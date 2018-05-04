<?php

namespace App\Http\Controllers;

use DB;
use Request;
use Confide;
use App\User;
use Carbon\Carbon;
use App\Models\Messenger\Thread;
use Cmgmyr\Messenger\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Cmgmyr\Messenger\Models\Participant;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MessagesController extends Controller
{
    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    public function index()
    {
        // All threads, ignore deleted/archived participants
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        /*$this->threads = $this->tr = Thread::getAllLatest(
                ($page === 1 ? 0 : $page+4)
            , 4)->get();*/
        $user = \Confide::user();
        $pr = config("messenger.participants_table");
        $tr = config("messenger.threads_table");
        $ms = config("messenger.messages_table");
        /*"SELECT * FROM messenger_participants AS pr INNER JOIN messenger_threads AS tr ON pr.thread_id = tr.id WHERE pr.user_id = 194 LIMIT 4 OFFSET 4";*/
        
        $unreadCount = function ($id, $lastRead = false) use ($user, $pr, $tr, $ms) {
            $d = DB::table($pr)
                ->select([DB::raw("COUNT(*) AS count_data")])
                ->join($ms, "{$pr}.thread_id", "=", "{$ms}.thread_id", "inner");
            if ($lastRead) {
                $d = $d->where("{$ms}.created_at", ">", $lastRead);
            }
            return $d
                ->where("{$ms}.thread_id", "=", $id)
                ->where("{$pr}.user_id", $user->id)->get()[0]->count_data;
        };
        $latestMessage = function ($id) use ($user, $pr, $tr, $ms) {
            return DB::table($ms)
                ->select(["body"])
                ->where("thread_id", "=", $id)
                ->orderBy("created_at", "desc")
                ->limit(1)
                ->get()[0]->body;
        };
        $creator = function ($id) use ($user, $pr, $tr, $ms) {
            return DB::table($ms)
                ->select(["users.username"])
                ->join("users", "{$ms}.user_id", "=", "users.id", "inner")
                ->where("{$ms}.thread_id", "=", $id)
                ->orderBy("{$ms}.created_at")
                ->limit(1)
                ->get()[0]->username;
        };
        $participants = function ($id) use ($user, $pr, $tr, $ms) {
            $r = DB::table($pr)
                ->select(["users.username"])
                ->join("users", "{$pr}.user_id", "=", "users.id")
                ->where("{$pr}.thread_id", "=", $id)
                ->get()
                ->toArray();
            $rr = [];
            foreach ($r as $val) {
                $rr[] = $val->username;
            }
            return $rr;
        };
        if (isset($_GET['search_type'], $_GET['search']) && strlen($_GET['search']) < 3) {
            return response()->json("w");
        }
        if (isset($_GET['search_type'], $_GET['search']) && $_GET['search_type'] == "message") {
            // "SELECT * FROM messenger_messages AS ms INNER JOIN messenger_threads AS tr ON ms.thread_id = tr.id INNER JOIN messenger_participants AS pr ON pr.thread_id = tr.id WHERE ms.body LIKE '%abc%' LIMIT 20 OFFSET 0;";
            $this->threads = DB::table("{$ms}")
                            ->select(["{$tr}.id as id", "{$tr}.subject", "{$pr}.last_read", "{$ms}.body"])
                            ->join("{$tr}", "{$tr}.id", "=", "{$ms}.thread_id")
                            ->join("{$pr}", "{$pr}.thread_id", "=", "{$tr}.id")
                            ->where("{$pr}.user_id", "=", $user->id)
                            ->where("{$ms}.body", "like", "%{$_GET['search']}%")
                            ->limit(20)
                            ->offset(0)
                            ->get();
                            $data = [];
            foreach ($this->threads as $thread) {

                    $data[] = [
                        'thread_id' => e($thread->id),
                        'subject' => e($thread->subject),
                        'unread_count' => $unreadCount($thread->id, $thread->last_read),
                        'body' => e($thread->body),
                        'creator' => $creator($thread->id),
                        'participants' => $participants($thread->id)
                    ];
            }

            return response()->json($data);
        } else {
            $limit = env("THREADS_PAGINATION_LIMIT");
                    $this->threads = DB::table($pr)
                    ->join($tr, "{$pr}.thread_id", "=", "{$tr}.id", "inner")
                    ->where("{$pr}.user_id", "=", $user->id);
                    if (isset($_GET['search'])) {
                        $this->threads = $this->threads->where("{$tr}.subject", "LIKE", "%{$_GET['search']}%");
                    }
                    $this->threads = $this->threads->orderBy("{$tr}.updated_at", "desc")
                    ->limit($limit)
                    ->offset($page == 1 ? 0 : ($page - 1) * $limit)
                    ->get();
        }

        
        if (isset($_GET["ajax_request"])) {
            $data = [];
            $user = Confide::user();
            foreach ($this->threads as $thread) {

                    $data[] = [
                        'thread_id' => e($thread->id),
                        'subject' => e($thread->subject),
                        'unread_count' => $unreadCount($thread->id, $thread->last_read),
                        'latest_message' => e($latestMessage($thread->id)),
                        'creator' => $creator($thread->id),
                        'participants' => $participants($thread->id)
                    ];
            }

            return response()->json($data);
        }

        // All threads that user is participating in
        // $threads = Thread::forUser(Auth::id())->latest('updated_at')->get();

        // All threads that user is participating in, with new messages
        // $threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();
        return view('messenger.index', ['threads' => $this->threads, 'that' => $this]);
    }

    public function addParticipants()
    {
        if (isset($_POST['data'])) {
            $data = json_decode($_POST['data'], true);
            if (is_array($data)) {
                $user = \Confide::user();
                if (isset($data['thread_name'])) {
                    $ss = [];
                    $s = explode(",", trim($data["thread_name"]));
                    $pr = config("messenger.participants_table");
                    foreach ($s as $val) {
                        $val = trim($val);
                        $st = DB::table("users")
                            ->select(["id", "confirmed"])
                            ->where("username", "like", $val)
                            ->first();
                        if (! $st) {
                            return response()->json(["message" => trans("msg.user_not_found", ["username" => $val])]);
                        }
                        if (! $st->confirmed) {
                            return response()->json(["message" => trans("msg.user_not_confirmed", ["username" => $val])]);
                        }
                        $st2 = DB::table($pr) 
                            ->select([DB::raw("COUNT(id) as count_data")])
                            ->where("user_id", "=", $st->id)
                            ->where("thread_id", "=", $data["thread_id"])
                            ->limit(1)
                            ->count();
                        if ($st2) {
                            return response()->json(["message" => trans("msg.user_already_joined", ["username" => $val])]);
                        }
                        Participant::create([
                            "thread_id" => $data["thread_id"],
                            "user_id" => $st->id,
                            "last_read" => null
                        ]);
                    }
                }
                if ($s) {
                    return response()->json(["message" => trans("msg.add_success")]);
                } else {
                    return response()->json(["message" => trans("msg.internal_error")]);
                }
            }
        }
        abort(404);
    }


    public function countIndexMessage()
    {
        return Thread::getAllLatestForPaginator();
    }  

    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $limit = env("MESSAGES_PAGINATION_LIMIT");
            $user = \Confide::user();
            $pr = config("messenger.participants_table");
            $tr = config("messenger.threads_table");
            $ms = config("messenger.messages_table");
            if (
                ! DB::table(config("messenger.participants_table"))
                    ->select("user_id")
                    ->where("user_id", "=", $user->id)
                    ->where("thread_id", "=", $id)
                    ->first()
            ) {
                throw new ModelNotFoundException("");
            }
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error', 'The thread with ID: ' . $id . ' was not found.');
            return redirect()->route('messages');
        }

        DB::table($pr)
            ->where("user_id", "=", $user->id)
            ->where("thread_id", "=", $id)
            ->limit(1)
            ->update([
                "last_read" => date("Y-m-d H:i:s")
            ]);

        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();

        // don't show the current user in list
        // $userId = Auth::id();
        // $users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();

        // $thread->markAsRead($userId);
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if (isset($_GET["ajax_request"])) {
            /*$data = [];
            foreach ($thread->messages as $message) {
                $data[] = [
                    "name" => empty($message->user->name) ? e($message->user->username) : e($message->user->name." (".$message->user->username.")"),
                    "body" => e($message->body),
                    "posted" => e($message->created_at->diffForHumans())
                ];
            }
            return response()->json($data);*/
            // "SELECT ms.id,users.username,ms.body,ms.created_at FROM messenger_messages AS ms INNER JOIN messenger_threads AS tr ON ms.thread_id = tr.id INNER JOIN users ON ms.user_id = users.id WHERE tr.id = 17 ORDER BY ms.created_at DESC LIMIT 3 OFFSET 0";
            $st = DB::table($ms)
                ->select(["{$ms}.id as msg_id", "users.username", "{$ms}.body", "{$ms}.created_at"])
                ->join("{$tr}", "{$ms}.thread_id", "=", "{$tr}.id")
                ->join("users", "{$ms}.user_id", "=", "users.id")
                ->where("{$tr}.id", "=", $id)
                ->where("{$ms}.deleted_at", "=", null)
                ->orderBy("{$ms}.created_at", "desc")
                ->limit($limit)
                ->offset($page == 1 ? 0 : ($page-1)*$limit);
            $data = [];
            foreach ($st->get() as $val) {
                $data[] = [
                    "id" => $val->msg_id,
                    "name" => e($val->username),
                    "body" => e($val->body),
                    "posted" => (new Carbon($val->created_at))->diffForHumans()
                ];
            }
            $data = [
                "last_page" => ceil($this->countCurrentMessage($id)/$limit),
                "data" => array_reverse($data)
            ];
            return response()->json($data);
        }

        $that = $this;
        $creator = function ($id) use ($user, $pr, $tr, $ms) {
            return DB::table($ms)
                ->select(["users.username"])
                ->join("users", "{$ms}.user_id", "=", "users.id", "inner")
                ->where("{$ms}.thread_id", "=", $id)
                ->orderBy("{$ms}.created_at")
                ->limit(1)
                ->get()[0]->username;
        };
        $creator = $creator($id);
        $r = DB::table($pr)
            ->select(["users.username"])
            ->join("users", "{$pr}.user_id", "=", "users.id")
            ->where("{$pr}.thread_id", "=", $id)
            ->get()
            ->toArray();
        $pars = [];
        foreach ($r as $val) {
            $pars[] = $val->username;
        }
        return view('messenger.show', compact('thread', 'users', 'that', 'page', 'id', 'creator', 'pars'));
    }

    public function countCurrentMessage($id)
    {
        $ms = config("messenger.messages_table");
        $st = DB::table($ms)
                ->where("thread_id", "=", $id)
                ->count();
        return $st;                
    }


    /**
     * Creates a new message thread.
     *
     * @return mixed
     */
    public function create()
    {

        $users = User::where('id', '!=', Auth::id())->get();

        return view('messenger.create', compact('users'));
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store()
    {
        Request::flash();
        $input = Input::all();
        $user = \Confide::user();
        if (Input::has('recipients')) {
            $thread = Thread::create([
                'subject' => $input['subject'],
            ]);
            $re = explode(",",trim($input['recipients']));
            $id = [];
            foreach ($re as $val) {
                $val = trim($val);
                $st = DB::table("users")
                      ->select(["id", "confirmed"])
                      ->where("username", "like", $val)
                      ->first();
                if ($val === $user->username) {
                    return \Redirect::to(url()->previous())->with("error", trans("msg.self_message"));
                }
                if (! $st) {
                    return \Redirect::to(url()->previous())->with("error", trans("msg.user_not_found", ["username" => trim($val)]));
                }
                if (! $st->confirmed) {
                    return \Redirect::to(url()->previous())->with("error", trans("msg.user_not_confirmed", ["username" => trim($val)]));
                }
                $id[] = $st->id;
            }
            $thread->addParticipant($id);
        } else {
             return \Redirect::to(url()->previous())->with("error", trans("msg.user_not_found", ["username" => ""]));
        }
        

        

        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $input['message'],
        ]);

        // Sender
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'last_read' => new Carbon,
        ]);

        if (isset($_GET["ajax_request"])) {
            return response()->json("OK", 200);
        }
        return redirect()->route('messages');
    }

    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return redirect()->route('messages');
        }

        $thread->activateAllParticipants();

        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => Input::get('message'),
        ]);

        // Add replier as a participant
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
        ]);
        $participant->last_read = new Carbon;
        $participant->save();

        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipant(json_decode(urldecode(Input::get('recipients')), true));
        }
        if (isset($_GET["ajax_request"])) {
            return response()->json("OK", 200);
        }
        return redirect()->route('messages.show', $id);
    }

    public function deleteThread()
    {
        if (isset($_POST['data'])) {
            $data = json_decode($_POST['data'], true);
            if (is_array($data)) {
                $user = \Confide::user();
                $q = DB::table(config("messenger.messages_table"))
                    ->select("user_id")
                    ->where("thread_id", "=", $data['thread_id'])
                    ->orderBy("created_at")
                    ->first();
                if ($q->user_id === $user->id) {
                    if (DB::table(config("messenger.threads_table"))
                        ->where("id", "=", $data["thread_id"])
                        ->limit(1)
                        ->delete()) {
                        return response()->json(["message" => trans("msg.delete_thread_success")]);
                    } else {
                        return response()->json(["message" => trans("msg.internal_error")]);
                    }
                } else {
                    return response()->json(["message" => trans("msg.delete_thread_invalid_permission")]);
                }
            }
        }
        abort(404);
    }

    public function leaveThread()
    {
        if (isset($_POST['data'])) {
            $data = json_decode($_POST['data'], true);
            if (is_array($data)) {
                $user = \Confide::user();
                $q = DB::table(config("messenger.messages_table"))
                    ->select("user_id")
                    ->where("thread_id", "=", $data['thread_id'])
                    ->orderBy("created_at")
                    ->first();
                if (DB::table(config("messenger.participants_table"))
                        ->where("thread_id", "=", $data["thread_id"])
                        ->where("user_id", "=", $user->id)
                        ->delete()
                ) {
                    return response()->json(["message" => trans("msg.leave_chat_success")]);
                } else {
                    return response()->json(["message" => trans("msg.internal_error")]);
                }
            }
        }
        abort(404);
    }
}
