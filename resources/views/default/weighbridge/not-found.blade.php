@extends('layouts.master')
@section('title', 'Data Not Found')


@section('content')

    <div class="col-md-12 text-center">
            <h4>There is no data found for the date : {{ $requestedDate }}</h4>

    </div>


@endsection