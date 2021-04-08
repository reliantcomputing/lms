@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-primary">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ">New book requests</h4>
                </div>
                <div class="col-md-6 mx-auto">
                    <p class="card-category">
                        @if (Auth::user()->role->name == "ROLE_DEPARTMENT" || Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT")
                            <a class="btn btn-warning btn-sm" href="{{route("createNewBookRequest")}}">Request new book</a>
                        @endif 
                      
                        <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#exampleModalCenter">
                            Generate Report
                        </a> 
                        
                    </p>
                </div>
            </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead class=" text-primary">
                <th>
                    Requested
                </th>
                <th>
                  ISBN
                </th>
                <th>
                  Staff Number
                </th>
                <th>
                  Librarian Number
                </th>
                <th>
                  Quantity
                </th>
                <th>
                  Status
                </th>
                <th>
                    Action
                </th>
              </thead>
              <tbody>
                @foreach ($newBookRequests as $newBookRequest)
                  <tr>
                      <td>
                        {{$newBookRequest->created_at}}
                        @if (Auth::user()->role->name == "ROLE_DEPARTMENT" || Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT")
                          @if ($newBookRequest->department_accepted == false && $newBookRequest->department_rejected == false && $newBookRequest->library_rejected != true)
                            <span class="text-danger">
                                <i class="material-icons">notifications_active</i>
                            </span>
                          @endif
                        @else
                          @if ($newBookRequest->library_accepted != false || $newBookRequest->library_rejected != false)
                            @if ($newBookRequest->department_accepted != false || $newBookRequest->department_rejected != false)
                                @if ($newBookRequest->is_processed == false)
                                <span class="text-danger">
                                    <i class="material-icons">notifications_active</i>
                                </span>
                                @endif
                            @else
                            
                            @endif
                          @else
                          <span class="text-danger">
                              <i class="material-icons">notifications_active</i>
                          </span>
                          @endif
                        @endif
                      </td>
                      <td>
                          {{$newBookRequest->isbn_number}}
                        </td>
                    <td>
                      {{$newBookRequest->staff_number}}
                    </td>
                    <td>
                      @if ($newBookRequest->librarian_number != null)
                        {{$newBookRequest->librarian_number}}
                      @else
                        <span class="badge badge-danger">Not Seen</span>
                      @endif
                    </td>
                    <td>
                      {{$newBookRequest->quantity}}
                    </td>
                    <td>
                      {{$newBookRequest->status}}
                    </td>
                    <td>
                    <a href="{{route("showNewBookRequest", $newBookRequest->id)}}" class="btn btn-primary btn-sm pr-1 pl-1">
                      <i class="fa fa-eye"></i>
                    </a>
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

  <!-- Modal -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Generate report</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <form role="form" method="POST" action="{{ route('export') }}">
                  @csrf()
                  
                  <div class="row">
                          <div class="col-md-12">
                              <label for="book">
                                  <b>File Type</b>
                              </label>
                              <div class="form-group{{ $errors->has('book') ? ' has-danger' : '' }}">
                                  <select class="form-control{{ $errors->has('book') ? ' is-invalid' : '' }}" name="type">
                                    <option value="PDF">PDF</option>  
                                    <option value="Excel">Excel</option>
                                  </select>
                              </div>
                              <label for="book">
                                  <b>Condition</b>
                              </label>
                              <div class="form-group{{ $errors->has('book') ? ' has-danger' : '' }}">
                                  <select class="form-control{{ $errors->has('book') ? ' is-invalid' : '' }}" name="sort">
                                    <option value="all">All</option>  
                                    <option value="department_accepted">Department Accepted</option>
                                    <option value="department_rejected">Department Rejected</option>
                                    <option value="library_accepted">Library Accepted</option>
                                    <option value="library_rejected">Library Rejected</option>
                                    <option value="processed">Processed</option>
                                  </select>
                              </div>
                          </div>
                          @if ($errors->has('book'))
                              <span class="invalid-feedback" style="display: block;" role="alert">
                                  <strong>{{ $errors->first('book') }}</strong>
                              </span>
                          @endif
                          
                      </div>
                  <div class="text-center">
                      <button type="submit" class="btn btn-info btn-lg btn-block">{{ __('Download') }}</button>
                  </div>
           </form>  
          </div>
        </div>
      </div>
    </div>
  

@endsection