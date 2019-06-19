@extends('layouts.master')
@section('title', 'Welcome To WeightBridge Panel')


@section('content')

    {{--  <div class="col-md-12 text-center">

          --}}{{--<img src="img/blps1.png" alt="" width="700" height="400">--}}{{--
      </div>--}}
    <section class="content-header">
        <p class="welcome-message text-center text-capitalize">You are Welcome, <b>{{Auth::user()->name}}</b></p>
        <h1>
            Dashboard
            <small><b><i>WeightBridge</i></b> Control panel</small>
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
                        <h3>{{$todaysTruckInTotal[0]->total_truck_entry}} <sup style="font-size: 20px"> Truck</sup></h3>

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
                        <h3>{{$todaysTruckOutTotal[0]->total_truck_out}}

                            {{--{{ is_null($totalIncomeOFTheDay) ? '0': $totalIncomeOFTheDay->todays_income }}--}}
                            <sup style="font-size: 20px"> Truck</sup>
                        </h3>
                        <p>Total Out For Today</p>
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
                        <h3>{{$upcomingTruckTotal[0]->total_upcoming_truck}}<sup style="font-size: 20px"> Truck</sup></h3>

                        <p>Upcoming Total</p>
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
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3> <sup style="font-size: 20px">Truck In: </sup>{{$inOutTruckTotalByCurrentUser[0]->total_in_by_you}}
                            <sup style="font-size: 20px">Truck Out: </sup>{{$inOutTruckTotalByCurrentUser[0]->total_out_by_you}} </h3>

                        <p>By You Today</p>
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