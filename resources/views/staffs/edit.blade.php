@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
      <h4 class="card-title">Edit Staff</h4>
      <p class="card-category">Edit the form below to update a staff.</p>
    </div>
    <div class="card-body">
    <form action="{{route('updateStaff', $staff->staff_number)}}" method="POST">
        @csrf
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">First Name</label>
            <input type="text" class="form-control" name="first_name" value="{{$staff->name}}" pattern="^[a-zA-Z]+(\s[a-zA-Z]+)?$" title="Numbers  and special charactors are not allowed.">
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
              <input type="text" class="form-control" name="last_name" value="{{$staff->surname}}" pattern="^[a-zA-Z]+(\s[a-zA-Z]+)?$" title="Numbers  and special charactors are not allowed.">
            </div>
            @if ($errors->has('last_name'))
                <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('last_name') }}</strong>
                </span>
            @endif
          </div>
        </div>
        <button type="submit" class="btn btn-primary pull-right">Update Staff</button>
        <div class="clearfix"></div>
      </form>
    </div>
  </div>
@endsection