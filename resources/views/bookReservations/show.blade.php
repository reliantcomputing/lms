@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
      <h4 class="card-title">Book Reservation</h4>
      <p class="card-category"></p>
    </div>
    <div class="card-body">
        <h4><b>Book Description</b></h4>
        <b>Book title: </b> {{$bookReservation->book->title}}<br>
        <b>Author: </b> {{$bookReservation->book->author}}<br>
        <b>Place Of Publication: </b> {{$bookReservation->book->place_of_publication}} <br>
        <b>Edition: </b>{{$bookReservation->book->edition}}th <br>
        <b>Quantity: </b> 1 <br>
        <b>ISBN Number: </b>{{$bookReservation->book->isbn_number}} <br>
        <b>Price: </b> R{{$bookReservation->book->sell_price}}<br>
        @if (Auth::user()->role->name == "ROLE_LIBRARY")
            @if ($bookReservation->is_accepted == false && $bookReservation->is_rejected == false)
                <a href="{{route("approveBookReservation", $bookReservation->id)}}" onclick="return confirm('Are you sure you want to mark this book collected?')" class="btn btn-success">Collect</a>
                <a href="{{route("rejectBookReservation", $bookReservation->id)}}" onclick="return confirm('Are you sure you want to reject or cancel this request?')" class="btn btn-danger">Cancel</a>
            @endif
        @endif  
    </div>
    <div class="card-footer">
      <b>Requested By: {{$bookReservation->student_number}}</b><p><i class="material-icons">access_time</i> <span class="mb-2">{{$bookReservation->created_at}}</span></p> 
    </div>
  </div>
  
  <!-- Modal -->
@endsection