<?php

namespace App\Http\Controllers;

use DB;
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
        $threads = Thread::getAllLatest()->get();
        if (isset($_GET["ajax_request"])) {
            $data = [];
            foreach ($threads as $thread) {
                $creator = $thread->creator()->username;
                $data[] = [
                    'is_unread' => $thread->isUnread(Auth::id()),
                    'thread_id' => $thread->id,
                    'subject' => $thread->subject,
                    'unread_count' => $thread->userUnreadMessagesCount(Auth::id()),
                    'latest_message' => $thread->latestMessage->body,
                    'creator' => $creator,
                    'participants' => array_unique(explode(",",trim($creator.",".$thread->participantsString(Auth::id()), ",")))
                ];
            }

            return response()->json($data);
        }

        // All threads that user is participating in
        // $threads = Thread::forUser(Auth::id())->latest('updated_at')->get();

        // All threads that user is participating in, with new messages
        // $threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();

        return view('messenger.index', compact('threads'));
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
        $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return redirect()->route('messages');
        }

        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();

        // don't show the current user in list
        $userId = Auth::id();
        $users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();

        $thread->markAsRead($userId);
        if (isset($_GET["ajax_request"])) {
            $data = [];
            foreach ($thread->messages as $message) {
                $data[] = [
                    "name" => empty($message->user->name) ? e($message->user->username) : e($message->user->name." (".$message->user->username.")"),
                    "body" => e($message->body),
                    "posted" => e($message->created_at->diffForHumans())
                ];
            }
            return response()->json($data);
        }

        return view('messenger.show', compact('thread', 'users'));
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
        $input = Input::all();

        $thread = Thread::create([
            'subject' => $input['subject'],
        ]);

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

        // Recipients
        if (Input::has('recipients')) {
            $re = explode(",",$input['recipients']);
            $id = [];
            foreach ($re as $val) {
                $st = DB::table("users")
                      ->select("id")
                      ->where("username", "=", trim($val))
                      ->first();
                if (! $st) {
                    return \Redirect::to(url()->previous())->with("error", trans("msg.user_not_found", ["username" => trim($val)]));
                }
                $id[] = $st->id;
            }
            $thread->addParticipant($id);
        }
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
}
