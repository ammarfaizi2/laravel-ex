
<div style="margin-top:10px;">
    <form action="{{ route('messages.update', $thread->id) }}" method="post" id="sendMessageForm">
        <input type="hidden" name="_method" value="put">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="_token">

        <!-- Message Form Input -->
        <div class="form-group">
            <textarea required style="resize: none;" name="message" id="body" class="form-control">{{ old('message') }}</textarea>
        </div>

        <!-- Submit Form Input -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary form-control">Submit</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    class _sendMessage {
        constructor () {
            this.form = $("#sendMessageForm")[0];
            this.action = this.form.action;
            this.form.action = "javascript:void(0);";
        }
    }
    _sendMessage.prototype.listen = function() {
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
                        $("#body")[0].value = "";
                        document.getElementById( 'bottom' ).scrollIntoView();
                    }
                });
            }
        });
    };
    _sendMessage.prototype.buildContext = function() {
        var rr = [], x, i = 0,
        q = {
            "_method": "put",
            "_token": $("#_token").val(),
            "message": $("#body").val()
        };
        if (! q["message"].trim()) {
            return false;
        }
        var context = "";
        for (x in q) {
            context += encodeURIComponent(x) + "=" + encodeURIComponent(q[x]) + "&";
        }
        return context;
    };
    var st = new _sendMessage();
        st.listen();
</script>