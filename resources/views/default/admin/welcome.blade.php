@extends('layouts.master')
@section('title', 'Welcome Admin')


@section('content')

    {{--  <div class="col-md-12 text-center">

          --}}{{--<img src="img/blps1.png" alt="" width="700" height="400">--}}{{--
      </div>--}}
    <section class="content-header">
        <p class="welcome-message text-center">You are Welcome, <b>{{Auth::user()->name}}</b></p>
        <h1>
            Dashboard
            <small><b><i>{{Auth::user()->role->name}}</i></b> Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{ $countUsers }} <sup style="font-size: 20px"> Users</sup></h3>
                        <p>Total Users</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{url('userEntryForm')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $countOnlineUser }}<sup style="font-size: 20px"> Users Online</sup>
                        </h3>
                        <p>Total Online Users</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>
                    <a href="{{url('onlineUsers')}}" class="small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{--{{ $countOrganization }}--}}<sup style="font-size: 20px"> Organizations</sup></h3>
                        <p>Total Organizations</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-flag"></i>
                    </div>
                    <a href="{{url('organizationEntryForm')}}" class="small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ $countImporters }}<sup style="font-size: 20px"> Importers</sup></h3>
                        <p>Total Importers</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-cart"></i>
                    </div>
                    <a href="{{url('importerList')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
    </section>

@endsection