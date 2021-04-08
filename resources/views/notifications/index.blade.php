@extends('layouts.admin')

@section('content')

    @foreach ($notifications as $notification)
    @if ($notification->is_viewed == false)
        <div class="card card-notification">
            <div class="card-body">
                <h4 class="card-title"><b>{{$notification->title}}</b></h4>
            {!! $notification->body !!} <br> <a href="{{route("showNotification", $notification->id)}}">View Notification</a>
            </div>
          </div>
    @else
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><b>{{$notification->title}}</b></h4>
            {!! $notification->body !!} <br> <a href="{{route("showNotification", $notification->id)}}">Mark as read.</a>
            </div>
          </div>
    @endif
          
    @endforeach
    {{ $notifications->links() }}
@endsection