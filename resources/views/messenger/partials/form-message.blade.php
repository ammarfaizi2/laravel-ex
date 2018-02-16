<h2>Add a new message</h2>
<form action="{{ route('messages.update', $thread->id) }}" method="post" id="sendMessageForm">
    <input type="hidden" name="_method" value="put">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="_token">

    <!-- Message Form Input -->
    <div class="form-group">
        <textarea name="message" id="body" class="form-control">{{ old('message') }}</textarea>
    </div>

    @if($users->count() > 0)
        <div class="checkbox">
            @php $k = 0; @endphp
            @foreach($users as $user)
                <label title="{{ $user->name }}">
                    <input type="checkbox" class="recipients" name="recipients{{$k++}}" value="{{ $user->id }}">{{ $user->name }}
                </label>
            @endforeach
        </div>
    @endif

    <!-- Submit Form Input -->
    <div class="form-group">
        <button type="submit" class="btn btn-primary form-control">Submit</button>
    </div>
</form>
<script type="text/javascript">
    class _sendMessage {
        constructor () {
            this.form = $("#sendMessageForm")[0];
            this.action = this.form.action;
            this.form.action = "javascript:void(0);";
        }
        listen() {
            var that = this;
            this.form.addEventListener("submit", function () {
                var postContext = that.buildContext();
                if (postContext !== false) {
                    $.ajax({
                        type: "PUT",
                        url: that.action + "?ajax_request=1",
                        datatype: "json",
                        data: postContext,
                        success: function (response) {
                            alert(response);
                            $("#body")[0].value = "";
                        }
                    });
                }
            });
        }
        buildContext() {
            var r = $(".recipients"), rr = [], x, i = 0,
            q = {
                "_method": "put",
                "_token": $("#_token").val(),
                "message": $("#body").val()
            };
            if (r.length) {
                for (x in r) {
                    if (r[x].checked) {
                        rr[i++] = r[x].value;
                    }
                }
                q["recipients"] = encodeURIComponent(JSON.stringify(rr));
            }
            if (! q["message"].trim()) {
                return false;
            }
            var context = "";
            for (x in q) {
                context += encodeURIComponent(x) + "=" + encodeURIComponent(q[x]) + "&";
            }
            return context;
        }
    }
    var st = new _sendMessage();
        st.listen();
</script>