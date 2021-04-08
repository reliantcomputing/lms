@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
      <h4 class="card-title">Order Book</h4>
      <p class="card-category">{{$book->title}}, {{$book->edition}}th</p>
    </div>
    <div class="card-body">
    <form action="{{route("order", $book->id)}}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="bmd-label-floating">Number Of Books
                    </label>
                    <input type="text" class="form-control" name="number_of_books" value="{{old("number_of_books")}}">
                </div>
                @if ($errors->has('number_of_books'))
                    <span class="invalid-feedback" style="display: block;" role="alert">
                        <strong>{{ $errors->first('number_of_books') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <button type="submit" class="btn btn-primary pull-right">Order Books</button>
        <div class="clearfix"></div>
      </form>
    </div>
  </div>
@endsection