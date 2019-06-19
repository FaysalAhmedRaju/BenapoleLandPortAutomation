@extends('layouts.master')
@section('title', 'Welcome Accounts User')


@section('content')

  {{--  <div class="col-md-12 text-center">

        --}}{{--<img src="img/blps1.png" alt="" width="700" height="400">--}}{{--
    </div>--}}
    <section class="content-header">
        <p class="welcome-message text-center">You are Welcome, <b>{{Auth::user()->name}}</b></p>
        <h1>
            Dashboard
            <small>Control panel</small>
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
                        <h3>{{number_format($totalFdrBalance[0]->total_fdr_balance,0)}} <sup style="font-size: 20px"> Taka</sup></h3>

                        <p>Total FDR Balance</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{number_format($totalIncomeOFTheDay[0]->todays_income,0)}}

                            {{--{{ is_null($totalIncomeOFTheDay) ? '0': $totalIncomeOFTheDay->todays_income }}--}}
                            <sup style="font-size: 20px"> Taka</sup>
                        </h3>
                        <p>Income Of The Day</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="/accountsReport" class="small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{number_format($totalExpenseOFTheDay[0]->todays_expense,0)}}</h3>

                        <p>Expense Of The Day</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-cart"></i>
                    </div>
                    <a href="/ExpenditureEntry" class="small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{number_format($totalSalaryPaidOFTheMonth[0]->total_pay,0)}}</h3>

                        <p>Salary Paid This Month</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="/SalaryReport" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
    </section>

@endsection