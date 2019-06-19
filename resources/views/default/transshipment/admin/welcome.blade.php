@extends('layouts.master')
@section('title', 'Welcome Assessment Admin User')
@section('content')
    <div class="col-md-12 text-center">
        <h2></h2>
        <p class="welcome-message text-center">You are Welcome, <b>{{Auth::user()->name}}</b>  </p>
        {{--<img src="img/blps1.png" alt="" width="700" height="400">--}}
    </div>
@endsection