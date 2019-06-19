@extends('layouts.master')
@section('title', $view_title)



@section('content')

    {{--  <div class="col-md-12 text-center">

          --}}{{--<img src="img/blps1.png" alt="" width="700" height="400">--}}{{--
      </div>--}}
    <section class="content-header">


        <p class="welcome-message text-center text-capitalize">
            <img src="/img/maintenance_man.jpg" alt="" width="130">
            You are Welcome, <b>{{Auth::user()->name}}</b>
        </p>
        <h1>
            Dashboard
            <small><b><i>Maintence</i></b> Control panel</small>
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
                        <h3>{{$todaysTruckTotal[0]->total_truck_entry}}<sup style="font-size: 20px"> Truck</sup></h3>

                        <p>Total For Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-car"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{$todaysTotalChassisSelf[0]->totalChassisSelf}} <sup style="font-size: 20px"> Chassis</sup></h3>

                        <p>Total For Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-cart"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{$todaysManifestTotal[0]->total_manifest}}<sup style="font-size: 20px"> Manifest</sup>
                        </h3>
                        <p>Total Entry For Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{$todaysExitTruckTotal[0]->total_truck_exit}}<sup style="font-size: 20px"> Truck</sup></h3>

                        <p>Total Exit For Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-car"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
    </section>

@endsection