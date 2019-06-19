@extends('layouts.master')

@section('title',  'Someting Went Wrong' )

@section('content')
    <div class="col-md-12">

        <div class=" alert alert-warning text-center">
            @if($errorMessage)
                <p style="font-size: 16px" class="error text-capitalize">
                    <i class="fa fa-warning"></i>
                    {{$errorMessage}}
                </p>
            @endif
        </div>

    </div>
@endsection
