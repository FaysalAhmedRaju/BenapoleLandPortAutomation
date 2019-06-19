<!DOCTYPE html>
<html>
<head>
    <title>Voucher Report</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;

        }

        table, th, td {
            border: 1px solid black;
            padding: 1px;
            text-align: center;
        }

        .center {
            position: absolute;
            text-align: center;
            top: 0;
            left: 250px;
        }

        .txt-right {
            text-align: right;
        }
    </style>
</head>
<body>
<img src="../public/img/blpa.jpg">
<p class="center">
    <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
    <span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    <span style="font-size: 19px;">Expenditure Voucher</span> <br>

</p>
<br><br><br>
<table style="border: none !important;">
    <tr>
        <td style="border: none !important; text-align: left">
            Voucher No. <b>{{$expenditure[0]->vouchar_no}}</b> <br>
            Voucher Date:<b>{{$expenditure[0]->vouchar_date}}</b>
        </td>
        <td style="border: none !important; text-align: right">Date : {{$todayWithTime}}</td>

    </tr>
</table>

<br>
<table style="page-break-inside:avoid;">
    <caption style="padding-bottom: 10px;"><b><u></u></b></caption>
    <thead>
    <tr>
        <th>S/l</th>
        <th>Particulars</th>
        <th>Created By</th>
        <th class="txt-right">Amount <br>TK.</th>


    </tr>
    </thead>
    <tbody>

    @foreach($expenditure as $key => $ex)
        <tr>
            <td width="60">{{ ++$key }}</td>
            <td>{{ $ex->acc_sub_head }}</td>
            <td>{{ $ex->username}}</td>
            <td class="txt-right">{{ number_format($ex->debit , 2, '.', ',')}}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="3"><b>Total:</b></td>
        <td class="txt-right">
            <b>{{number_format(ceil($expenditure[0]->total), 2, '.', ',')}}</b>
        </td>
    </tr>

    <tr>
        <td style="border: none !important; text-align: right" colspan="4"><br><br></td>
    </tr>

    <tr>
        <td style="border: none !important;">In word:<br></td>
        <th style="border: none !important;" colspan="3">&nbsp;<span
                    style="text-transform: capitalize">{{convert_number_to_words(ceil($expenditure[0]->total) )." Taka only"}}</span><br>
        </th>
    </tr>
    </tfoot>
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