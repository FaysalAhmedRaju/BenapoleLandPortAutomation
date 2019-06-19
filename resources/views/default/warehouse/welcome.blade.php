@extends('layouts.master')
@section('title', 'Welcome To WareHouse Panel')


@section('content')

    {{--  <div class="col-md-12 text-center">

          --}}{{--<img src="img/blps1.png" alt="" width="700" height="400">--}}{{--
      </div>--}}
    <section class="content-header">
        <p class="welcome-message text-center text-capitalize">You are Welcome, <b>{{Auth::user()->name}}</b></p>
        <h1>
            Dashboard
            <small><b><i>WareHouse</i></b> Control panel</small>
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
                        <h3>{{$todaysWareHouse[0]->total_truck_receive}} <sup style="font-size: 20px"> Truck</sup></h3>

                        <p>Total Receive For Today</p>
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
                        <h3>{{$todaysWareHouse[0]->total_truck_delivery}}<sup style="font-size: 20px"> Truck</sup></h3>
                        <p>Total Delivery For Today</p>
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
                        <h3>{{$todaysWareHouse[0]->total_manifest_receive}}<sup style="font-size: 20px"> Manifest</sup></h3>

                        <p>Total Receive For Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-document"></i>
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
                        <h3> {{$todaysWareHouse[0]->total_manifest_delivery}}<sup style="font-size: 20px">Manifest</sup></h3>
                        <p>Total Delivery For Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-document"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
    </section>

@endsection