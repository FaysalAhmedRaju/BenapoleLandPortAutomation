@extends('layouts.master')

@section('title', 'Salary Not Found')

@section('content')
	<div class="col-md-12 text-center">
        <h4 class="error">
        {{$month_year}}'s Salary Sheet is not Generated.
        <br>
        Please Generate the salary and save them first.
        </h4>
	</div>
@endsection
