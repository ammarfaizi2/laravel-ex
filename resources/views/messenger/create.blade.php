@extends('layouts.default')

@section('content')
    <div style="margin:2% 3% 2% 3%;">
        <a href="{{ route('messages') }}">{{ trans('msg.back') }}</a>
        <h1>Create a new message</h1>
        @include('messenger.partials.flash')
        <form action="{{ route('messages.store') }}" method="post">
            {{ csrf_field() }}
            <div class="col-md-6">
                <!-- Subject Form Input -->
                <div class="form-group">
                    <label class="control-label">Subject</label>
                    <input required type="text" class="form-control" name="subject" placeholder="Subject"
                           value="{{ old('subject') }}">
                </div>

                <!-- Message Form Input -->
                <div class="form-group">
                    <label class="control-label">Message</label>
                    <textarea required style="resize:none;" name="message" class="form-control">{{ old('message') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="control-label">Recepients</label>
                    <input required class="form-control" placeholder="username1,username2,username3" type="text" name="recipients">
                </div>

                <!-- Submit Form Input -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary form-control">Send</button>
                </div>
            </div>
        </form>
    </div>
@stop
