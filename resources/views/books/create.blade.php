@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
      <h4 class="card-title">Add Book</h4>
      <p class="card-category">Fill the form below to add a book.</p>
    </div>
    <div class="card-body">
    <form action="{{route("saveBook")}}" method="POST">
        @csrf
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">Title
                  <i class="text-danger">*</i>
              </label>
            <input type="text" class="form-control" name="title" value="{{old("title")}}">
            </div>
            @if ($errors->has('title'))
                <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">ISBN Number
                    <i class="text-danger">*</i>
              </label>
            <input type="number" class="form-control" name="isbn_number" value="{{old("isbn_number")}}">
            </div>
            @if ($errors->has('isbn_number'))
                <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('isbn_number') }}</strong>
                </span>
            @endif
          </div>
        </div>

        <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Author
                        <i class="text-danger">*</i>
                    </label>
                    <input type="text" class="form-control" name="author" value="{{old("author")}}">
                  </div>
                  @if ($errors->has('author'))
                      <span class="invalid-feedback" style="display: block;" role="alert">
                          <strong>{{ $errors->first('author') }}</strong>
                      </span>
                  @endif
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Place of publication
                            <i class="text-danger">*</i>
                    </label>
                    <input type="text" class="form-control" name="place_of_publication" value="{{old("place_of_publication")}}">
                  </div>
                  @if ($errors->has('place_of_publication'))
                      <span class="invalid-feedback" style="display: block;" role="alert">
                          <strong>{{ $errors->first('place_of_publication') }}</strong>
                      </span>
                  @endif
                </div>
        </div>

        <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Edition
                        <i class="text-danger">*</i>
                    </label>
                    <input type="number" class="form-control" name="edition" value="{{old("edition")}}">
                  </div>
                  @if ($errors->has('edition'))
                      <span class="invalid-feedback" style="display: block;" role="alert">
                          <strong>{{ $errors->first('edition') }}</strong>
                      </span>
                  @endif
                </div>
                <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Stock Price (ZAR)
                              <i class="text-danger">*</i>
                          </label>
                          <input type="number" class="form-control" name="stock_price" value="{{old("stock_price")}}">
                        </div>
                        @if ($errors->has('stock_price'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('stock_price') }}</strong>
                            </span>
                        @endif
                      </div>
        </div>


        <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">Select Department
                            <i class="text-danger">*</i>
                    </label>
                    <select class="form-control" name="department" data-style="btn btn-link" id="exampleFormControlSelect1">
                        @foreach ($departments as $department)
                            <option value="{{$department->id}}">{{$department->name}}</option>
                        @endforeach    
                    </select>
                  </div>
                  @if ($errors->has('department'))
                      <span class="invalid-feedback" style="display: block;" role="alert">
                          <strong>{{ $errors->first('department') }}</strong>
                      </span>
                  @endif
                </div>
        </div>
        <button type="submit" class="btn btn-primary pull-right">Add Book</button>
        <div class="clearfix"></div>
      </form>
    </div>
  </div>
@endsection