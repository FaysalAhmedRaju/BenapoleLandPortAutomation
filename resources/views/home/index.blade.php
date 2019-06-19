<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title>BLPA</title>

{!!Html :: style('css/bootstrap.min.css')!!} <!--3.3.7-->

{!!Html :: script('js/jquery-2.2.3.min.js')!!}
{!!Html :: script('js/bootstrap.min.js')!!}


{!!Html :: style('css/bootstrap-theme.min.css')!!}<!--3.3.7-->
{!!Html :: style('css/font-awesome.min.css')!!}
<!-- Custom styles -->

    {!! Html :: style('css/style.css')!!}

    {!! Html :: style('css/style-responsive.css')!!}


    {!! Html :: style('css/slideShowAndNavbar.css')!!}


    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" rel="stylesheet" media="all">--}}



    {{-- <script>
         $('#bootstrap-touch-slider').bsTouchSlider();
     </script>
 --}}


</head>

<body>

<!-- Fixed navbar -->
<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            {{--<a class="navbar-brand" href="#">Menu Nuevo</a>--}}
            <img class="navbar-brand" src="{{URL::asset('img/blpa.jpg')}}" alt="" width="97" height="49">
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                {{--<li class="dropdown">--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Document--}}
                        {{--<span class="caret"></span></a>--}}
                    {{--<ul class="dropdown-menu multi-level" role="menu">--}}
                        {{--<li><a target="_blank" href="{{asset(url('/file/Manual.pdf'))}}">Manual</a></li>--}}
                        {{--<li><a target="_blank" href="{{asset(url('/file/bpl_tariff.pdf'))}}">Tariff</a></li>--}}
                        {{--<li><a target="_blank" href="{{asset(url('/file/Head_SubHead.pdf'))}}">Sub Head</a></li>--}}


                    {{--</ul>--}}
                {{--</li>--}}
                {{--<li class="dropdown">--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Contact--}}
                        {{--<span class="caret"></span></a>--}}
                    {{--<ul class="dropdown-menu multi-level" role="menu">--}}
                        {{--<li><a href="#">Action</a></li>--}}


                    {{--</ul>--}}
                {{--</li>--}}
            </ul>

        </div><!--/.nav-collapse -->
    </div>
