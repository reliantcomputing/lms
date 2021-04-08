@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
      <h4 class="card-title">{{$notification->title}}</h4>
    </div>
    <div class="card-body">
        {!! $notification->body !!}
    </div>
    <div class="card-footer">
        {{$notification->created_at->diffForHumans()}}
    </div>
  </div>
@endsection