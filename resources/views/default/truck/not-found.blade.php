@extends('layouts.master')
@section('title', 'Data Not Found')


@section('content')

    <div class="col-md-12 text-center">
        <div class="alert alert-warning">



        @if(isset($requestedDate))
                <h4>
                    There is no data found for the
                    date: {{isset($requestedDate) ? date('d-m-Y',strtotime($requestedDate)):''}}
                </h4>
                <a style="text-decoration: none" class="btn btn-danger btn-group-sm" href="{{ URL::previous() }}">
                   <i class="fa fa-backward"></i> Go Back
                </a>

        @elseif(isset($noIncompleteManifest))
               <h4>{{$noIncompleteManifest}}</h4>

            @else
            <h4>Something Went Wrong in Server!</h4>
            <a href="{{ URL::previous() }}">Back To Home</a>



        @endif




            </div>
    </div>
@endsection