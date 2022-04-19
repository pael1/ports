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
            @foreach($notifications as $value)
            <a class="nav-link" data-toggle="dropdown" id="{{ $value->id }}" href="#" onClick="showDiv();">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge pending">{{ $value->unread }}</span>
            </a>
            @endforeach

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notif" style="overflow:scroll; height:400px;">
            </div>
        </li>
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false" v-pre>
                <i class="far fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">
                    {{ Auth::user()->firstname . ', ' . Auth::user()->middlename . ', ' . Auth::user()->lastname }}
                    
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
