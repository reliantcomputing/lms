@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-primary">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ">Book requests</h4>
                </div>
                <div class="col-md-6 mx-auto">
                    <p class="card-category">
                        @if (Auth::user()->role->name == "ROLE_DEPARTMENT" || Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT")
                            <a class="btn btn-warning btn-sm" href="{{route("createBookRequest")}}">Request book</a>
                        @endif 

                        &nbsp;
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
                    Requested on
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
                <th>Status</th>
                <th>
                    Action
                </th>
              </thead>
              <tbody>
                @foreach ($bookRequests as $bookRequest)
                  <tr>
                      <td>
                        {{$bookRequest->created_at}}  
                      </td>
                    <td>
                      {{$bookRequest->staff_number}}
                    </td>
                    <td>
                      @if ($bookRequest->librarian_number != null)
                        {{$bookRequest->librarian_number}}
                      @else
                        <span class="badge badge-danger">Not Seen</span>
                      @endif
                    </td>
                    <td>
                      {{$bookRequest->number_of_books}}
                    </td>
                    <td>
                      {{$bookRequest->status}}
                    </td>
                    <td>
                    <a href="{{route("showBookRequest", $bookRequest->id)}}" class="btn btn-primary btn-sm">View</a>
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
              <form role="form" method="POST" action="{{ route('printBookRequest') }}">
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
                                    <option value="accepted">Accepted</option>
                                    <option value="rejected">Rejected</option>
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