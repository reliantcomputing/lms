@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
      <h4 class="card-title">{{$bookRequest->book()->department->name}}</h4>
      <p class="card-category"></p>
    </div>
    <div class="card-body">
        <h4><b>Book Description</b></h4>
        <b>Book title: </b> {{$bookRequest->book()->title}}<br>
        <b>Author: </b> {{$bookRequest->book()->author}}<br>
        <b>Place Of Publication: </b> {{$bookRequest->book()->place_of_publication}} <br>
        <b>Quantity: </b> {{$bookRequest->number_of_books}} <br>
        <b>ISBN Number: </b>{{$bookRequest->book()->isbn_number}} <br>
        <b>Stock Price: </b>R{{$bookRequest->book()->stock_price}} <br>
        <b>Sell Price: </b>R{{$bookRequest->book()->sell_price}} <br>
        <b>Total Cost: </b>R{{$bookRequest->book()->stock_price*$bookRequest->number_of_books}} <br>
        @if (Auth::user()->role->name == "ROLE_LIBRARY")
            @if ($bookRequest->is_accepted == false && $bookRequest->is_rejected == false)
                <a href="{{route("approve", $bookRequest->id)}}" onclick="return confirm('Are you sure you want to process this request?')" class="btn btn-success">Process</a>
                <a href="{{route("reject", $bookRequest->id)}}" onclick="return confirm('Are you sure you want to reject this request?')" class="btn btn-danger">Reject</a>
            @endif
        @endif  
    </div>
    <div class="card-footer">
      <b>Requested By: {{$bookRequest->staff_number}}</b><p><i class="material-icons">access_time</i> <span class="mb-2">{{$bookRequest->created_at}}</span></p> 
    </div>
  </div>
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Approve new book request.</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form action="" method="post">
            <div class="form-group">
              @csrf
                <label class="bmd-label-floating">Price
                    <i class="text-danger">*</i>
                </label>
                <input type="number" class="form-control" step="0.01" name="price" required>
            </div>
            <button type="submit" class="btn btn-success">Approve</button>
        </form>
        </div>
      </div>
    </div>
  </div>
@endsection