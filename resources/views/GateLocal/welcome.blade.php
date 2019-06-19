
@extends('layouts.master')
@section('title', 'Welcome GateOut Panel')

@section('content')
    {{--<div class="col-md-12 text-center">--}}
    <section class="content-header">
        <p class="welcome-message text-center text-capitalize">You are Welcome, <b>{{Auth::user()->name}}</b></p>
        <h1>
            Dashboard
            <small><b><i>GateOut</i></b> Control panel</small>
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
                        <h3>{{$todaysEntryTruckTotal[0]->today_entry}}<sup style="font-size: 20px"> Truck</sup></h3>

                        <p>Total Entry Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-bus"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{$todaysExitTruckTotal[0]->today_exit}}


                            <sup style="font-size: 20px"> Truck</sup>
                        </h3>
                        <p>Total Exit Today</p>
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
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{$todaysTruckEntryByUser[0]->today_entry_by_user}}<sup style="font-size: 20px"> Truck In</sup></h3>

                        <p>By You</p>
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
                        <h3>{{$todaysTruckExitByUser[0]->user_exit}}<sup style="font-size: 20px"> Truck Exit</sup></h3>

                        <p>By You</p>
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
    {{--</div>--}}
@endsection