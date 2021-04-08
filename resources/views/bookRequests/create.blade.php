@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
      <h4 class="card-title">Request Book</h4>
      <p class="card-category">Fill in the form below to request a book.</p>
    </div>
    <div class="card-body">
    <form action="{{route("saveNewBookRequest")}}" method="POST">
        @csrf

        <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">Quantity
                        <i class="text-danger">*</i>
                    </label>
                    <input type="number" class="form-control" name="quantity" value="{{old("quantity")}}">
                  </div>
                  @if ($errors->has('quantity'))
                      <span class="invalid-feedback" style="display: block;" role="alert">
                          <strong>{{ $errors->first('quantity') }}</strong>
                      </span>
                  @endif
                </div>
        </div>

        <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">Select Book
                        <i class="text-danger">*</i>
                    </label>
                    <select class="form-control" name="book" data-style="btn btn-link" id="exampleFormControlSelect1">
                        @foreach ($books as $book)
                            <option value="{{$book->id}}">{{$book->title}}, {{$book->edition}}th</option>
                        @endforeach    
                    </select>
                  </div>
                  @if ($errors->has('book_description'))
                      <span class="invalid-feedback" style="display: block;" role="alert">
                          <strong>{{ $errors->first('book_description') }}</strong>
                      </span>
                  @endif
                </div>
        </div>

        <button type="submit" class="btn btn-primary pull-right">Request Book</button>
        <div class="clearfix"></div>
      </form>
    </div>
  </div>
@endsection