@extends('layouts.master')
@section('title', 'Importer Wise Report')
@section('content')
        <div class="col-md-12">
            <div class="col-md-5 col-md-offset-3" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 0 5px 10px;">
                <form action="{{ route('c&f-reports-get-importer-wise-report') }}" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <div class="col-md-12">
                        <table>
                        <br>
                            <tr>
                                <th>Importer No:</th>
                                <td>
                                    <input class="form-control" type="number" name="importerNo" id="importerNo">
                                </td>
                                 <td style="padding-left: 10px;">
                                    <button type="submit" class="btn btn-primary center-block">Show</button>
                                </td>
                            </tr>
                        </table>
                        <br>
                    </div>
                </form>
            </div>
        </div>
@endsection