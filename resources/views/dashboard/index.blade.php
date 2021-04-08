@extends('layouts.admin')


@section('content')
<div class="row">

  @if (Auth::user()->role->name == "ROLE_SUPER_LIBRARY")
    <div class="col-lg-3 col-md-6 col-sm-6">
      <div class="card card-stats">
        <div class="card-header card-header-info card-header-icon">
          <div class="card-icon">
            <i class="material-icons">group</i>
          </div>
          <p class="card-category">Librarians</p>
          <h3 class="card-title"> {{$librarians->count()}}
          </h3>
        </div>
        <div class="card-footer">
          <div class="stats">
          <a href="{{route("librarians")}}">View Details</a>
          </div>
        </div>
      </div>
    </div>
  @endif

  @if (Auth::user()->role->name == "ROLE_STUDENT")
    <div class="col-lg-3 col-md-6 col-sm-6">
      <div class="card card-stats">
        <div class="card-header card-header-primary card-header-icon">
          <div class="card-icon">
            <i class="material-icons">library_books</i>
          </div>
          <p class="card-category">Reservations</p>
          <h3 class="card-title"> {{$bookReservations->count()}}
          </h3>
        </div>
        <div class="card-footer">
          <div class="stats">
          <a href="{{route("book-reservations")}}">View Details</a>
          </div>
        </div>
      </div>
    </div>
  @endif

  @if (Auth::user()->role->name == "ROLE_LIBRARY")
    <div class="col-lg-3 col-md-6 col-sm-6">
      <div class="card card-stats">
        <div class="card-header card-header-info card-header-icon">
          <div class="card-icon">
            <i class="material-icons">book</i>
          </div>
          <p class="card-category">Books</p>
          <h3 class="card-title"> {{$books->count()}}
          </h3>
        </div>
        <div class="card-footer">
          <div class="stats">
          <a href="{{route("books")}}">View Details</a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-primary card-header-icon">
            <div class="card-icon">
              <i class="material-icons">library_books</i>
            </div>
            <p class="card-category">Reservations</p>
            <h3 class="card-title"> {{$bookReservations->count()}}
            </h3>
          </div>
          <div class="card-footer">
            <div class="stats">
            <a href="{{route("book-reservations")}}">View Details</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-primary card-header-icon">
              <div class="card-icon">
                <i class="material-icons">library_books</i>
              </div>
              <p class="card-category">Book requests</p>
              <h3 class="card-title"> {{$bookRequests->count()}}
              </h3>
            </div>
            <div class="card-footer">
              <div class="stats">
              <a href="{{route("book-requests")}}">View Details</a>
              </div>
            </div>
          </div>
        </div>

      <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
              <div class="card-icon">
                <i class="material-icons">notifications_active</i>
              </div>
              <p class="card-category">Notifications</p>
              <h3 class="card-title"> {{$books->count()}}
              </h3>
            </div>
            <div class="card-footer">
              <div class="stats">
              <a href="{{route("notifications")}}">View Details</a>
              </div>
            </div>
          </div>
        </div>
  @endif


  @if (Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT" || Auth::user()->role->name == "ROLE_DEPARTMENT")
  <div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card card-stats">
      <div class="card-header card-header-info card-header-icon">
        <div class="card-icon">
          <i class="material-icons">library_books</i>
        </div>
        <p class="card-category">Books</p>
        <h3 class="card-title"> {{$books->count()}}
        </h3>
      </div>
      <div class="card-footer">
        <div class="stats">
        <a href="{{route("books")}}">View Details</a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-sm-6">
      <div class="card card-stats">
        <div class="card-header card-header-primary card-header-icon">
          <div class="card-icon">
            <i class="material-icons">group</i>
          </div>
          <p class="card-category">Students</p>
          <h3 class="card-title"> {{$students->count()}}
          </h3>
        </div>
        <div class="card-footer">
          <div class="stats">
          <a href="{{route("students")}}">View Details</a>
          </div>
        </div>
      </div>
    </div>
    @if (Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT")
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-primary card-header-icon">
            <div class="card-icon">
              <i class="material-icons">group</i>
            </div>
            <p class="card-category">Staff</p>
            <h3 class="card-title"> {{$staff->count()}}
            </h3>
          </div>
          <div class="card-footer">
            <div class="stats">
            <a href="{{route("staff")}}">View Details</a>
            </div>
          </div>
        </div>
      </div>       
    @endif

    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">
              <i class="material-icons">notifications_active</i>
            </div>
            <p class="card-category">Notifications</p>
            <h3 class="card-title"> {{$books->count()}}
            </h3>
          </div>
          <div class="card-footer">
            <div class="stats">
            <a href="{{route("notifications")}}">View Details</a>
            </div>
          </div>
        </div>
      </div>
@endif

  </div>
@endsection