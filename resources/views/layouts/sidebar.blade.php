<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 newbgcolorSidebar">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
      <img src="dist/img/logo.png" class="brand-image img-circle elevation-3">
      <span class="brand-text font-weight-light"><b>PORTS</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-5">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ Request::is('users') ? 'active' : Request::is('users/*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Users
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('complaints.index') }}" class="nav-link {{ Request::is('complaints') ? 'active' : Request::is('complaints/*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>
                Complaints
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('roles.index') }}" class="nav-link {{ Request::is('roles') ? 'active' : Request::is('roles/*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>
                Roles
              </p>
            </a>
          </li>
          {{-- <li class="nav-item">
            <a href="{{ route('products.index') }}" class="nav-link {{ Request::is('products') ? 'active' : Request::is('products/*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Products
              </p>
            </a>
          </li> --}}
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>