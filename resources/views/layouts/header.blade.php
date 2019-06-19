<!-- Logo -->
<a href="#" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>{{Auth::user()->role->name }} </b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>{{ Auth::user()->role->name }} </b>Panel</span>
</a>

<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->


            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                   {{-- <img src="img/user2-160x160.jpg" class="user-image" alt="User Image">--}}
                        <span class="text-capitalize">
                            {{Auth::user()->name}} ({{Session::get('PORT_NAME')}})
                        </span>
                </a>
                <ul class="dropdown-menu">
                    <!-- User image -->
                    <li class="user-header">
                        <img src="{{Auth::user()->photo==null?'/img/noImg.jpg':'/'.Auth::user()->photo}}" class="img-circle" alt="User Image">
                        <p>
                            <span class="text-capitalize">{{Auth::user()->id}}</span>
                            <small>Emai: {{Auth::user()->email}}</small>
                            <small>Port:
                                <span class="text-capitalize">
                                    {{Session::get('PORT_ALIAS')}}-
                                    {{Session::get('PORT_ID')}}
                                </span>
                                </small>
                        </p>
                    </li>
                    <!-- Menu Body -->

                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="{{ route('user-change-password-from-view') }}" class="btn btn-default btn-xs">Change Password</a>
                        </div>

                        <div class="pull-right">
                            {{--<a href="{{ route('logout')}}" class="btn btn-default btn-flat">Sign out</a>--}}
                            <a class="btn btn-default btn-xs" href="{{ route('user-port-session') }}">Change Port</a>

                            <a href="{{ route('logout') }}" class="btn btn-default btn-xs" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Sign out
                            </a>
                          </div>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>

                    </li>
                </ul>
            </li>

        </ul>
    </div>

</nav>