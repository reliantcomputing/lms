@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
      <h4 class="card-title">Change Password</h4>
    </div>
    <div class="card-body">
    <form action="{{route("update-password")}}" method="POST">
        @csrf
        <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">Current Password</label>
                  <input type="password" class="form-control" name="current_password" value="{{old("current_password")}}">
                  </div>
                  @if ($errors->has('current_password'))
                      <span class="invalid-feedback" style="display: block;" role="alert">
                          <strong>{{ $errors->first('current_password') }}</strong>
                      </span>
                  @endif
                </div>
              </div>
        <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">New Password</label>
                  <input type="password" class="form-control" name="password">
                  </div>
                  @if ($errors->has('password'))
                      <span class="invalid-feedback" style="display: block;" role="alert">
                          <strong>{{ $errors->first('password') }}</strong>
                      </span>
                  @endif
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirm">
                  </div>
                  @if ($errors->has('password_confirm'))
                      <span class="invalid-feedback" style="display: block;" role="alert">
                          <strong>{{ $errors->first('password_confirm') }}</strong>
                      </span>
                  @endif
                </div>
              </div>
        <button type="submit" class="btn btn-primary pull-right">Change Password</button>
        <div class="clearfix"></div>
      </form>
    </div>
  </div>
@endsection