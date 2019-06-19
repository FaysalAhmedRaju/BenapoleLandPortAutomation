@extends('layouts.master')
@section('title', 'Welcome C&F User')


@section('content')

    {{--<div class="col-md-12 text-center">
        <h2></h2>
        <p class="welcome-message text-center">You are Welcome, <b>{{Auth::user()->name}}</b>  </p>
    </div>--}}


    <section class="content-header">
        <p class="welcome-message text-center text-capitalize">You are Welcome, <b>{{Auth::user()->name}}</b></p>
        <h1>
            Dashboard
            <small><b><i>C&F</i></b> Control panel</small>
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
                        <h3>0 {{-- {{$todaysTruckTotal[0]->total_truck_entry}} --}}<sup style="font-size: 20px"> Truck</sup></h3>

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
                        <h3>0{{-- {{$todaysManifestTotal[0]->total_manifest}} --}}


                            <sup style="font-size: 20px"> Manifest</sup>
                        </h3>
                        <p>Total In For Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-document"></i>
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
                        <h3>0{{-- {{$todaysManifestByUser[0]->total_manifest_by_user}} --}}<sup style="font-size: 20px"> Manifest</sup></h3>

                        <p>Created By You</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-document"></i>
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
                        <h3>0{{-- {{$todaysTruckByUser[0]->total_truck_by_user}} --}}<sup style="font-size: 20px"> Truck</sup></h3>

                        <p>Created By You</p>
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