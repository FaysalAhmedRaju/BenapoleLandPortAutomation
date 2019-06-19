@extends('layouts.master')
@section('title', 'Error')


@section('content')

    <div class="col-md-12 text-center">
        <h3 class="error">Opps! Something went wrong!</h3>
        <h5 class="error">Please try again later!</h5>
        {{-- <p class="ok text-center">You are Welcome, <b>{{Auth::user()->name}}</b>  </p>
         <img src="img/bpls1.png" alt="" width="700" height="400">--}}
    </div>


@endsection