</nav>
<div class="clear-fix"></div>
<div class="container">
    <div class="row" style="padding: 10px; background-color: #FFF">

        <div col-md-12>


            <div id="bootstrap-touch-slider" class="carousel bs-slider slide  control-round indicators-line"
                 data-ride="carousel" data-pause="hover" data-interval="5000">

                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#bootstrap-touch-slider" data-slide-to="0" class="active"></li>
                    <li data-target="#bootstrap-touch-slider" data-slide-to="1"></li>
                    <li data-target="#bootstrap-touch-slider" data-slide-to="2"></li>
                    <li data-target="#bootstrap-touch-slider" data-slide-to="3"></li>
                    <li data-target="#bootstrap-touch-slider" data-slide-to="4"></li>
                    <li data-target="#bootstrap-touch-slider" data-slide-to="5"></li>
                </ol>

                <!-- Wrapper For Slides -->
                <div class="carousel-inner" role="listbox">

                    <!-- Third Slide -->
                    <div class="item active">

                        <!-- Slide Background -->
                        <img src="{{URL::asset('img/blps1.png')}} " style="height: 400px" alt="Bootstrap Touch Slider"
                             class="slide-image"/>

                        <div class="bs-slider-overlay"></div>

                        <div class="container">
                            <div class="row">
                                <!-- Slide Text Layer -->
                                <div class="slide-text slide_style_left">
                                    {{--<h1 data-animation="animated zoomInRight">Bootstrap Carousel</h1>
                                    <p data-animation="animated fadeInLeft">Bootstrap carousel now touch enable slide.</p>
                                    <a href="http://bootstrapthemes.co/" target="_blank" class="btn btn-default" data-animation="animated fadeInLeft">select one</a>
                                    <a href="http://bootstrapthemes.co/" target="_blank"  class="btn btn-primary" data-animation="animated fadeInRight">select two</a>
                               --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Slide -->

                    <!-- Second Slide -->
                    <div class="item">

                        <!-- Slide Background -->
                        <img src="{{URL::asset('img/blps2.jpg')}}" style="height: 400px" alt="Bootstrap Touch Slider"
                             class="slide-image"/>
                        <div class="bs-slider-overlay"></div>
                        <!-- Slide Text Layer -->
                        <div class="slide-text slide_style_center">
                            {{-- <h1 data-animation="animated flipInX">Bootstrap touch slider</h1>
                             <p data-animation="animated lightSpeedIn">Make Bootstrap Better together.</p>
                             <a href="http://bootstrapthemes.co/" target="_blank" class="btn btn-default" data-animation="animated fadeInUp">select one</a>
                             <a href="http://bootstrapthemes.co/" target="_blank"  class="btn btn-primary" data-animation="animated fadeInDown">select two</a>
                        --}}
                        </div>
                    </div>
                    <!-- End of Slide -->

                    <!-- Third Slide -->
                    <div class="item">

                        <!-- Slide Background -->
                        <img src="{{URL::asset('img/bpls3.png')}}" style="height: 400px" alt="Bootstrap Touch Slider"
                             class="slide-image"/>
                        <div class="bs-slider-overlay"></div>
                        <!-- Slide Text Layer -->
                        <div class="slide-text slide_style_right">
                            {{-- <h1 data-animation="animated zoomInLeft">Beautiful Animations</h1>
                             <p data-animation="animated fadeInRight">Lots of css3 Animations to make slide beautiful .</p>
                             <a href="http://bootstrapthemes.co/" target="_blank" class="btn btn-default" data-animation="animated fadeInLeft">select one</a>
                             <a href="http://bootstrapthemes.co/" target="_blank" class="btn btn-primary" data-animation="animated fadeInRight">select two</a>
                         --}}
                        </div>
                    </div>
                    <!-- End of Slide -->

                    <!-- forth Slide -->
                    <div class="item">

                        <!-- Slide Background -->
                        <img src="{{URL::asset('img/bpls4.jpg')}}" style="height: 400px" alt="Bootstrap Touch Slider"
                             class="slide-image"/>
                        <div class="bs-slider-overlay"></div>
                        <!-- Slide Text Layer -->
                        <div class="slide-text slide_style_right">
                            {{-- <h1 data-animation="animated zoomInLeft">Beautiful Animations</h1>
                             <p data-animation="animated fadeInRight">Lots of css3 Animations to make slide beautiful .</p>
                             <a href="http://bootstrapthemes.co/" target="_blank" class="btn btn-default" data-animation="animated fadeInLeft">select one</a>
                             <a href="http://bootstrapthemes.co/" target="_blank" class="btn btn-primary" data-animation="animated fadeInRight">select two</a>
                        --}}
                        </div>
                    </div>
                    <!-- End of Slide -->

                    <!-- fifth Slide -->
                    <div class="item">

                        <!-- Slide Background -->
                        <img src="{{URL::asset('img/bpls5.png')}}" style="height: 400px" alt="Bootstrap Touch Slider"
                             class="slide-image"/>
                        <div class="bs-slider-overlay"></div>
                        <!-- Slide Text Layer -->
                        <div class="slide-text slide_style_right">
                            {{-- <h1 data-animation="animated zoomInLeft">Beautiful Animations</h1>
                             <p data-animation="animated fadeInRight">Lots of css3 Animations to make slide beautiful .</p>
                             <a href="http://bootstrapthemes.co/" target="_blank" class="btn btn-default" data-animation="animated fadeInLeft">select one</a>
                             <a href="http://bootstrapthemes.co/" target="_blank" class="btn btn-primary" data-animation="animated fadeInRight">select two</a>
                        --}}
                        </div>
                    </div>
                    <!-- End of Slide -->

                    <!-- sixth Slide -->
                    <div class="item">

                        <!-- Slide Background -->
                        <img src="{{URL::asset('img/bpls6.png')}}" style="height: 400px" alt="Bootstrap Touch Slider"
                             class="slide-image"/>
                        <div class="bs-slider-overlay"></div>
                        <!-- Slide Text Layer -->
                        <div class="slide-text slide_style_right">
                            {{--  <h1 data-animation="animated zoomInLeft">Beautiful Animations</h1>
                              <p data-animation="animated fadeInRight">Lots of css3 Animations to make slide beautiful .</p>
                              <a href="http://bootstrapthemes.co/" target="_blank" class="btn btn-default" data-animation="animated fadeInLeft">select one</a>
                              <a href="http://bootstrapthemes.co/" target="_blank" class="btn btn-primary" data-animation="animated fadeInRight">select two</a>
                         --}}
                        </div>
                    </div>
                    <!-- End of Slide -->


                </div><!-- End of Wrapper For Slides -->

                <!-- Left Control -->
                <a class="left carousel-control" href="#bootstrap-touch-slider" role="button" data-slide="prev">
                    <span class="fa fa-angle-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>

                <!-- Right Control -->
                <a class="right carousel-control" href="#bootstrap-touch-slider" role="button" data-slide="next">
                    <span class="fa fa-angle-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>

            </div> <!-- End  bootstrap-touch-slider Slider -->

        </div>
    </div>
</div>
<div class="container" style="background-color:#FFF;">
    <div class="row" style="padding: 10px;">
        <div class="col-md-12" style="min-height: 50px">

        </div>
        <div class="col-md-9" style="">

            <h2><span>Bangladesh Land Port</span> Authority</h2>
            <p class="infopost">Today: <span class="date">
            <?php $timezone = 6; //(GMT -6:00) EST (Dhaka)
            echo gmdate("F j, Y, g:i a", time() + 3600 * ($timezone + date("I")));
            ?>
            <p class="text-justify">On the Bangladesh-India land border, Benapole is the most important checkpost of
                Bangladesh and is operated by the Bangladesh Land Port Authority (BLPA). Geographically Benapole is a
                major strategical point for border trading between India and Bangladesh owing ito its proximity to
                Kolkata. According to Land Port Authority, approximately 90 percent of the total imported items from
                India come through Benapole. Primarily Benapole land port was an Land Customs station and gradually it
                turned into a Customs Division (1984) and later Custom House (1997) in response to its rising importance
                as in terms of import volume. In 2009, Finance minister Abul Maal Abdul Muhith opened the newly
                constructed building of Benapole Customs and Immigration Check post.[1]

                As of 2009, 143 staff including 9 officials and 134 employees are working at the Benapole land port. In
                fiscal year 1996-97 revenue realized from Benapole land port was around Taka 5 billion, at present it is
                Taka 8.50 billion.

                Benapole land port is also lucrative for Indian exporters for its cheaper service and equipment charges.
                Indian goods receive duty exemption advantage in this land port. The Indian Government has also decided
                to give priority to export in Bangladesh through Benapole-Petrapole border. Kolkata, one of the
                commercial hubs of India, is only 80 kilometers away from the Petrapole-Benapole border and is involved
                in development in the area.[2]

                Benapole had witnessed a rise of imports by 15 – 20 percent each year. It has become a significant
                revenue generator for the government since late 1980s. However, port facilities remain under-developed
                as yet. Carriability of the road from Benapole to Jessopre is limited notwithstanding regular
                maintenance. A two-member consultant team of the Asian Development Bank (ADB) is working to sort out
                improvement areas in the immigration and customs of the land port and also studying feasibility of
                Benapole-Petropole border as a corridor of transit in this South Asian region
            </p>

        </div>

        <div class="col-md-3" style="padding: 0">
            <div class="col-md-12" style="padding: 0">

                <form class="login-form" action="{{ route('log-in') }}" method="POST">
                    {!! csrf_field() !!}


                    <div class="login-wrap"
                         style="background-color:white;box-shadow:1px 0px 5px #888888; border-radius: 10px; ">
                        {{--<p class="login-img"><i class="icon_lock_alt"></i></p>--}}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input required type="text" name="username" class="form-control  input-sm"
                                   placeholder="Username"
                                   autofocus>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                            <input required type="password" name="password" class="form-control" placeholder="Password">
                        </div>
                        {{-- <label class="checkbox text-center">
                             <input type="checkbox" value="remember-me"> Remember me <br>
                             <span class=""> <a href="#"> Forgot Password?</a></span>
                         </label>--}}
                        <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
                        @if(Session::has('loginFail'))
                            <p class="warning" style="color: red; font-size: 12px">{{ Session::get('loginFail') }}</p>
                        @endif
                        {{--<button class="btn btn-info btn-lg btn-block" type="submit">Signup</button>--}}
                    </div>


                </form>
                <br><br>
            </div>

            <div class="col-md-12" style="padding: 0">
                <div class="login-wrap"
                     style="background-color:white;box-shadow:1px 0px 5px #888888; border-radius: 10px; ">
                    <p style="color: black; text-align: center">Looking For Manifest Info?</p>

                    <form class="login-form" action="{{ route('public-manifest-report') }}" target="_blank"
                          method="POST">
                        {!! csrf_field() !!}

                        <div class="input-group">
                            <input type="text" style=" " class="form-control datePicker" ng-model="dateWiseReport"
                                   name="manifest_no" id="manifest_no" placeholder="Manifest no.">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary">
                                    Get Info
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>


            <div class="col-md-12" style="padding: 0">
                <div class="login-wrap" style="background-color:white;box-shadow:1px 0px 5px #888888; border-radius: 10px; ">
                    <h3 class="">Todays Leave</h3>
                    @if(count($leaveApp)>0)
                        @foreach($leaveApp as$k=>$leave)
                          <span></span>  <H5 class="label label-success"> {{$leave->employee->name or 'Not Found'}}</H5> <br>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid" style="background-color: #f2ffe8;">


        <div class="row">


            <div class="col-md-12" style="padding-top: 20px;">

                <p><img src="img/datasoft_logo.gif"/>&nbsp;&nbsp;&nbsp;&nbsp;
                    &copy; Copyright
                    <a href="http://www.datasoft-bd.com" target="_blank">DataSoft Systems Bangladesh Ltd.</a>.
                </p>

            </div>
        </div>

    </div>


</body>
</html>