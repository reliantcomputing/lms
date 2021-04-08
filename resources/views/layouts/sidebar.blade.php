<div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item ">
        <a class="nav-link" href="{{route("dashboard")}}">
          <i class="material-icons">dashboard</i>
          <p>Dashboard</p>
        </a>
      </li>
      @if (Auth::user()->role->name == "ROLE_SUPER_LIBRARY")
        <li class="nav-item ">
          <a class="nav-link" href="{{route("librarians")}}">
            <i class="material-icons">group</i>
            <p>Librarians</p>
          </a>
        </li>
      @endif

      @if (Auth::user()->role->name != "ROLE_SUPER_LIBRARY")
          <li class="nav-item ">
            <a class="nav-link" href="{{route("notifications")}}">
              <i class="material-icons">notifications_active</i>
              <p>Notifications
                  @if (Auth::user()->notifications()->count() != 0)
                    <span class="badge badge-danger">{{Auth::user()->notifications()->count()}}</span>
                  @endif
              </p>
            </a>
          </li>
      @endif

      @if (Auth::user()->role->name == "ROLE_LIBRARY")
        <li class="nav-item ">
          <a class="nav-link" href="{{route("books")}}">
            <i class="material-icons">book</i>
            <p>Books</p>
          </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="{{route("new-book-request")}}">
              <i class="material-icons">library_books</i>
              <p>New Book Requests/Status</p>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="{{route("book-requests")}}">
              <i class="material-icons">library_books</i>
              <p>Book Requests/Status</p>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="{{route("book-reservations")}}">
              <i class="material-icons">library_books</i>
              <p>Book Reservations</p>
            </a>
          </li>
      @endif

      @if (Auth::user()->role->name == "ROLE_DEPARTMENT" || Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT")
        <li class="nav-item ">
            <a class="nav-link" href="{{route("books")}}">
              <i class="material-icons">library_books</i>
              <p>Books</p>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="{{route("new-book-request")}}">
              <i class="material-icons">library_books</i>
              <p>New Book Requests/Status</p>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="{{route("book-requests")}}">
              <i class="material-icons">library_books</i>
              <p>Book Requests/Status</p>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="{{route("students")}}">
              <i class="material-icons">group</i>
              <p>Students</p>
            </a>
        </li>
        @if (Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT")
          <li class="nav-item ">
            <a class="nav-link" href="{{route("staff")}}">
              <i class="material-icons">group</i>
              <p>Staff</p>
            </a>
          </li>
        @endif
      @endif

      @if (Auth::user()->role->name == "ROLE_STUDENT")
        <li class="nav-item ">
          <a class="nav-link" href="{{route("books")}}">
            <i class="material-icons">book</i>
            <p>Books</p>
          </a>
        </li>
        <li class="nav-item ">
          <a class="nav-link" href="{{route("book-reservations")}}">
            <i class="material-icons">book</i>
            <p>Book Reservations</p>
          </a>
        </li>      
      @endif
        <li class="nav-item ">
          <a class="nav-link" href="{{ route('change-password') }}">
            <i class="material-icons">lock</i>
            <p>Change Password</p>
          </a>
        </li>
        <li class="nav-item ">
          <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="material-icons">power_off</i>
            <p>Log out</p>
          </a>
        </li>
    </ul>
  </div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
  @csrf
</form>