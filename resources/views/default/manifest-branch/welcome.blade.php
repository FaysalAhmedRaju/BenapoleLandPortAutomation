
@extends('layouts.master')
@section('title', 'Welcome Manifest Branch Panel')


@section('content')
    {{--<div class="col-md-12 text-center">--}}

    <section class="content-header">
        <p class="welcome-message text-center text-capitalize">You are Welcome, <b>{{Auth::user()->name}}</b></p>
        <h1>
            Dashboard
            <small><b><i>Manifest Branch</i></b> Control panel</small>
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
                        <h3>{{$totalManifestPosting[0]->total_posting_done}}<sup style="font-size: 20px"> Manifest</sup></h3>

                        <p>Total Manifest Posting</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-document"></i>
                    </div>
                    {{--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>--}}
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{$totalTruckReceive[0]->total_truck_receive}}


                            <sup style="font-size: 20px"> Truck</sup>
                        </h3>
                        <p>Total Truck Receive</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-car"></i>
                    </div>
                    {{--<a href="#" class="small-box-footer">More info <i--}}
                                {{--class="fa fa-arrow-circle-right"></i></a>--}}
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{$receiveNotDone[0]->posting_done_but_receive_not_done}}<sup style="font-size: 20px"> Manifest</sup></h3>

                        <p>Posting Done But Not Received</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-document"></i>
                    </div>
                    {{--<a href="#" class="small-box-footer">More info <i--}}
                                {{--class="fa fa-arrow-circle-right"></i></a>--}}
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{$total_delivery_menifest[0]->total_delivery_menifest}}<sup style="font-size: 20px"> Manifest</sup></h3>

                        <p>Total Delivery Manifest</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-car"></i>
                    </div>
                    {{--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>--}}
                </div>
            </div>
            <!-- ./col -->
        </div>
    </section>
    {{--</div>--}}
@endsection