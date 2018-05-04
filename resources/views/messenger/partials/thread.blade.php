<?php $class = $thread->isUnread(Auth::id()) ? 'alert-info' : ''; ?>

<div class="media alert {{ $class }}">
    <h4 class="media-heading">
        <a href="{{ route('messages.show', $thread->id) }}">{{ $thread->subject }}</a>
        ({{ $thread->userUnreadMessagesCount(Auth::id()) }} unread)</h4>
    <p>
        {{ $thread->latestMessage->body }}
    </p>
    <p>
        <small><strong>Creator:</strong> {{ $creator = $thread->creator()->username }}</small>
    </p>
    <p>
        <small><strong>Participants:</strong> {{ implode(",",array_unique(explode(",",trim($creator.",".$thread->participantsString(Auth::id()), ",")))) }}</small>
    </p>
</div>