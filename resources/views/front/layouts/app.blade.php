<!DOCTYPE html>
<html>
	<head>
		<title>Contest Partner</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="icon" href="{{asset('front_asset/assets/images/fevicon-icon.svg') }}" sizes="any" type="image/svg+xml">
<!-- <link rel="stylesheet" href="{{asset('dist/css/styles.css') }}"> -->
		<link rel="stylesheet" href="{{asset('front_asset//assets/css/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{asset('front_asset//assets/css/font-awesome.css') }}">
		<style>
			.error{
				color:red;
			}
			#user_cards{
				overflow-y: auto;
				overflow-x: hidden;
				max-height: 380px;
			}
		</style>
		<link rel="stylesheet" href="{{asset('front_asset/assets/css/style.css') }}">
		
		<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.css" integrity="sha512-8D+M+7Y6jVsEa7RD6Kv/Z7EImSpNpQllgaEIQAtqHcI0H6F4iZknRj0Nx1DCdB+TwBaS+702BGWYC0Ze2hpExQ==" crossorigin="anonymous" />-->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.css" integrity="sha512-8D+M+7Y6jVsEa7RD6Kv/Z7EImSpNpQllgaEIQAtqHcI0H6F4iZknRj0Nx1DCdB+TwBaS+702BGWYC0Ze2hpExQ==" crossorigin="anonymous" />
		
	</head>

