@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-primary">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ">List of Books</h4>
                </div>
                <div class="col-md-6 mx-auto">
                    <p class="card-category">
                        @if (Auth::user()->role->name == "ROLE_LIBRARY" || Auth::user()->role->name == "ROLE_SUPER_LIBRARY")
                            <a class="btn btn-warning btn-sm" href="{{route("createBook")}}">New Book</a>
                        @endif
                        @if (Auth::user()->role->name == "ROLE_DEPARTMENT" || Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT")
                            <a class="btn btn-warning btn-sm" href="{{route("createNewBookRequest")}}">Request new book</a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table" id="dataTable" width="100%" cellspacing="0">
              <thead class=" text-primary">
                <th>
                  Title
                </th>
                <th>
                  ISBN
                </th>
                <th>
                  Author
                </th>
                <th>
                  Edition
                </th>
                <th>
                  Quantity
                </th>
                <th>
                    Department
                </th>
                @if (Auth::user()->role->name == "ROLE_LIBRARY" || Auth::user()->role->name == "ROLE_SUPER_LIBRARY")
                      <th>
                        Stock Price
                      </th>
                      <th>
                        Sell Price
                      </th>
                @endif
                @if (Auth::user()->role->name == "ROLE_DEPARTMENT" || Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT")
                      <th>
                        Stock Price
                      </th>
                      <th>
                        Sell Price
                      </th>
                @endif
                @if (Auth::user()->role->name == "ROLE_STUDENT")
                      <th>
                        Price
                      </th>
                @endif
                <th>
                  Action
                </th>
              </thead>
              <tbody>
                @foreach ($books as $book)
                  <tr>
                    <td>
                      {{$book->title}}
                    </td>
                    <td>
                      {{$book->isbn_number}}
                    </td>
                    <td>
                      {{$book->author}}
                    </td>
                    <td>
                      {{$book->edition}}th
                    </td>
                    <td>
                      {{$book->count}}
                    </td>
                    <td>
                        {{$book->department->name}}
                    </td>
                    @if (Auth::user()->role->name == "ROLE_LIBRARY" || Auth::user()->role->name == "ROLE_SUPER_LIBRARY")
                        <td>
                            R{{$book->stock_price}}
                        </td>
                        <td>
                            R{{$book->sell_price}}
                        </td>
                    @endif
                    @if (Auth::user()->role->name == "ROLE_DEPARTMENT" || Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT")
                        <td>
                            R{{$book->stock_price}}
                        </td>
                        <td>
                            R{{$book->sell_price}}
                        </td>
                    @endif
                    @if (Auth::user()->role->name == "ROLE_STUDENT")
                        <td>
                            R{{$book->sell_price}}
                        </td>
                    @endif
                    <td>
                        @if (Auth::user()->role->name == "ROLE_LIBRARY" || Auth::user()->role->name == "ROLE_SUPER_LIBRARY")
                            <a class="btn btn-primary btn-sm p-1"  href="{{route('orderPage', $book->id)}}">
                                Order
                            </a> 
                            <a class="btn btn-success btn-sm p-1"  href="{{route('editBook', $book->id)}}">
                                <i class="material-icons">edit</i>
                            </a> 
                            <a class="btn btn-danger btn-sm p-1"  href="{{route('deleteBook', $book->id)}}" onclick="return confirm('Are you sure you want to delete this book: {{$book->title}}?');">
                                <i class="material-icons">delete</i>
                            </a> 
                        @endif

                        @if (Auth::user()->role->name == "ROLE_DEPARTMENT" || Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT")
                            <a class="btn btn-success btn-sm p-1"  href="{{route('getSelectedBookRequestPage', $book->id)}}" onclick="return confirm('Are you sure you want to request this book: {{$book->title}}?');">
                                Request
                            </a> 
                        @endif

                        @if (Auth::user()->role->name == "ROLE_STUDENT")
                            <a class="btn btn-success btn-sm p-1"  href="{{route("saveBookReservation", $book->id)}}" onclick="return confirm('Are you sure you want to reserve this book: {{$book->title}}?');">
                                Reserve
                            </a> 
                        @endif
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
@endsection