@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
      <h4 class="card-title">Add Staff</h4>
      <p class="card-category">Fill in the form below to add a staff.</p>
    </div>
    <div class="card-body">
    <form action="{{route("saveStaff")}}" method="POST">
        @csrf
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">First Name</label>
              <input type="text" class="form-control" name="first_name" pattern="^[a-zA-Z]+(\s[a-zA-Z]+)?$" title="Numbers  and special charactors are not allowed.">
            </div>
            @if ($errors->has('first_name'))
                <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('first_name') }}</strong>
                </span>
            @endif
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">Last Name</label>
              <input type="text" class="form-control" name="last_name" pattern="^[a-zA-Z]+(\s[a-zA-Z]+)?$" title="Numbers  and special charactors are not allowed.">
            </div>
            @if ($errors->has('last_name'))
                <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('last_name') }}</strong>
                </span>
            @endif
          </div>
        </div>

        <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Staff Number</label>
                    <input type="number" class="form-control" name="staff_number">
                  </div>
                  @if ($errors->has('staff_number'))
                      <span class="invalid-feedback" style="display: block;" role="alert">
                          <strong>{{ $errors->first('staff_number') }}</strong>
                      </span>
                  @endif
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Email Address</label>
                    <input type="text" class="form-control" name="email">
                  </div>
                  @if ($errors->has('email'))
                      <span class="invalid-feedback" style="display: block;" role="alert">
                          <strong>{{ $errors->first('email') }}</strong>
                      </span>
                  @endif
                </div>
              </div>
        <button type="submit" class="btn btn-primary pull-right">Add Staff</button>
        <div class="clearfix"></div>
      </form>
    </div>
  </div>
@endsection