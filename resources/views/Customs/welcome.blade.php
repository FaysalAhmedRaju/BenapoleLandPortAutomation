@extends('layouts.master')
@section('title', 'Welcome Customs User')


@section('content')
    <section class="content-header">
        <p class="welcome-message text-center text-capitalize">You are Welcome, <b>{{Auth::user()->name}}</b></p>
        <h1>
            Dashboard
            <small><b><i>Customs</i></b> Control panel</small>
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
                        <h3>{{--{{$todaysTruckTotal[0]->total_truck_entry}}--}}00<sup style="font-size: 20px"> Truck</sup></h3>

                        <p>Total In For Today</p>
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
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{--{{$todaysTruckByUser[0]->total_truck_entry}}--}}00


                            <sup style="font-size: 20px"> Manifest</sup>
                        </h3>
                        <p>Created By You</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-car"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{--{{$todaysManifestByUser[0]->total_Bus_entry}}--}}00<sup style="font-size: 20px"> Truck</sup></h3>

                        <p>Total In For Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-bus"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{--{{$Trucks_of_manifest[0]->total_Bus_entry_user}}--}}00<sup style="font-size: 20px"> Manifest</sup></h3>

                        <p>Created By You</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-bus"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
    </section>


@endsection