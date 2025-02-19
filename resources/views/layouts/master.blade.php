<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Document Storage | Application</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>

    </ul>

    <!-- SEARCH FORM -->
    <div class="form-inline ml-3">
    <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" @keyup="searchit" v-model="search" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" @click="searchit">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </div>

    <div class="navbar-nav ml-auto">
    <ul class="nav navbar-nav">
      <li class="dropdown user user-menu">
      <a href="#" class="dropdown-toggle d-block" data-toggle="dropdown" aria-expanded="false">
      <img src="{{asset('img/profile/').'/'.Auth::user()->photo}}" class="img-circle mr-1" style="width: 2.1rem;height: auto;" alt="User Image">
      <span class="hidden-xs"> {{Auth::user()->name}} </span>
      </a>
      <div class="dropdown-menu" role="menu">
      <router-link to="/profile" class="dropdown-item" >
      <i class="nav-icon fas fa-user mr-2 float-left"></i>
      <p>
      Profile
      </p>
      </router-link>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="{{ route('logout') }}"
      onclick="event.preventDefault();
      document.getElementById('logout-form').submit();">
      <i class="nav-icon fa fa-power-off red mr-2 float-left"></i>
      <p>
      {{ __('Logout') }}
      </p>
      </a>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
      </form>
      </div>
      </li>
    </ul>
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{asset('img/doc.png')}}" alt=" Logo" class="brand-image elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Document Storage</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('img/profile/').'/'.Auth::user()->photo}}" class="img-circle elevation-2" style="width: 3.1rem;height: auto;" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
              {{Auth::user()->name}} 
              
              <br>
              {{Auth::user()->type}}
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

            <li class="nav-item">
            <router-link to="/dashboard" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                Dashboard
                </p>
            </router-link>
            </li>

            <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-file-alt "></i>
              <p>
                Documents
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview pl-2">
            @canany(['isAdmin', 'isUploader'])
            <li class="nav-item">
                <router-link to="/generate" class="nav-link">
                  <i class="fas fa-list-ol nav-icon"></i>
                  <p>Document Number</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/upload" class="nav-link">
                  <i class="fas fa-file-upload nav-icon"></i>
                  <p>Upload Document</p>
                </router-link>
              </li>
              @endcan
              <li class="nav-item">
                <router-link to="/documentlist" class="nav-link">
                  <i class="fas fa-copy nav-icon"></i>
                  <p>Document List</p>
                </router-link>
              </li>
            
            </ul>
          </li>

            @can('isAdmin')
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-cog "></i>
              <p>
                Master
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview pl-2">
              <li class="nav-item">
                <router-link to="/users" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Users</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/author" class="nav-link">
                  <i class="fas fa-user nav-icon"></i>
                  <p>Author</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/units" class="nav-link">
                  <i class="fas fa-building nav-icon"></i>
                  <p>Units</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/documenttype" class="nav-link">
                  <i class="fas fa-file-alt nav-icon"></i>
                  <p>Document Type</p>
                </router-link>
              </li>
            </ul>
          </li>

          <!-- <li class="nav-item">
                <router-link to="/developer" class="nav-link">
                    <i class="nav-icon fas fa-cogs"></i>
                    <p>
                        Developer
                    </p>
                </router-link>
         </li> -->
         @endcan
          <li class="nav-item">
                <router-link to="/profile" class="nav-link">
                    <i class="nav-icon fas fa-user"></i>
                    <p>
                        Profile
                    </p>
                </router-link>
         </li>

          <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                    <i class="nav-icon fa fa-power-off red"></i>
                    <p>
                        {{ __('Logout') }}
                    </p>
                 </a>

             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                 @csrf
             </form>
        </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height: 325px;">

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <router-view></router-view>

        <vue-progress-bar></vue-progress-bar>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      V.1.0-beta
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2018 Idm</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

@auth
<script>
    window.user = @json(auth()->user());
    window.baseUrl = {!! json_encode(url('/')) !!};
    //console.log(APP_URL);
</script>
@endauth

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
