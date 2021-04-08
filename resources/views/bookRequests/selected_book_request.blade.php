@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
      <h4 class="card-title">Request Book(s)</h4>
    <p class="card-category">{{$book->title}}, {{$book->edition}}th</p>
    </div>
    <div class="card-body">
    <form action="{{route("selectedBookRequest", $book->id)}}" method="POST">
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

        <button type="submit" class="btn btn-primary pull-right">Request Book(s)</button>
        <div class="clearfix"></div>
      </form>
    </div>
  </div>
@endsection