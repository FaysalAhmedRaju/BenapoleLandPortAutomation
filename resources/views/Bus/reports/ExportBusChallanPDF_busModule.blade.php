
<!DOCTYPE html>
<html>
<head>
    <title>Challan Export Report</title>
    <style>

        /*.center{*/
        /*position: absolute;*/
        /*text-align: center;*/
        /*top: 0;*/
        /*left: 320px;*/
        /*}*/

        /*.amount-right{*/
        /*text-align: right!important;*/
        /*}*/

        /*.tble-warehouse tr td {*/
        /*border: 1px solid black;*/
        /*}*/

        html {
            margin: 4px 10px 0;
        }
        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }


        /*.center{*/

        /*text-align: center;*/

        /*}*/

        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 150px;
        }

        table.firstTable
        {
            border: 0px !important;
            /*background: #cfffff;*/
            /*font-color: #00003f;*/
        }

        table.firstTable tr td
        {
            border: 0px !important;

        }

        /*-----------Total amount table--------*/
        table.MonyTable
        {
            border: 0px !important;
            /*background: #cfffff;*/
            /*font-color: #00003f;*/

        }
        table.MonyTable tr td
        {
            border: 0px !important;
            /*background: #cfffff;*/
            /*font-color: #00003f;*/

        }


        /*-----Last Table----*/
        table.secondTable
        {
            border: 0px !important;
            /*background: #cfffff;*/
            /*font-color: #00003f;*/
        }
        table.secondTable tr td
        {
            border: 0px !important;
            /*background: #cfffff;*/
            /*font-color: #00003f;*/
        }
        html {
            margin: 0px 10px 0;
        }


        p.paraMessage
        {
            border: 1px solid black;
            border-radius: 15px;
            /*width: 400px;*/
            /*   text-align: center;*/

            /*border-left-width: 5px;*/
            /*border-left: 500px;*/
            /*border-right: 600px;*/

            /*    margin-left: 200px;
                margin-right: 200px;
               margin-top: 0px;
                margin-bottom: 0px;*/
            /*padding:0*/

            margin: 0px 60px;
            padding: 4px 6px;


        }


        .tablBackGroundImage{
            background-image: url(/img/Logo_BSBK.gif);
            /*background: url(/img/blpa.jpg );*/
            background-repeat:no-repeat;
            background-position:center center;
            background-size: 600px 300px ;
            border: 0px solid black;
            opacity: .2;
            /*padding-top: 40px;*/

        }

        tr.trOfFirsTable_all_space_off{

            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;

        }


        /*    tr{
                margin: 0;
                padding: 0;
                border: 0;
                font-size: 100%;
                font: inherit;
                vertical-align: baseline;
            }*/
    </style>

</head>
<body>


<table class="firstTable"  style="/*box-shadow: 0px 0px 1px 1px darkgrey;*/  width:100%;">
    <tr class="trOfFirsTable_all_space_off">
        <td style="width:12%;">
            <p><b>SI.No:</b> {{$challan_no}}</p>
        </td>

        <td style="width:12%;  text-align: right;">
            <img  src="../public/img/blpa.jpg" width="70px">
        </td>

        <td style="width:50%;  text-align: center;">
            <p {{--class="center"--}}><span style="font-size: 20px;"><b>BANGLADESH LANDPORT AUTHORITY</b></span>  <br>
                <span style="font-size: 19px;">Benapole Land Port, Jessore</span> <br>
            <p class="paraMessage" ><b>Port Charge of Challan at Tarminal</b></p>
        </td>
        <td style="width:26%; padding-left: 20px;  ">
            {{--<P style="font-size: 12px;">  &nbsp; </P>--}}
            {{--TD-BM-2.2--}}

            <div style="text-align: left; padding-left:40px; ">
                <p style="font-size: 10px; padding: 0;margin: 0">TD-BM-2.2</p>
                <ul style="font-size: 12px;">

                    <li>Bank Copy</li>
                    <li>Account Copy</li>
                    <li>Office Copy</li>
                </ul>
            </div>
            <p style="font-size: 12px; "><b>Challan NO: &nbsp;</b><span style=" border: solid 1px; padding:5px 15px">{{$export_challan_no}}</span> </p>
            <p style="font-size: 12px;"><b>Date:</b>&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;<span style=" border: solid 1px; padding:5px 15px" > {{date('d/m/Y',strtotime($create_datetime))}}</span> </p>
        </td>
    </tr>
</table>




<div class="tablBackGroundImage" style="padding: 0; ">
    <table  style="box-shadow: 0px 0px 1px 1px darkgrey; width: 100%;">
        <tr>
            <th style="width:5%">S/L</th>
            <th colspan="1" style="width: 17%">Truck Description</th>
            <th colspan="1" style="width: 28%">Rate of Charge</th>
            <th colspan="1" style="width: 8%">Number</th>
            <th colspan="1" style="width: 20%">Total Amount Money</th>
            <th colspan="1" style="width: 22%">Comment</th>
        </tr>


        <tr>
            <td >1</td>
            <td >
                Indian Truck
            </td>
            <td>
                i)Entry Fee: Taka.<br>
                ii)Haltage Charge: Taka.<br>
                <b style="border-bottom: dotted 2px; padding: 0 40px">&nbsp;&nbsp;</b>   Day
            </td>
            <td >
                <p  style=" width: 100%; border-bottom: dotted 2px; /*padding: 0 0px*/ ">&nbsp;&nbsp;</p>

                <p  style="width: 100%; border-bottom: dotted 2px; /*padding: 0 0px */">&nbsp;&nbsp;</p>
            </td>
            <td style="/*text-align: right;*/ ">
                {{--<br>---------}}
                {{----}}
                {{--<br>----------}}
                <p  style=" width: 100%; border-bottom: dotted 2px; /*padding: 0 0px*/ ">&nbsp;&nbsp;</p>
                <p  style="width: 100%; border-bottom: dotted 2px; /*padding: 0 0px */">&nbsp;&nbsp;</p>
            </td>
            <td style="">
                Cash book No<span style="border-bottom: dotted 2px; padding: 0 25px">&nbsp;&nbsp;</span>of
                <br><br>
                Receipt No<span style="border-bottom: dotted 2px; padding: 0 30px">&nbsp;&nbsp;</span>to
                <br>
                <span style="border-bottom: dotted 2px; padding: 0 35px">&nbsp;&nbsp;</span>
            </td>
        </tr>


        <tr>
            <td >2</td>
            <td >
                Bangladeshi Truck
            </td>
            <td style="">
                i)Entry Fee:&nbsp;
                {{$first_charge_4_55}} * {{$first_c_truck_no}} <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                {{$second_charge_21_59}} * {{$second_c_truck_no}} <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                {{$third_charge_53_92}}  * {{$third_c_truck_no}} <br> <br>

                ii)Haltage Charge: {{--<b>--}} {{$haltage_charge}} {{--</b>--}}Taka.
                <span style="border-bottom: dotted 2px; padding: 0 40px"> {{$total_holtage_day}}</span>   Day
            </td>
            <td style="text-align: center;">
                <p style="width: 100%; border-bottom: dotted 2px; /*padding: 0 5px;*/ text-align: center; ">{{--<b >--}}{{$Total_truck_no}}{{--</b>--}} <span style="font-size: 12px">Trucks</span></p>
            </td>
            <td style="text-align: right;vertical-align: top; ">

                <br>
                = {{number_format($t_first,2)}}<br>
                = {{number_format($t_second,2)}}<br>
                = {{number_format($t_third,2)}}<br>

                <p  style=" width: 100%; border-bottom: dotted 2px; /*padding: 0 0px*/ ">{{number_format($Total_entrance_fee_all_truck,2)}}</p>

                <p  style="width: 100%; border-bottom: dotted 2px; /*padding: 0 0px */"> {{number_format($total_haltage_charge_all_truck,2)}}</p>
            </td>
            <td >
            </td>
        </tr>



        <tr>
            <td >3</td>
            <td>
                Miscellaneous
            </td>
            <td style="text-align: center;">
                <p style=" width:100%; text-transform: capitalize; border-bottom: dotted 2px;/* padding: 0 0px*/">{{$miscellaneous_name}}-{{$miscellaneous_charge}}</p>
            </td>
            <td >
            </td>
            <td style="text-align: right;">
                &nbsp;<p style="width: 100%; border-bottom: dotted 2px; ">{{number_format($totalTakaWithoutVat,2)}}</p>
                {{--<br>--}}
                <p style="width: 100%; border-bottom: dotted 2px;">{{number_format($cellVat,2)}}</p>
            </td>
            <td >
            </td>
        </tr>

    </table>

    <table class="MonyTable"   style="box-shadow: 0px 0px 0px 0px darkgrey; width: 100%; ">
        <tr style="">
            <td style=" width: 27%;"></td>
            <td style=" width: 6%;  "> Total Taka:</td>
            <td style="text-align: right;  width: 12%; ">
                &nbsp; &nbsp;&nbsp; &nbsp;<span style="border: solid 1px; padding:5px 15px">= {{number_format($grandTotal,2)}}</span>
            </td>
            <td style=" width: 12%; "></td>
        </tr>
    </table>

    <table class="MonyTable"   style="box-shadow: 0px 0px 0px 0px darkgrey; width: 100%; ">
        <tr style="/*background-color: deepskyblue*/">
            <td style="width:15% ">Taka In word:</td>
            <td colspan="2" style="border-bottom: dotted 2px;width:55% "> <span style="text-transform: capitalize; border-bottom: dotted 1px;  text-decoration: none;  ">{{convert_number_to_words($grandTotal)." Taka only."}}</span></td>
        </tr>
    </table>

</div>

{{--------------------------------------done--------------------------------}}


<div style="width: 100%">
    <table class="secondTable" style="width:100%" >
        <tr>
            <td style="width: 25%">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 12px;border-top: dotted 1px; padding-top: 100%;">Assistant Incharge{{--</span><br><span>--}} Truck Tarminal</span>
            </td>
            <td style="width: 25%">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 12px;border-top: dotted 1px; padding-top: 100%;">Traffic Controller{{--</span><br><span>--}} Truck Tarminal</span>
            </td>
            <td style="width: 25%">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 12px;border-top: dotted 1px; padding-top: 100%;">Assistant Director (Traffic)</span>
            </td>
            <td style="width: 25%; /*background-color: red*/">
               <span style="text-align: center; /*font-family:'Brush Script MT';*/ font-style: italic ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   &nbsp;&nbsp;&nbsp;&nbsp;{{$user_name}}</span> <br>
                {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}<span style="border-top: dotted 1px; padding-top: 100%; font-size: 12px">Signature Of Money-taking Bank Officer</span><br>
            </td>
        </tr>
    </table>
</div>




@php
    function convert_number_to_words($number) {
        $hyphen      = ' ';
        $conjunction = ' and ';
        $separator   = ' ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
@endphp

</body>
</html>
