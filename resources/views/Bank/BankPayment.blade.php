@extends('layouts.master')
@section('title', 'Welcome Bank User')
@section('script')

    {!!Html :: script('js/customizedAngular/bank.js')!!}


@endsection

@section('content')

    <div class="col-md-12 text-center" ng-app="bankApp" ng-controller="bankCtrl" ng-cloak>


        <div class="col-md-5 col-md-offset-3" style="">
            <form class="form-inline" name="form" ng-submit="doSearch(searchText)" novalidate>

                <div class="form-group text-right">
                    <label for="searchText"> Search By Manifest:</label>
                    <input type="text" ng-model="searchText" name="jj" ng-pattern='/^([0-9]{1,10}|[0-9P]{2,6})[\/]{1}([0-9]{1,3}|[(A|a)]{1}|[(A-Z-A-Z)]{3})[\/]{1}[0-9]{4}$/' required="required" class="form-control input-sm"  placeholder="Type Manifest No." ng-keydown="keyBoard($event)" ng-model-options="{allowInvalid: true}">
                    <br>

                    <span class="error" ng-show='form.searchText.$error.pattern'>
                                Input like: 256/12 Or 256/A
                    </span>
                </div>
                <p class="error">@{{ notFoundMsg }}</p>
            </form>
            <br>

        </div>

        <div class="col-md-4" style="">

           {{-- <button type="button" class="btn btn-primary" ng-click="generatePDF()" ng-disabled="!searchText">
                <span class="fa fa-search"></span> Get Assessment
            </button>--}}

            <a href="/assessment/get-assessment-report/@{{ searchText }}"  target="_blank" class="btn btn-primary" >Assessment PDF</a>


           {{-- <a href="getAssessmentInvoicePDFBank/@{{searchText}}"  target="_blank" class="btn btn-primary" >Challan PDF</a>
--}}
            <form action="{{ route('assessment-get-assessment-invoice-report') }}" target="_blank" method="post">
                {{ csrf_field() }}


                            <input class="form-control" ng-show="jjj" ng-model="searchText" type="text" name="manifest" >

                            <button type="submit" class="btn btn-primary center-block">Challan PDF</button>

            </form>

            {{--<a href="GetAssessmentPdfReport/@{{ searchText }}"  target="_blank" class="btn btn-primary" >Get Assessment</a>--}}

        </div>