<body class="with-sticky-header home-page">
	<!-- Start header -->
	<span class="position-absolute trigger"><!-- hidden trigger to apply 'stuck' styles --></span>
	<header id="header" class="p-3">

		<nav class="navbar navbar-expand-lg sticky-top navbar-light bg-white main-navigation">
		    <div class="container">
		        <a class="navbar-brand" href="{{ url('/') }}">
			  	    <img  class="img-fluid" src="{{asset('front_asset/assets/images/logo_100x100.png') }}" alt="Wappnet">
			  	</a>
		        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar1">
		            <span class="navbar-toggler-icon"></span>
		        </button>
		        <div class="collapse navbar-collapse" id="navbar1">
		            <ul class="navbar-nav ml-auto">
		                <!-- <li class="nav-item top-item active">
		                    <a class="nav-link top-link" href="#">Home</a>
		                </li> -->

		                <li class="nav-item top-item dropdown level-1">
                            <a class="nav-link top-link dropdown-toggle" href="#" id="navbarServices" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						          	@lang('messages.solutions_link') <i class="fa fa-angle-down down-arrow-icon" aria-hidden="true"></i>
						        </a>
		                    <div class="dropdown-menu dropdown-menu-center top-auto top-submenu-drop">
							  	<div class="container level-2">
							      	<div class="row row-level-2 no-gutters">
							          	<div class="col-sm-6 col-md-4 col-lg-4 col-group-menu col-right">
											<div class="group-head">
												<div class="drp-top-title">Survey Types</div>
											</div>
											<div class="group-content">
												<ul>
													<li><a href="#" class="dropdown-item">Customer Satisfaction</a></li>
													<li><a href="#" class="dropdown-item">Customer Loyalty</a></li>
													<li><a href="#" class="dropdown-item">Event Surveys</a></li>
												</ul>
											</div>
											<div class="group-content">
												<ul>
													<li><a href="#" class="dropdown-item">Employee Engagement</a></li>
													<li><a href="#" class="dropdown-item">Job Satisfaction</a></li>
													<li><a href="#" class="dropdown-item">HR Surveys</a></li>
												</ul>
											</div>
											<div class="group-content">
												<ul>
													<li><a href="#" class="dropdown-item">Market Research</a></li>
													<li><a href="#" class="dropdown-item">Opinion Polls</a></li>
													<li><a href="#" class="dropdown-item">Concept Testing</a></li>
												</ul>
											</div>
							          	</div>
							          	<div class="col-sm-6 col-md-4 col-lg-4 col-group-menu col-left">
							          		<div class="group-head">
												<div class="drp-top-title">Powered Data for business</div>
											</div>
											<div class="group-content">
												<ul>
													<li>
														<a href="#" class="dropdown-item">Customers</a>
														<p class="sub-title">Win more business with Customer Powered Data</p>
													</li>
													<li>
														<a href="#" class="dropdown-item">Employees</a>
														<p class="sub-title">Build a stronger workforce with Employee Powered Data</p>
													</li>
													<li>
														<a href="#" class="dropdown-item">Markets</a>
														<p class="sub-title">Validate business strategy with Market Powered Data</p>
													</li>
												</ul>
											</div>
							          	</div>
							          	<div class="col-sm-6 col-md-4 col-lg-4 col-group-menu col-left">
							          		<div class="group-head">
												<div class="drp-top-title">Solutions for teams</div>
											</div>
											<div class="group-content">
												<ul>
													<li>
														<a href="#" class="dropdown-item">Customer Experience</a>
														<p class="sub-title">Delight customers & increase loyalty through feedback</p>
													</li>
													<li>
														<a href="#" class="dropdown-item">Human Resources</a>
														<p class="sub-title">Improve your employee experience, engagement & retention</p>
													</li>
													<li>
														<a href="#" class="dropdown-item">Marketing</a>
														<p class="sub-title">Create winning campaigns, boost ROI & drive growth</p>
													</li>
												</ul>
											</div>
							          	</div>
							      	</div>
							  	</div>
							</div>
		                </li>

		                <li class="nav-item top-item">
		                    <a class="nav-link top-link" href="{{ route('front.plans') }}">@lang('messages.pricing_link')</a>
		                </li>
						<li class="nav-item top-item">
						<a class="nav-link top-link" href="{{route('front.about_us')}}">About Us</a>
						</li>
                        @if (!Auth::check())
                            <li class="nav-item top-item">
                                <a class="nav-link top-link" href="{{ route('front.login') }}">@lang('messages.login_link')</a>
                            </li>
                            {{-- <li class="nav-item top-item">
                                <a class="nav-link top-link" href="#">@lang('messages.get_started_link')</a>
                            </li> --}}
                        @else
                            {{-- <li class="nav-item top-item">
                                <a class="nav-link top-link" href="{{route('front.dashboard')}}">@lang('messages.get_started_link')</a>
                            </li> --}}
                            <li class="nav-item top-item">
                                <a class="nav-link top-link" href="{{ route('front.logout') }}">Logout</a>
                            </li>
                        @endif
                        {{-- <a class="nav-link top-link" href="{{ url('lang') }}/en">English</a>
                        <a class="nav-link top-link" href="{{ url('lang') }}/fr">French</a> --}}

		            </ul>
		        </div>
		    </div>
		</nav>
	</header>
	<!-- End header -->

	<!-- Start Main Container -->
	{{-- <div class="container"> --}}
    @yield('content')
    @php
        $current_route=Route::currentRouteName();
    @endphp
    @if ($current_route != "front.login")
    <!-- Start Contact us Section -->
	<section class="bg-gray contact-us-section">
		<div class="container">
			<div class="row">
				<div class="col-12 col-sm-12 col-md-12">
					<div class="title txt-white text-center font-italic mb-20">Choose a Better Way To Work</div>
					<div class="button-section text-center">
						<a href="{{route('front.plans')}}"><button type="button" class="btn btn-white-gray-border btn-first">Try To Free</button></a>
						@if ($current_route != "front.contact_us")
						<a href="{{route('front.contact_us')}}"><button type="button" class="btn btn-gray-white-border">Contact Us</button></a>
						@endif
					</div>
				</div>
			</div>
		</div>
	</section>
    <!-- End Contact us Section -->
    @endif
	    <!--/row-->
	{{-- </div> --}}
                                       <script type="text/javascript" src="{{config('constants.S3_LINK')}}/assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="{{config('constants.S3_LINK')}}/assets/js/popper.min.js"></script>
		<script type="text/javascript" src="{{config('constants.S3_LINK')}}/assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="{{config('constants.S3_LINK')}}/assets/js/slick.min.js"></script>

		<script type="text/javascript" src="{{config('constants.S3_LINK')}}/assets/js/custom-script.js" charset="utf-8" async defer></script>
	<script type="text/javascript" src="{{config('constants.S3_LINK')}}/assets/js/jquery.toast.js" ></script>
    <script type="text/javascript" src="{{config('constants.S3_LINK')}}/assets/js/jquery.validate.min.js" ></script>
    
    
    @yield('script')
	<!-- End Main Container -->
	<!-- Start Footer-->
	<!-- Start Footer-->
	<footer id="footer">
        @php
            $current_route=Route::currentRouteName();
        @endphp
        @if ($current_route != "front.login")
            <section>
                <div class="container bg-white footer-menu-wrappper">
                <div class="row align-items-center">
                    <div class="col-6 col-sm-6 col-md-3 col-lg-3">
                        <a class="company-logo" href="{{ url('/') }}">
                            <img  class="img-fluid" src="{{asset('front_asset/assets/images/logo_100x100.png') }}" alt="Wappnet">
                        </a>
                    </div>
                    <div class="col-6 col-sm-6 col-md-3 col-lg-3">
                        <ul>
                            <li class="top-section font-bold"><a href="{{route('front.about_us')}}">About Us</a></li>
                            {{-- <li><a href="#">Leadership Team</a></li>
                            <li><a href="#">Board of Directors</a></li>
                            <li><a href="#">Investor Relations</a></li>
                            <li><a href="#">App Directory</a></li>
                            <li><a href="#">Newsroom</a></li>
                            <li><a href="#">Office locations</a></li>
                            <li><a href="#">Jobs</a></li>
                            <li><a href="#">Sitemap</a></li>
                            <li><a href="#">Help</a></li> --}}
                        </ul>
                    </div>
                    <div class="col-6 col-sm-6 col-md-3 col-lg-3">
                        <ul>
                            <li class="top-section font-bold"><a href="{{route('front.privacy_policy')}}">Privacy Policy</a></li>
                            {{-- <li><a href="#">Leadership Team</a></li>
                            <li><a href="#">Board of Directors</a></li>
                            <li><a href="#">Investor Relations</a></li>
                            <li><a href="#">App Directory</a></li>
                            <li><a href="#">Newsroom</a></li>
                            <li><a href="#">Office locations</a></li>
                            <li><a href="#">Jobs</a></li>
                            <li><a href="#">Sitemap</a></li>
                            <li><a href="#">Help</a></li> --}}
                        </ul>
                    </div>
                    <div class="col-6 col-sm-6 col-md-3 col-lg-3">
                        <ul>
                            <li class="top-section font-bold"><a href="{{route('front.faqs')}}">FAQs</a></li>
                            {{-- <li><a href="#">Leadership Team</a></li>
                            <li><a href="#">Board of Directors</a></li>
                            <li><a href="#">Investor Relations</a></li>
                            <li><a href="#">App Directory</a></li>
                            <li><a href="#">Newsroom</a></li>
                            <li><a href="#">Office locations</a></li>
                            <li><a href="#">Jobs</a></li>
                            <li><a href="#">Sitemap</a></li>
                            <li><a href="#">Help</a></li> --}}
                        </ul>
                    </div>
                </div>
                </div>
            </section>
        @endif
		<div class="container-fluid copyright-wrapper bg-gray pt-15 pb-15">
			<div class="container">
				<div class="row">
					<div class="col-12 col-sm-12 col-md-7 col-lg-7">
						<div class="copyright">
							Copyright <i class="fa fa-copyright"></i> <a href="{{$setting_details['url']}}">{{$setting_details['develop_by']}}</a> | {{ date('Y') }} All rights reserved.
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-5 col-lg-5">
						<ul class="social-media-wrappper">
                            @if ($sem['facebook'])
                            <li>
                            <a href="{{$sem['facebook']}}" class="social-media">
									<i class="fa fa-facebook" aria-hidden="true"></i>
								</a>
							</li>
                            @endif

                            @if ($sem['twiter'])
							<li>
								<a href="{{$sem['twiter']}}" class="social-media">
									<i class="fa fa-twitter" aria-hidden="true"></i>
								</a>
                            </li>
                            @endif

                            @if ($sem['skype'])
							<li>
								<a href="{{$sem['skype']}}" class="social-media">
									<i class="fa fa-skype" aria-hidden="true"></i>
								</a>
                            </li>
                            @endif

                            @if ($sem['whats_app'])
							<li>
								<a href="{{$sem['whats_app']}}" class="social-media">
									<i class="fa fa-whatsapp" aria-hidden="true"></i>
								</a>
                            </li>
                            @endif

                            @if ($sem['insta'])
							<li>
								<a href="{{$sem['insta']}}" class="social-media">
									<i class="fa fa-instagram" aria-hidden="true"></i>
								</a>
                            </li>
                            @endif

                            @if ($sem['linkedin'])
							<li>
								<a href="{{$sem['linkedin']}}" class="social-media">
									<i class="fa fa-linkedin" aria-hidden="true"></i>
								</a>
                            </li>
                            @endif
						</ul>
					</div>
				</div>
			</div>
		</div>

    </footer>
    <script>
        var start = 0;
        $(document).ready(function() {
            start = new Date();
            /* $(window).on("unload", function(e) {
            }); */

            // console.log(end - start);
            /* if("{{Auth::check()}}"){
                setInterval(user_log,15000);
            } */

        });

    /* function user_log(){
        var end = new Date();
        $.ajax({
            type : "POST",
            url: "{{route('front.user_log')}}",
            data: {
                'timeSpent': end,
                '_token' : "{{csrf_token()}}"
            },
            async: false,
            success:function(data){
                console.log(data);
            }
        });
    } */
    </script>
	<!-- End Footer-->
	<!-- End Footer-->

</body>
</html>
