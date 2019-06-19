<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <link rel="shortcut icon" href="{{asset('/images/favicon.ico')}} " type="image/x-icon">
    <link rel="icon" href="{{asset('/images/favicon.ico')}}" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <title>@yield('title')</title>


{!!Html :: style('css/AdminLTE.min.css')!!}
{!!Html :: style('css/all-skins.min.css')!!}
{{--{!!Html :: style('css/ionicons.min.css')!!}--}}

{!!Html :: style('css/bootstrap.min.css')!!} <!--3.3.7-->
{!!Html :: style('css/bootstrap-theme.min.css')!!}<!--3.3.7-->
{!!Html :: style('css/jquery-ui.min.css')!!}<!--3.3.7-->
{!!Html :: style('css/jquery-ui.theme.min.css')!!}<!--3.3.7-->
{!!Html :: style('css/ng-tags-input.min.css')!!}
{!!Html :: style('css/ng-tags-input.bootstrap.min.css')!!}





{!!Html :: style('css/font-awesome.min.css')!!}
{!!Html :: style('css/Site.css')!!}
{!!Html :: style('css/pdf.css')!!}
{!! Html::style('/css/jquery.growl.css') !!}

@yield('style')

{!!Html :: script('js/jquery-2.2.3.min.js')!!}
{!!Html :: script('js/bootstrap.min.js')!!}
{!!Html :: script('js/jquery-ui.min.js')!!}


{!!Html :: script('js/angular.min.js')!!}
{!!Html :: script('js/angular-animate.js')!!}
{!!Html :: script('js/dirPagination.js')!!}
{!!Html :: script('js/ng-tags-input.min.js')!!}
{!!Html :: script('js/angular-ui-bootstrap.js')!!}
{!!Html :: script('js/customizedAngular/CustomService.js')!!}
{!!Html :: script('js/kendo.all.min.js')!!}
{!!Html :: script('js/pako_deflate.min.js')!!}
{!!Html :: script('js/jquery-ui-timepicker-addon.js')!!}

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">

        @include('layouts.header')
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">


            @if(view()->exists('layouts.sidebars.'. Auth::user()->role_id))
                @include('layouts.sidebars.'.Auth::user()->role_id)
            @else
                {{--{{generateSideBar()}}--}}

                {{--@include('layouts.sidebars.'.Auth::user()->role_id)--}}
                 @include('layouts.sidebar-d')

            @endif
            {{--<ul class="sidebar-menu">
                @if(Auth::user()->role_id == 1 && Session::get('PORT_ID') == 4)
                    @include('layouts.sidebars.1_bhomra')
                @else
                    @if (view()->exists('layouts.sidebars.' . Auth::user()->role_id))
                        @include('layouts.sidebars.'.Auth::user()->role_id)
                    @else
                        @include('layouts.sidebar-d')
                    @endif
                @endif
            </ul>--}}
            </section>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-color: #FFF;">


        <!-- Main content -->
        <section class="content">
            <div class="row">

                @yield('content')

            </div>
        </section>
        <!-- /END .content -->
    </div>
    <!-- /.END content-wrapper -->

    <footer class="main-footer">

        <strong>Copyright &copy; 2017 <a href="http://www.datasoft-bd.com" target="_blank">DataSoft Systems Bangladesh
                Ltd.</a>.</strong>
    </footer>


</div>

@yield('script')

{!!Html :: script('js/app.min.js')!!}
{!! Html::script('js/jquery.growl.js') !!}
<script>
    $(function () {

        $(".datePicker").datepicker(
                {
                    /* changeMonth: true,
                     changeYear: true,*/
                    dateFormat: 'yy-mm-dd',
                }
        );

                $('.datetimepicker').datetimepicker({
                    showButtonPanel: true,
                    dateFormat: 'yy-mm-dd',
                    timeFormat: 'HH:mm:ss'
                });



    });
</script>
<script type="text/javascript">


    function generatePDFpp() {


        kendo.drawing.drawDOM($('#aa'), {
            // paperSize: [1100, 1430],   //letter size 8.5"x11"
            paperSize: "A4",
            landscape: true,
            PrintOnFirstPage: false,
            margin: {top: "1cm", left: "1cm", right: "1cm", bottom: "2cm"},
            template: $("#page-template").html(),
            scale: 0.8,
            forcePageBreak: ".page-break",
            date: new Date(),
            title: 'My Title',
            subject: 'My subject'


        }).then(function (group) {

            kendo.drawing.pdf.saveAs(group, "Report" + new Date() + ".pdf");
        });


    }

</script>
<script type="x/kendo-template" id="page-template">

    <div class="page-template">
        <div class="header">

            <div style="float: right;">Page #: pageNum # of #: totalPages #</div>

        </div>
        <div class="watermark">BLPA</div>
        <div class="foote">

        </div>
    </div>

</script>

@include('sweet::alert')
</body>
</html>