@extends('layouts.master')
@section('title', 'Welcome Super Admin User')


@section('content')

  {{--  <div class="col-md-12 text-center">

        --}}{{--<img src="img/blps1.png" alt="" width="700" height="400">--}}{{--
    </div>--}}
    <section class="content-header">
        <p class="welcome-message text-center">You are Welcome, <b>{{Auth::user()->name}}</b></p>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

@endsection