@extends('layouts.master')
@section('title', 'Welcome TransShipment User')


@section('content')

    {{-- <div class="col-md-12 text-center">


        <h2></h2>
        <p class="welcome-message text-center">You are Welcome, <b>{{Auth::user()->name}}</b>  </p>
       <img src="img/Manifest_Posting.jpg" alt="" width="500" height="500">

    </div> --}}
    <section class="content-header">
        <p class="welcome-message text-center text-capitalize">You are Welcome, <b>{{Auth::user()->name}}</b></p>
        <h1>
            Dashboard
            <small><b><i>{{Auth::user()->role->name }}</i></b> Control panel</small>
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
                        <h3>{{ $countTranshipmentUser }} <sup style="font-size: 20px">Users</sup></h3>
                        <p>Total For TranShipment</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>
                    <a href="{{url('TruckEntryForm')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $countTodaysTruckEntry[0]->todaysTruckentryByTranshipment}}
                            <sup style="font-size: 20px"> Trucks</sup>
                        </h3>
                        <p>Total Foreign Truck Entry For Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-bus"></i>
                    </div>
                    <a href="{{url('TruckEntryForm')}}" class="small-box-footer">More info 
                    <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ $countTodaysTruckExit[0]->todaysTruckexitByTranshipment }}</h3>
                        <p>Total Local Truck Exit For Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-bus"></i>
                    </div>
                    <a href="{{url('LocalTruckGateOutTranshipment')}}" class="small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ $countTodaysAssessmentDone[0]->todaysAssessmentTranshipment }}<sup style="font-size: 20px"> Assessments</sup></h3>

                        <p>Total Assessments For Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-list-outline"></i>
                    </div>
                    <a href="{{url('AssessmentSheet')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
    </section>


@endsection