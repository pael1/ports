<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark newbgcolorHeader">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" onClick="showDiv();">
                <i class="far fa-comments"></i>
                {{-- if 0 sya class default if not class danger --}}
                <span class="badge badge-danger navbar-badge">3</span>

            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notif">
                {{-- @foreach ($departments as $department)
                    <a href="#" class="dropdown-item">
                      <div class="media">
                          <div class="media-body">
                              <h3 class="dropdown-item-title">
                                {{ $department->name }}
                              </h3>
                              <p class="text-sm">{{ $department->address }}</p>
                              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> {{ $department->created_at }}</p>
                          </div>
                      </div>
                  </a>
                <div class="dropdown-divider"></div>
                @endforeach --}}
            </div>
        </li>
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false" v-pre>
                <i class="far fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">
                    {{ Auth::user()->name }}
                </a>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