{{--//12--}}

        <div class="col-md-12">

                 <span ng-if="dataLoading" style="color:green; text-align:center; font-size:12px">
                        <img src="img/dataLoader.gif" width="250" height="15"/>
                        <br/> Loading...!
                 </span>

        </div>

        <div id="aa">

{{--Show Assesment Details START--}}

<div ng-show="AssessmentDiv">

    {{--//12--}}
    <div class="col-md-12 ForPdf"  ng-show="ForPdf">

        <img src="img/blpa.jpg" style="float: left">
        <p class="center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
            <span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
            <span style="font-size: 19px;">Assessment & Bank Payment Report</span> <br>
        </p>
        <h5 style="text-align: right;padding-right: 35px;"> Date: <?php $timezone  = 6; //(GMT -6:00) EST (Dhaka)
            echo gmdate("F j, Y, g:i a", time() + 3600*($timezone+date("I")));
            ?>
        </h5>
    </div>


{{--

    <div class="col-md-12" style="padding: 0">

        <table  border="0" class="table text-center-td-th" id="assessment" style="box-shadow: 1px 0px 5px -1px #969696">


            <tr>
                <td class="2">
                    <b> Manifest/Export Application No & Data:</b>
                </td>

                <td class="2">
                    <u> @{{ ManifestNo }}</u> <br> @{{Mani_date }}
                </td>



                <td class="2">
                    <b>Consignee:</b>
                </td>

                <td class="2">
                    @{{ Consignee }}
                </td>
            </tr>

            <tr>
                <td class="2">
                    <b>Bill Of Entry No & Date:</b>
                </td>

                <td class="2"> <span ng-show="m.be_date">C-</span>
                    <u> @{{ Bill_No }}</u> <br> @{{ Bill_date}}
                </td>



                <td class="2">
                    <b> Consignor:</b>
                </td>

                <td class="2">
                    @{{ Consignor }}
                </td>
            </tr>



            <tr>
                <td rowspan="2">
                    <b>Custom's Release Order No & Date:</b>
                </td>

                <td rowspan="2"> <span ng-show="m.be_date">C-</span>
                    <u> @{{ Custome_release_No}}</u> <br> @{{ Custome_release_Date}}
                </td>



                <td>
                    <b> C & F Agent:</b>
                </td>

                <td>
                    @{{ CnF_Agent }}
                </td>
            </tr>

            <tr>

                <td><b>Shed / Yard No.</b> </td>
                <td>@{{ Shed_Yard }}</td>
            </tr>



        </table>


    </div>


    <div class="col-md-12" style=" text-align: left">


        <b><p style="padding: 3px ;box-shadow: 1px 0px 5px 2px #969696; width: 250px">PARTICULARS OF CHARGES DUE</p></b>
    </div>
    <div class="col-md-12" style="box-shadow: 1px 0px 5px -1px #969696">



        <div class="col-md-12">


            <div class="col-md-5">


                <h5 class="text-left"><b> 1. WareHouse Rent:</b></h5>

                <table>
                    <tr>
                        <th colspan="2"  style="text-align: left">
                            (i).  Free Period
                            <br>
                            (From/To)

                        </th>
                        <td colspan="6">

                            @{{ receive_date  }} - @{{ freeday}}

                        </td>


                    </tr>
                    <tr>
                        <th></th>
                        <td></td>
                    </tr>

                    <tr ng-show="gotFirstSlab">
                        <th colspan="2" style="text-align: left" >(ii). Rent due Period <br>(From/To)</th>


                        <td colspan="6">

                            (@{{ fromdate  }} - @{{ deliver_date}} ) = @{{ WarehouseNetDay}}

                        </td>


                    </tr>

                </table>
            </div>


            <div class="col-md-6">


                <table class="table">
                    <thead>
                    <tr>
                        <th>Duration</th>
                        <th>M.Ton</th>
                        <th></th>
                        <th>Period</th>
                        <th></th>
                        <th>Rate</th>
                        <th></th>
                        <th>Amount</th>
                    </tr>
                    </thead>


                    <tbody>

                    <tr>--}}
{{--free Slab--}}{{--

                        <td> Free Slab </td>
                        <td>@{{ WarehouseReceiveWeight}} </td>
                        <td> X</td>
                        <td>3</td>
                        <td>X</td>
                        <td>0</td>
                        <td>=</td>
                        <td>0</td>

                    </tr>

                    <tr ng-show="gotFirstSlab">--}}
{{--1st Slab--}}{{--

                        <td> 1st Slab (1- @{{ firstSlabEndDay}})</td>
                        <td>@{{ WarehouseReceiveWeight}} </td>
                        <td> X</td>
                        <td>@{{ firstSlabEndDay}}</td>
                        <td>X</td>
                        <td>@{{ firstSlabCharge}}</td>
                        <td>=</td>
                        <td> @{{ totalFirstSlabCharge|number:2  }}</td>

                    </tr>

                    <tr ng-show="gotSecondSlab">--}}
{{--2nd Slab--}}{{--

                        <td>2nd Slab (22- @{{ secondSlabEndDay}}) </td>
                        <td>@{{ WarehouseReceiveWeight}} </td>
                        <td> X</td>
                        <td>@{{ secondSlabEndDay-21}}</td>
                        <td>X</td>
                        <td>@{{ secondSlabCharge}}</td>
                        <td>=</td>
                        <td> @{{ totalSecondSlabCharge|number:2  }}</td>

                    </tr>

                    <tr ng-show="gotThirdSlab">--}}
{{--3rd Slab--}}{{--

                        <td> 3rd Slab (51- @{{ thirdSlabEndDay}})</td>
                        <td>@{{ WarehouseReceiveWeight}} </td>
                        <td> X</td>
                        <td>@{{ thirdSlabEndDay-50}}</td>
                        <td>X</td>
                        <td>@{{ thirdSlabCharge}}</td>
                        <td>=</td>
                        <td> @{{ totalThirdSlabCharge|number:2  }}</td>

                    </tr>

                    </tbody>

                </table>
            </div>
        </div>



        <div class="col-md-12">

            <div class="col-md-5">
                <h5 class="text-left"><b> 2. Handling Charge:</b></h5>
            </div>


            <div class="col-md-6">


                <table class="table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>M.Ton</th>
                        <th></th>
                        <th>Rate</th>
                        <th></th>
                        <th>Amount</th>
                    </tr>
                    </thead>


                    <tbody>
                    <tr>
                        <th>OffLoading (@{{ OffloadMode }})</th>

                        <td>@{{ WeightForHandling}}</td>
                        <td>X</td>
                        <td>@{{ OffloadCharge }}</td>
                        <td>=</td>
                        <td>@{{ TotalForOffloadHandling| number : 2  }}</td>

                    </tr>

                    <tr>
                        <th>Loading (@{{ LoadingMode }})</th>

                        <td>@{{ WeightForHandling}}</td>
                        <td>X</td>
                        <td>@{{ LoadingCharge }}</td>
                        <td>=</td>
                        <td>@{{ TotalForLoadHandling| number : 2  }}</td>

                    </tr>
                    </tbody>

                </table>
            </div>

        </div>

        <div class="col-md-12">
            <div class="col-md-5">

                <h5 class="text-left"><b> 3. Other Dues:</b></h5>

                <table>
                    <tr>
                        <td><br><br>
                            (i) <b> Truck Entrance</b>
                        </td>
                    </tr>

                    <tr>
                        <td><br><br><br></td><br></td>
                    </tr>

                    <tr>
                        <td> (ii) <b>Haltage Charge</b></td>
                    </tr>
                    <tr>
                        <td><br><br><br></td>
                    </tr>
                    <tr>
                        <td> (iii) <b>Carpenter Charge</b></td>
                    </tr>

                    <tr>
                        <td><br><br><br></td>
                    </tr>

                    <tr>
                        <td> (iv) <b>Weighbridge Charge</b></td>
                    </tr>

                </table>

            </div>

            <div class="col-md-6">


                <table class="table">
                    <thead>
                    <tr>


                        <th>Name</th>
                        <th>Quantity</th>
                        <th></th>
                        <th>Rate</th>
                        <th></th>
                        <th>Amount</th>
                    </tr>
                    </thead>


                    <tbody>
                    <tr>

                        <td>1. Foreign Truck</td>
                        <td>@{{ m.Foreign_Truck }}</td>
                        <td>X</td>
                        <td>53.92</td>
                        <td>=</td>
                        <td>@{{ foreignTruckAmount }}</td>

                    </tr>
                    <tr>
                        <td>2. Local Truck</td>

                        <td>@{{ m.Local_Truck }}</td>
                        <td>X</td>
                        <td>53.92</td>
                        <td>=</td>
                        <td>@{{ localTruckAmount }}</td>

                    </tr>
                    </tbody>
                </table>


                <table class="table">
                    <thead>



                    <tr>
                        <th>Total Truck</th>
                        <th></th>
                        <th></th>
                        <th>Period</th>
                        <th></th>
                        <th>Rate</th>
                        <th></th>
                        <th>Amount</th>
                    </tr>
                    </thead>


                    <tbody>

                    <tr>

                        <td>@{{ TotalForeignTruck }}</td>
                        <td></td>
                        <td>X</td>
                        <td>@{{HoltageDay }}</td>
                        <td>X</td>
                        <td>71.86</td>
                        <td>=</td>
                        <td><span>@{{ (holtageAmount)| number : 2}}</span> </td>

                    </tr>
                    </tbody>



                    --}}
{{--Carpenter--}}{{--



                    <thead>

                    <tr> <th></th>
                        <th></th>
                        <th></th>
                        <th colspan="2">No. of Packages</th>



                        <th>Rate</th>
                        <th></th>
                        <th>Amount</th>
                    </tr>
                    </thead>


                    <tbody>

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>@{{ NoofPackages }}</td>


                        <td>X</td>
                        <td>7.22</td>
                        <td>=</td>
                        <td><span>@{{ (totalCarpenterCharge)| number : 2}}</span> </td>

                    </tr>

                    </tbody>


                    --}}
{{--Weighbridge charge--}}{{--


                    <thead>

                    <tr><th></th>
                        <th></th>
                        <th></th>
                        <th colspan="2">Foreign Truck</th>
                        <th>Rate</th>
                        <th></th>
                        <th>Amount</th>
                    </tr>
                    </thead>


                    <tbody>

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>@{{ TotalForeignTruck }}</td>
                        <td>X</td>
                        <td>89.84</td>
                        <td>=</td>
                        <td><span>@{{ (totalWeighbridgeCharge)| number : 2}}</span> </td>

                    </tr>

                    </tbody>
                </table>





            </div>
        </div>


        <div class="col-md-11">


            <table class="table">

                <tbody>
                <tr>
                    <td></td>

                    <td class="text-right"><b>Sub Total Taka:</b> <span>@{{ TotalAmount | ceil}}</span></td>

                </tr>
                <tr>
                    <td class="text-left"><b> 4. VAT:</b></td>
                    <td class="text-right"> <span>@{{( TotalAmount | ceil) *15/100}}</span></td>

                </tr>

                <tr>
                    <td></td>
                    <td class="text-right"><b>Grand Total Taka:</b>  <span>@{{( ((TotalAmount | ceil) *15/100) +( TotalAmount | ceil) ) |ceil}}</span></td>

                </tr>
                <tr>

                    <td colspan="2" class="text-left"><b>In Word (Taka):
                        </b> <span style="text-decoration: underline;" class="text-capitalize" id="totalInWord"> </span>
                    </td>

                </tr>

                </tbody>

            </table>

        </div>


</div>
 --}}
{{--ng-show="AssessmentDiv  END--}}{{--





--}}




        <div class="col-md-12" ng-show="BankPaymentDiv">



                        <table class="table">
                            <thead>
                            <tr>
                                <td class="text-center" colspan="8"><h5 class="ok"> Payment Details</h5></td>
                            </tr>
                            <tr>
                                <th>S/L</th>
                                <th>Payment</th>
                               <th>Chalan</th>
                                <th>Payment Mode</th>
                                <th>Payorder No</th>
                                <th>Receive By</th>
                                <th>Comment</th>
                                <th>Date</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr ng-repeat="item in PaymentData">
                                <td>@{{ $index+1 }}</td>
                                <td>@{{item.T_charge  }}</td>
                               <td>@{{ item.challan_no  }}</td>
                                <td>@{{ item.paymode |payMode }}</td>
                                <td>@{{ item.payment_details  }}</td>
                                <td>@{{ item.recived_by  }}</td>
                                <td>@{{ item.comment  }}</td>
                                <td>@{{ item.receive_dt  }}</td>


                            </tr>


                            <tr>
                                <td colspan="2"> <b>Total:</b> @{{ TotalPaymentPaid }}</td>
                                <td colspan="6"></td>
                            </tr>
                            </tbody>



                        </table>
            </div> {{--payemnt details div--}}

</div>
        </div>  {{--PDF div END--}}

        <div ng-show="BankPaymentDiv" class="col-md-7 col-md-offset-2" style="background-color: #f8f9f9; border-radius: 5px; padding: 5px 10px; text-align: center">

                <p class="ok" ng-show="PaymentComplete">Payment Completed!</p>

                <form  name="paymentForm" ng-hide="PaymentComplete">
                    <table style="width: 100%;">


                        <tr>


                            <th> <label for="payment" class="control-label"><b>Payment:</b></label> </th>
                            <td>

                                <input type="number" ng-model="Payment" name="payment" ng-change="CheckPaymentExceed(Payment)" class="form-control input-sm" id="payment" placeholder="Payment">

                                <span ng-if="ExceedAmount" style="color:red; text-align:center; font-size:11px">
                                        <p>Can't pay more than Assessment value!</p>
                                </span>


                            </td>

                            <th>Chalan No:</th>
                            <td>
                                <input type="text" ng-model="challan_no" class="form-control input-sm" placeholder="Type Chalan No.">
                            </td>

                        </tr>

                        <tr>
                            <td colspan="4">&nbsp;

                            </td>
                        </tr>


                        <tr>


                            <th>&nbsp;  <b>Payment Type:</b></th>
                            <td class="text-left">

                                <label class="radio-inline">
                                    <input type="radio" ng-init="PaymentMode=1"  ng-click="PayOrder(PaymentMode)" ng-model="PaymentMode" ng-checked="true"  value="1">Cash
                                </label>
                                <label class="radio-inline">
                                    <input type="radio"  ng-model="PaymentMode" ng-click="PayOrder(PaymentMode)"   value="0" >Pay-Order
                                </label>


                            </td>

                            <th>Comment:</th>
                            <td>
                                <input type="text" ng-model="Comment"  name="Comment" class="form-control input-sm"  placeholder="Type your Comment Here">
                            </td>




                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;

                            </td>
                        </tr>
                        <tr>

                            <th><span ng-show="PaymentModePayorder">Payorder No:</span> </th>

                            <td>
                                <span  ng-show="PaymentModePayorder" >
                                <input type="number" ng-model="PayorderNo" name="PayorderNo" class="form-control input-sm" id="PayorderNo" placeholder="Pay order Number">
                                </span>

                                <p id="PayorderNoReq" class="error text-left" ng-show="PayorderNoReq">
                                    Please input Valid Payorder No
                                </p>
                            </td>


                        </tr>


                        <tr>
                            <td colspan="4">&nbsp;

                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-center">
                                <input type="button" class="btn btn-primary" value="Payment"   ng-click="savePayment()" ng-disabled="!Payment || ExceedAmount ||!challan_no" >

                                <span ng-if="PaymentSucc" style="color:green; text-align:center; font-size:12px">
                                        <p>Successfully saved Payment!</p>
                                </span>



                            </td>
                        </tr>
                    </table>
                </form>

            </div> {{--payment form--}}





</div>


@endsection