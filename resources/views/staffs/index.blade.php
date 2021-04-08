@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-primary">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ">List of staffs</h4>
                </div>
                <div class="col-md-6 mx-auto">
                    <p class="card-category">
                        <a class="btn btn-warning btn-sm" href="{{route("createStaff")}}">Add Staff</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead class=" text-primary">
                <th>
                 Staff Number
                </th>
                <th>
                  First  Name
                </th>
                <th>
                  Surname
                </th>
                <th>
                  Email
                </th>
                <th>
                  Action
                </th>
              </thead>
              <tbody>
                @foreach ($staffs as $staff)
                  @if ($staff->email != Auth::user()->email)
                  <tr>
                      <td>
                        {{$staff->staff_number}}
                      </td>
                      <td>
                        {{$staff->name}}
                      </td>
                      <td>
                        {{$staff->surname}}
                      </td>
                      <td>
                        {{$staff->email}}
                      </td>
                      <td>
                        <a class="btn btn-success btn-sm"  href="{{route('editStaff', $staff->staff_number)}}">Edit</a> |
                        <a class="btn btn-danger btn-sm"  href="{{route('deleteStaff', $staff->staff_number)}}" onclick="return confirm('Are you sure you want to delete this staffs: {{$staff->email}}?');">Delete</a>
                      </td>
                    </tr> 
                  @endif
                    
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection