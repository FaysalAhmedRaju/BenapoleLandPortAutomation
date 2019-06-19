<!DOCTYPE html>
<html>
<head>
    <title>Bank Voucher Report</title>
    <style>
        table.dataTable {
            border-collapse: collapse;
            width: 100%;
        }
        table.dataTable, table.dataTable th, table.dataTable td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        table.dataTable1 {
            border-collapse: collapse;
            width: 100%;
        }
        table.dataTable1 th, table.dataTable1 td {
            border: 1px solid;
            padding: 5px;
            text-align: right;
        }
        table.dataTable1 tr td:first-child {
            border: 0;
        }
        table.dataTable1 tr td:last-child{
            border-top: 0;
        }

        html {
            margin: 5px 5px 0;
        }
        body{
            background-image: url(/img/Logo_BSBK.gif);
            /*background: url(/img/blpa.jpg );*/
            background-repeat:no-repeat;
            background-position:center center;
            background-size:250px 180px;
            opacity: .2;
        }
        .center {
            position: absolute;
            text-align: center;
            top: 0;
            left: 250px;
        }

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
    </style>
</head>
<body>

<table   width="100%;" style="border: none !important;">
    <tr>
        <td style="width: 25%"  style="border: none !important;">
            <img src="../public/img/blpa.jpg" height="100">
        </td>
        <td style="width: 50%; text-align: center"  style="border: none !important;">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span> <br>
            <span style="font-size: 19px;"><b>Head office</b></span> <br>
            TCB Building (6th Floor), Kawran Bazar, Dhaka.<br>
            <span style="font-size: 19px;text-decoration: underline "><b>Bank Voucher (Credit)</b></span>

        </td>
        <td style="width: 25%; font-size: 14px; text-align: left; vertical-align: bottom;"  style="border: none !important;">
            Voucher No: {{$expenditure[0]->bank_vouchar_no}} <br>
            Voucher Date: {{$expenditure[0]->bank_vouchar_date}}<br><br>
            <b>Time:</b> {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}

        </td>

    </tr>

</table>

<br><br>
<table class="dataTable" >


    <thead>
    <tr >
        <td style="border: none !important; text-align: left" colspan="8" > Person/Organization: <b>{{$expenditure[0]->organization_name}}</b><br><br>

        Amount Of Money: <b style="text-transform: capitalize;">
                {{convert_number_to_words($expenditure[0]->total)." Taka only."}}
            </b>
            AS --------------------------------------------------------------------------------------------------------------------------------------------------------------------
            <br><br>
        </td>
        {{--{{convert_number_to_words($grandTotal)." Taka only."}}--}}

    </tr>

    </thead>
    <tbody>

    <tr>
        <td rowspan="3" style="width: 300px; text-align: left">

        </td>
        <td colspan="7"></td>

    </tr>
    <tr>
        {{--<td>2</td>--}}

        <td colspan="2" style="text-align: center;">Account Head</td>
        <td style="text-align: center">Portfolio</td>
        <td colspan="4" style="text-align: center">Amount Of Money</td>

    </tr>
    <tr>

        <td colspan="7" style="text-align: right!important;padding: 0" >
            <table class="dataTable1" width="100%">

                <tbody>	@php($i=0)
                @foreach($expenditure as $key => $valueBank)
                    <tr>

                        <td style="width: 249px" class="firstClass">{{ $valueBank->acc_sub_head }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                        <td>{{ $valueBank->amount }}</td>

                    </tr>

                @endforeach
                <tr  style="padding: 0">
                    <td class="firstClass">Total Amount:</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td>{{ number_format($expenditure[0]->total , 2, '.', ',')}}</td>
                </tr>
                </tbody>

            </table>
        </td>
    </tr>
    </tbody>
    <tfoot>

    <tr>
        <td style="border: none !important; text-align: left" colspan="8">
            <br>
            Cheque/PO/DD No:  <b style="text-decoration: underline"> {{$expenditure[0]->cheque_no}}</b> ____________________<br><br>

            According to ___________ No Foley.

            <br><br></td>
    </tr>
    </tfoot>
</table>
<br><br>


<table class="secondTable" style="width:100%" >
    <tr>
        <td style="width: 25%; text-align: center">

            <span style="text-transform: capitalize; font-family: Arial, Helvetica, sans-serif;">
              <i>{{Auth::user()->name}}</i>
            </span>
            <br>
            <span style="font-size: 15px;border-top: dotted 1px; padding-top: 100%;">
                Created By
            </span>
        </td>
        <td style="width: 25%; text-align: center">
            <span style="font-size: 15px;border-top: dotted 1px; padding-top: 100%;">Account holder</span>
        </td>
        <td style="width: 25%; text-align: center">
           <span style="font-size: 15px;border-top: dotted 1px; padding-top: 100%;">Approved By</span>
        </td>
        <td style="width: 25%; text-align: center">
               <span style="text-align: center; /*font-family:'Brush Script MT';*/ font-style: italic ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   &nbsp;&nbsp;&nbsp;&nbsp;</span> <br>
            <span style="border-top: dotted 1px; padding-top: 100%; font-size: 15px">Accepted</span><br>
        </td>
    </tr>
</table>


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