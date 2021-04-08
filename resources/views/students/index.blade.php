@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-primary">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ">List of Students</h4>
                </div>
                <div class="col-md-6 mx-auto">
                    <p class="card-category">
                        <a class="btn btn-warning btn-sm" href="{{route("createStudent")}}">Add Student</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead class=" text-primary">
                <th>
                  Student Number
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
                @foreach ($students as $student)
                  <tr>
                    <td>
                      {{$student->student_number}}
                    </td>
                    <td>
                      {{$student->name}}
                    </td>
                    <td>
                      {{$student->surname}}
                    </td>
                    <td>
                      {{$student->email}}
                    </td>
                    <td>
                      <a class="btn btn-success btn-sm"  href="{{route('editStudent', $student->student_number)}}">Edit</a> |
                      <a class="btn btn-success btn-sm"  href="{{route('deleteStudent', $student->student_number)}}" onclick="return confirm('Are you sure you want to delete this students: {{$student->email}}?');">Delete</a>
                    </td>
                  </tr>   
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection