<!DOCTYPE html>
<html>
<head>
    <title>Bus Wise Export Report</title>
    <style>
        .center {
            position: absolute;
            text-align: center;
            top: 0;
            left: 150px;
        }

        /*.center{*/
        /*text-align: center;*/
        /*}*/
        .amount-right {
            text-align: right !important;
        }

        .tble-warehouse tr td {
            border: 1px solid black;
        }

        /*table {*/
        /*border-collapse: collapse;*/
        /*}*/

        /*table, th, td {*/
        /*border: 1px solid black;*/
        /*padding: 5px;*/
        /*}*/

        /*.center{*/

        /*text-align: center;*/

        /*}*/
        html {
            margin: 0px 10px 0;
        }

        p.paraMessage {
            border: 1px solid black;
            border-radius: 12px;
            text-align: center;
            /*border-left-width: 5px;*/
            /*border-left: 500px;*/
            /*border-right: 600px;*/
            margin: 0px 90px;
            /*margin-top: 0;*/
            padding: 4px;

        }
        p.testParagraph {
            border: 1px solid black;
            border-radius: 12px;
            text-align: center;



            /*margin: 0px 90px;*/

            margin-top: 0;
            margin-bottom: 0;
            margin-left: 90px;
            margin-right: 90px;
            padding: 4px;
        }

        td.GrandTotal {
            border: 1px solid black;
            border-radius: 12px;
            text-align: center;
        }

        p.BanglaWrite {
            text-align: center;
        }

        .tablBackGroundImage{
            width: 250px;
            height: 180px;
            /*height: 90%;*/
            background-image: url(/img/Logo_BSBK.gif);
            /*background: url(/img/blpa.jpg );*/
            background-repeat:no-repeat;
            background-position:center center;
            background-size:250px 180px;
            border: 0px solid black;
            opacity: .2;

        }

    </style>

</head>
<body>
<table style=" /*width: 100%*/">

    <td style=" text-align:center;  /*width: 10%;*/">
        <img src="../public/img/blpa.jpg" style="height: 70px">
    </td>
    <td style="  /*width: 75%;*/ ">

        <p style="text-align: center;   margin-bottom: 0; margin-top: 0;">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span> <br>
            <span style="font-size: 19px;">Benapole Land Port, Jessore</span><br>
            {{--<span style="font-size: 19px;">Export Sheet</span>--}}

        </p>


        <p class="paraMessage" style="font-size: 12px; /*background-color: yellow;*/ ">Bus Entry at International Bus Terminal and Haltage Charge Sheet</p>

    </td>

    <td style="/*background-color: #00e765; width:15%;*/">

        <p style="font-size: 15px">Driver Copy</p>
        <p style="font-size: 15px">SI.No: {{$bus_export_id}}</p>
    </td>
</table>

