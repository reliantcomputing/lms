@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
      <h4 class="card-title">{{$newBookRequest->department->name}}</h4>
      <p class="card-category"></p>
    </div>
    <div class="card-body">
        <h4><b>Book Description</b></h4>
        <b>Book title: </b> {{$newBookRequest->title}}<br>
        <b>Author: </b> {{$newBookRequest->author}}<br>
        <b>Place Of Publication: </b> {{$newBookRequest->place_of_publication}} <br>
        <b>Quantity: </b> {{$newBookRequest->quantity}} <br>
        <b>ISBN Number: </b>{{$newBookRequest->isbn_number}} <br>

        @if ($newBookRequest->price)
          <b>Price: </b>R{{$newBookRequest->price}} <br>
          <b>Total Price: </b>R{{$newBookRequest->price * $newBookRequest->quantity}} <br>
        @endif

        @if (Auth::user()->role->name == "ROLE_DEPARTMENT" || Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT")
          @if ($newBookRequest->library_accepted == true)
            <a href="{{route("departmentAcceptNewBookRequest", $newBookRequest->id)}}" class="btn btn-success">Accept</a>
            <a href="{{route("departmentRejectNewBookRequest", $newBookRequest->id)}}" class="btn btn-danger">Reject</a>
          @endif
        @else
          @if ($newBookRequest->library_accepted != false || $newBookRequest->library_rejected != false)
            @if ($newBookRequest->department_accepted != false || $newBookRequest->department_rejected != false)
                @if ($newBookRequest->is_processed == false)
                <a href="{{route("processNewBookRequest", $newBookRequest->id)}}" class="btn btn-success">Process</a>
                @endif
            @else
            
            @endif
          @else
          <a href="#" class="btn btn-success" data-toggle="modal" data-target="#exampleModalCenter">Send Price Notification</a>
          <a href="{{route("libraryRejectNewBookRequest", $newBookRequest->id)}}" class="btn btn-danger">Reject</a>
          @endif
        @endif
    </div>
    <div class="card-footer">
      <b>Requested By: {{$newBookRequest->staff_number}}</b><p><i class="material-icons">access_time</i> <span class="mb-2">{{$newBookRequest->created_at}}</span></p> 
    </div>
  </div>
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Notify the department.</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form action="{{route("libraryUpdatePrice", $newBookRequest->id)}}" method="post">
            <div class="form-group">
              @csrf
                <label class="bmd-label-floating">Price
                    <i class="text-danger">*</i>
                </label>
                <input type="number" class="form-control" step="0.01" name="price" required>
            </div>
            <button type="submit" class="btn btn-success">Send Price Notification</button>
        </form>
        </div>
      </div>
    </div>
  </div>
@endsection