@extends('layouts.master')
@section('title', 'Welcome Gate Pass')


@section('content')

    {{--  <div class="col-md-12 text-center">

          --}}{{--<img src="img/blps1.png" alt="" width="700" height="400">--}}{{--
      </div>--}}
    <section class="content-header">
        <p class="welcome-message text-center">You are Welcome, <b>{{Auth::user()->name}}</b></p>
        <h1>
            Dashboard <small><b><i>Gate Pass</i></b> Control panel</small>
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
                        <h3>{{$todaysAssessmentDetails[0]->total_assessment}} <sup style="font-size: 20px"> Assessment</sup></h3>

                        <p>Total For Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-document"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{number_format($TotalAssessmentWithVat,0)}}<sup style="font-size: 20px"> Taka</sup>
                        </h3>
                        <p>Total Assessment Value For Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="" class="small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{$todaysAssessmentDetails[0]->total_assessment_done}}<sup style="font-size: 20px"> Assessment</sup></h3>

                        <p>Done For Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-document"></i>
                    </div>
                    <a href="" class="small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{$todaysAssessmentDetails[0]->total_assessment_created_by_you}}<sup style="font-size: 20px"> Assessment</sup></h3>

                        <p>Created By You For Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-document"></i>
                    </div>
                    <a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
    </section>

@endsection