<div style="padding: 0">

    <table class="tablBackGroundImage" style="box-shadow: 0px 0px 1px 1px darkgrey; width: 100%; ">
        <tr>
            <td style="width:25%; ">
                {{--    <b>Truck No:</b>--}}
                Bus No:
            </td>

            <td style=" text-align: center; border-bottom: dotted 1px; width:35%; ">
                {{$type_name}}-{{$truckNO}}
            </td>

            <td style="border-bottom: dotted 1px; width:5%;">

            </td>

            <td style="border-bottom: dotted 1px; width:35%; ">

            </td>
        </tr>


        <tr>
            <td style=" ">
                {{--<b>Name of Driver:</b>--}}
                Name of Driver:
            </td>
            <td style="text-align: center; border-bottom: dotted 1px;">
                {{--{{$driverName}}--}}

            </td>
            <td style="  border-bottom: dotted 1px;">

            </td>
            <td style="  border-bottom: dotted 1px;">

            </td>
        </tr>

        <tr>
            <td style=" ">
                {{--<b>Entry Date:</b>--}}
                Entry Date:
            </td>
            <td style="text-align: center;  border-bottom: dotted 1px;">
                {{date('d-m-Y',strtotime($entry_datetime))}}

            </td>
            <td style="">
                {{--<b>Time:</b>--}}
                Time:
            </td>
            <td style=" text-align: center; border-bottom: dotted 1px;">
                {{--{{$entry_time_only}}--}}

            </td>

        </tr>




        <tr>
            <td style="" >
                {{--<b> Exit Date:</b>--}}
                Exit Date:
            </td>
            <td style="  border-bottom: dotted 1px;">
                {{--{{$exit_datetime}}--}}

            </td>
            <td style="">
                {{--<b> Time:</b>--}}
                Time:
            </td>
            <td style=" text-align: center;  border-bottom: dotted 1px;">
                {{--{{$exit_time_only}}--}}

            </td>

        </tr>



        <tr>
            <td style="">
                {{--<b>Haltage Time: </b>--}}
                Haltage Time:
            </td>
            <td style="border-bottom: dotted 1px; text-align: center">
                {{$h_day}}

            </td>
            <td style="border-bottom: dotted 1px; ">

            </td>
            <td style="border-bottom: dotted 1px;">
            </td>
        </tr>




        <tr>
            <td style="">
                {{--<b>&nbsp;1) Entry Fee:-Taka</b>--}}
                &nbsp;1) Entry Fee:-Taka
            </td>
            <td style="text-align: right;  border-bottom: dotted 1px;">

                {{--<b>{{$e_fee}}</b>--}}
                {{$e_fee}}
            </td>
            <td style="  border-bottom: dotted 1px;">
            </td>
            <td style="  border-bottom: dotted 1px;">

            </td>
        </tr>



        <tr>
            <td style="">
                {{--<b>2) Haltage Charge:-Taka</b>--}}
                2) Haltage Charge:-Taka
            </td>
            <td style=" text-align: right;  border-bottom: dotted 1px;">

                {{--<b>{{$New_holtageTotalcharge}}</b>--}}
                {{$New_holtageTotalcharge}}
            </td>
            <td style="  border-bottom: dotted 1px;">

            </td>
            <td style=" border-bottom: dotted 1px;">

            </td>
        </tr>


        <tr>
            <td style="">
                {{--<b>Total Taka:</b>--}}
                Total Taka:

            </td>
            <td style=" text-align: right;  border-bottom: dotted 1px;">
                {{--<b>{{$totalTaka}}</b>--}}
                {{$totalTaka}}
            </td>
            <td style=" border-bottom: dotted 1px;">

            </td>
            <td style=" border-bottom: dotted 1px;">

            </td>
        </tr>



        <tr>
            <td style="">
                {{--<b>VAT:</b>--}}
                VAT:
            </td>
            <td style=" text-align:right; border-bottom: dotted 1px;">
                {{--<b>{{$vat}}</b>--}}
                {{$vat}}
            </td>
            <td style=" border-bottom: dotted 1px;">
            </td>

            <td style=" border-bottom: dotted 1px;">

            </td>
        </tr>


        <tr>
            <td style="">
                {{--<b>Grand Total Taka:</b>--}}
                Grand Total Taka:
            </td>
            <td {{--style="text-align: right"--}} style="" class="GrandTotal">
                {{--<b>{{$grandTotal}}</b>--}}
                {{$grandTotal}}
            </td>
            <td style="">

            </td>
            <td style="">

            </td>
        </tr>

    </table>

</div>
{{----------------------done-----------------------}}

<table style="box-shadow: 0px 0px 1px 1px darkgrey; width: 100%;">
    <tr>
        <td style="width: 25%">
            {{--<b> In Word:</b>--}}
            In Word:
        </td>
        <td {{--colspan="2"--}} style=" border-bottom: dotted 1px;  width: 50% ">
            ( <span style="text-transform: capitalize"> {{convert_number_to_words($grandTotal)." Taka"}}</span>

        </td>

        <td style="width: 25%; border-bottom: dotted 1px;" >

        </td>
        <td style="  /*border-bottom: dotted 1px;*/" >
            )Only
        </td>
    </tr>
</table>

<div style="width: 100%; ">
    <table style="width:100%;">
        <tr>
            <td style="width:20%">
            </td>
            <td style="width:60%;/* background-color: greenyellow*/">
                <p class="BanglaWrite"><b>"Take oath to love the country, Give up the corruption"</b></p>
            </td>
            <td style="text-align:center;">
               <span style="text-align:center; font-style: italic ">
                   {{Auth()->user()->name}}</span>
                <br>
                <span style="border-top: dotted 1px;">Incharge/Assistant</span>
            </td>
            {{--<td style="width: 20%;">
               <span >{{Auth()->user()->name}}----------------------</span><br>
               <span>Incharge/Assistant</span>
            </td>--}}
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

