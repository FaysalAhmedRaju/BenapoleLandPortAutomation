@inject('menus','App\Menu')

@php($validUser=request()->user())

<section class="sidebar">
    <!-- Sidebar user panel -->


    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">


        <li class="header">
            <a href="@if(getRoleDashboardRoute()) {{route(getRoleDashboardRoute())}} @endif"><i class="fa fa-dashboard fa-2x"></i>
                &nbsp;&nbsp;&nbsp;DASHBOARD
            </a>
        </li>

        @php($displayedMenus=displayableMenus($menus))


        @foreach($displayedMenus as $displayedMenu)

            @php($displayedChildMenus =displayableMenus($menus,$displayedMenu->id))
            @if(isset($displayedChildMenus)  && count($displayedChildMenus) > 0)
                {{--{{count($displayedChildMenus)}}--}}
                <li class="{{ $displayedMenu->id }}  treeview {!! Route::has($displayedMenu->route_name)?$displayedMenu->route_name:'#' !!}">
            @else
                {{--{{$displayedMenu->route_name}}--}}
                <li class="{{ $displayedMenu->id }} {!! Route::has($displayedMenu->route_name)?$displayedMenu->route_name:'#' !!}">
                    @endif
                    <a href="{!! Route::has($displayedMenu->route_name)?route($displayedMenu->route_name):'#' !!}">
                        <i class="@if(isset($displayedMenu->icon_name)  && !empty($displayedMenu->icon_name)){{ $displayedMenu->icon_name }}@else fa fa-users @endif"></i>
                        <span>{{ $displayedMenu->menu_name }}</span>
                        @if(isset($displayedChildMenus)  && count($displayedChildMenus) > 0)
                            <i class="fa fa-angle-left pull-right"></i>
                        @endif
                    </a>

                    @if(isset($displayedChildMenus) && count($displayedChildMenus) > 0)
                        <ul class="treeview-menu">
                            @foreach($displayedChildMenus as $displayedchildMenu)
                                @php($displayedSubChildMenus=displayableMenus($menus,$displayedchildMenu->id))
                                @if(isset($displayedSubChildMenus)  && count($displayedSubChildMenus) > 0)
                                    <li class="{{ $displayedchildMenu->id }}  treeview {!! Route::has($displayedchildMenu->route_name)?$displayedchildMenu->route_name:'#' !!}">
                                        <a href="{!! Route::has($displayedchildMenu->route_name)?route($displayedchildMenu->route_name):'#' !!}"><i
                                                    class="@if(isset($displayedchildMenu->icon_name)  && !empty($displayedchildMenu->icon_name)) {{ $displayedchildMenu->icon_name }} @else fa fa-users @endif"></i><span>{{ $displayedchildMenu->menu_name }}</span> @if(isset($displayedSubChildMenus)  && count($displayedSubChildMenus) > 0)
                                                <i class="fa fa-angle-left pull-right"></i> @endif </a>
                                        <ul class="treeview-menu">
                                            @foreach($displayedSubChildMenus as $displayedSubChildMenu)
                                                <li class="{{ $displayedSubChildMenu->id }}  {!! Route::has($displayedSubChildMenu->route_name)? $displayedSubChildMenu->route_name:'#' !!}">
                                                    <a href="{!! Route::has($displayedSubChildMenu->route_name)? route($displayedSubChildMenu->route_name):'#' !!}"><i
                                                                class="@if(isset($displayedSubChildMenu->icon_name)  && !empty($displayedSubChildMenu->icon_name)) {{ $displayedSubChildMenu->icon_name }} @else fa fa-users @endif"></i> {{ $displayedSubChildMenu->menu_name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li class="{{ $displayedchildMenu->id }}  {!! Route::has($displayedchildMenu->route_name)? $displayedchildMenu->route_name:'#' !!}">
                                        <a href="{!! Route::has($displayedchildMenu->route_name)? route($displayedchildMenu->route_name):'#' !!}"><i
                                                    class="@if(isset($displayedchildMenu->icon_name)  && !empty($displayedchildMenu->icon_name)){{ $displayedchildMenu->icon_name }}@else fa fa-users @endif"></i> {{ $displayedchildMenu->menu_name }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </li>

                @endforeach


    </ul>
</section>
<!-- /.sidebar -->