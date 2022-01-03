<?php

use App\Roles;
use App\Role_module;
use App\Lib\Permissions;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('admin_asset/assets/plugins/images/favicon.png') }}">
    <title>{{env('APP_NAME')}}</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{asset('admin_asset/assets/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="{{asset('admin_asset/assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet">
    <!-- toast CSS -->
    <link href="{{asset('admin_asset/assets/plugins/bower_components/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <!-- morris CSS -->
    <link href="{{asset('admin_asset/assets/plugins/bower_components/morrisjs/morris.css') }}" rel="stylesheet">
    <!-- Image Popup CSS -->
    <link href="{{asset('admin_asset/assets/plugins/bower_components/Magnific-Popup-master/dist/magnific-popup.css') }}" rel="stylesheet">
    <!-- animation CSS -->
    <link href="{{asset('admin_asset/assets/css/animate.css') }}" rel="stylesheet">
    <link href="{{asset('admin_asset/assets/plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />

    <link href="{{asset('admin_asset/assets/plugins/bower_components/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin_asset/assets/plugins/bower_components/custom-select/custom-select.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin_asset/assets/plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link href="{{asset('admin_asset/assets/plugins/bower_components/multiselect/css/multi-select.css') }}" rel="stylesheet" type="text/css" />
    <!-- Custom CSS -->
    <link href="{{asset('admin_asset/assets/css/style.css') }}" rel="stylesheet">
    <!-- color CSS -->
    <link href="{{asset('admin_asset/assets/css/colors/purple-dark.css') }}" id="theme" rel="stylesheet">
    <link href="{{asset('admin_asset/assets/plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin_asset/assets/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Date picker plugins css -->
    <link href="{{asset('admin_asset/assets/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Daterange picker plugins css -->
    <link href="{{asset('admin_asset/assets/plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}" rel="stylesheet">
    <link href="{{asset('admin_asset/assets/plugins/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('admin_asset/assets/plugins/bower_components/dropify/dist/css/dropify.min.css') }}">
    <link href="{{asset('admin_asset/assets/plugins/bower_components/multiselect/css/multi-select.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin_asset/assets/plugins/bower_components/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('admin_asset/assets/plugins/bower_components/html5-editor/bootstrap-wysihtml5.css') }}" />
    <link href="{{asset('admin_asset/assets/plugins/bower_components/horizontal-timeline/css/horizontal-timeline.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_asset/assets/plugins/bower_components/fancybox/ekko-lightbox.min.css') }}" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body class="fix-sidebar">
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
                <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
                <div class="top-left-part"><a class="logo" href="{{ route('admin.dashboard') }}"><b>
                            <!--This is dark logo icon-->
                            <img src="{{asset('admin_asset/assets/plugins/images/eliteadmin-logo.png') }}" alt="home" class="dark-logo" />
                            <!--This is light logo icon-->
                            <img src="{{asset('admin_asset/assets/plugins/images/eliteadmin-logo-dark.png')}}" alt="home" class="light-logo" />
                        </b><span class="hidden-xs">
                            <!--This is dark logo text-->
                            <img src="{{asset('admin_asset/assets/plugins/images/eliteadmin-text.png') }}" alt="home" class="dark-logo" />
                            <!--This is light logo text-->
                            <img src="{{asset('admin_asset/assets/plugins/images/eliteadmin-text-dark.png') }}" alt="home" class="light-logo" />
                        </span></a></div>
                <ul class="nav navbar-top-links navbar-left hidden-xs">
                    <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>

                </ul>

            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
        <!-- Left navbar-header -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse slimscrollsidebar">
                <div class="user-profile">
                    <div class="dropdown user-pro-body">
                        <div>
                            @if(@Auth::user()->profile_image)

                            <img src="{{ asset('storage/'.str_replace('public/','',Auth::user()->profile_image)) }}" alt="user-img" class="img-circle">
                            @else
                            <img src="{{ asset('admin_asset/assets/plugins/images/user_avatar.png') }}" alt="user-img" class="img-circle">
                            @endif
                        </div>
                        <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ @Auth::user()->first_name }} {{ @Auth::user()->last_name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu animated flipInY">
                            <li><a href="{{ route('admin.profile') }}"><i class="ti-wallet"></i> Profile</a></li>
                            <li><a href="{{ route('admin.changepassword') }}"><i class="ti-wallet"></i> Change Password</a></li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: one;">
                                {{ csrf_field() }}
                                <input name="role_type" id="role_type" type="hidden" value="Admin" />
                            </form>
                            <li><a href="{{route('admin.logout')}}"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
                @php
                $current_route=Route::currentRouteName();
                @endphp
                <ul class="nav" id="side-menu">
                    {{-- dashboard --}}
                    @php
                    $current_route=Route::currentRouteName();
                    @endphp
                    <li> <a href="{{ route('admin.dashboard') }}" class="waves-effect @if($current_route=="admin.dashboard") active @endif"><i data-icon="a" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Dashboard</span></a> </li>

                    @if(auth()->user()->can('seo-setting-list') || auth()->user()->can('sem-setting-list') || auth()->user()->can('page-setting-list') || auth()->user()->can('general-setting-list') || auth()->user()->can('expertise-level-list') || auth()->user()->can('site-interest-list') || auth()->user()->can('activity-points-setting-list') || auth()->user()->can('point-ranking-setting-list'))
                    <li class="@if($current_route=="admin.seo" || $current_route=="admin.sem" || $current_route=="admin.setting" || $current_route=="admin.expertise_level" || $current_route=="admin.site_interest" || $current_route == 'admin.activity_points_setting' || $current_route == 'admin.point_ranking_setting') active @endif"> <a href="#" class="waves-effect @if($current_route==" admin.seo" || $current_route=="admin.sem" ) active @endif">
                            <i class="fa fa-gears"></i>
                            <span class="hide-menu"> Settings <span class="fa arrow"></span>
                            </span>
                        </a>
                        <ul class="nav nav-second-level">
                            @can('seo-setting-list')
                            <li> <a class="@if($current_route=="admin.seo") active @endif" href="{{ route('admin.seo') }}">SEO Setting</a> </li>
                            @endcan
                            @can('sem-setting-list')
                            <li> <a class="@if($current_route=="admin.sem") active @endif" href="{{ route('admin.sem') }}">SEM Setting</a> </li>
                            @endcan
                            @can('general-setting-list')
                            <li> <a class="@if($current_route=="admin.setting") active @endif" href="{{ route('admin.setting') }}">General Setting</a> </li>
                            @endcan

                            @can('expertise-level-list')
                            <li> <a class="@if($current_route=="admin.expertise_level") active @endif" href="{{ route('admin.expertise_level') }}">Expertise Level</a> </li>
                            @endcan

                            @can('site-interest-list')
                            <li> <a class="@if($current_route=="admin.site_interest") active @endif" href="{{ route('admin.site_interest') }}">Site Interest</a> </li>
                            @endcan
                            @can('activity-points-setting-list')
                            <li> <a class="@if($current_route=="admin.activity_points_setting") active @endif" href="{{ route('admin.activity_points_setting') }}">Activity Points Setting</a> </li>
                            @endcan
                            @can('point-ranking-setting-list')
                            <li> <a class="@if($current_route=="admin.point_ranking_setting") active @endif" href="{{ route('admin.point_ranking_setting') }}">Point Ranking Setting</a> </li>
                            @endcan
                        </ul>
                    </li>
                    @endif
                    
                    {{-- Home Page Setting  --}}

                    @if(auth()->user()->can('homepage-setting-list') || auth()->user()->can('page-setting-list'))
                    <li  class="@if($current_route=="admin.homepage_settings" || $current_route=="admin.enterprise_solutions" || $current_route=="admin.key_features" || $current_route=="admin.pages") active @endif"> <a href="#" class="waves-effect">
                            <i class="fa fa-gears"></i>
                            <span class="hide-menu">Page Settings<span class="fa arrow"></span>
                            </span>
                        </a>
                        <ul class="nav nav-second-level">
                            @can('homepage-setting-list')
                            <li> <a class="@if($current_route=="admin.homepage_settings") active @endif"  href="{{ route('admin.homepage_settings') }}">Home Page Settings</a> </li>

                            <li> <a class="@if($current_route=="admin.key_features") active @endif"  href="{{ route('admin.key_features') }}">Home Page Key Feature</a> </li>
                            @endcan

                            @can('page-setting-list')
                            <li> <a class="@if($current_route=="admin.pages") active @endif" href="{{ route('admin.pages') }}">General Page Setting</a> </li>
                            @endcan
                        </ul>
                    </li>
                    @endif


                    {{-- Email Format --}}
                    @can('email-format-list')
                    <li> <a href="{{ route('admin.email_format') }}" class="waves-effect @if($current_route=="admin.email_format") active @endif"><i class="fa fa-envelope"></i> <span class="hide-menu">Email Format</span></a> </li>
                    @endcan

                    {{-- Role Management --}}
                    @can('role-permission-list')
                    <li> <a href="{{ route('admin.list_roles') }}" class="waves-effect @if($current_route=="admin.list_roles") active @endif"><i class="fa fa-users" aria-hidden="true"></i> <span class="hide-menu">Role / Permissions</span></a> </li>
                    @endcan

                    {{--Admin User Management --}}
                    @can('admin-user-management-list')
                    <li> <a href="{{ route('admin.user') }}" class="waves-effect @if($current_route=="admin.user") active @endif"><i class="fa fa-users" aria-hidden="true"></i> <span class="hide-menu">Admin User Management</span></a> </li>
                    @endcan

                    {{-- Front User Management --}}
                    @can('front-user-management-list')
                    <li class="@if($current_route=="admin.get_normal_users" || $current_route=="admin.get_premium_user" || $current_route=="admin.get_business_user" || $current_route=="admin.get_sub_business_user" ) active @endif"> <a href="#" class="waves-effect"><i class="fa fa-users" aria-hidden="true"></i> <span class="hide-menu">Front User Management<span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a class="@if($current_route=="admin.get_normal_users")active @endif" href="{{route('admin.get_normal_users')}}">Normal Users</a> </li>
                            <li> <a class="@if($current_route=="admin.get_premium_user") active @endif" href="{{route('admin.get_premium_user')}}">Premium Users</a> </li>
                            <li> <a class="@if($current_route=="admin.get_business_user") active @endif" href="{{route('admin.get_business_user')}}">Business Users</a> </li>
                            <li> <a class="@if($current_route=="admin.get_sub_business_user") active @endif" href="{{route('admin.get_sub_business_user')}}">Sub-Business Users</a> </li>
                        </ul>
                    </li>
                    @endcan
                    {{-- Plan / Subscription Management --}}
                    @can('plan-subscription-list')
                    <li> <a href="{{ route('admin.plan_subscription') }}" class="waves-effect @if($current_route=="admin.plan_subscription") active @endif"><i class="fa fa-columns" aria-hidden="true"></i> <span class="hide-menu">Subscription Plan Management</span></a> </li>
                    @endcan

                    {{-- Admin Catalogue Management--}}
                    @can('admin-catalogue-list')
                    <li> <a href="{{ route('admin.catalogue') }}" class="waves-effect @if($current_route=="admin.catalogue") active @endif"><i class="fa fa-book" aria-hidden="true"></i> <span class="hide-menu">Admin Catalogue</span></a> </li>
                    @endcan

                    @if(auth()->user()->can('advertisement-category-list') || auth()->user()->can('advertisement-list'))
                    <li class="@if($current_route=="admin.campaign_group" || $current_route=="admin.advertisement_category" || $current_route=="admin.advertisement_report" ) active @endif"> <a href="#" class="waves-effect"><i class="fa fa-newspaper-o" aria-hidden="true"></i> <span class="hide-menu">Advertisement Management<span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            @can('advertisement-category-list')
                            <li> <a class="@if($current_route=="admin.advertisement_category") active @endif" href="{{route('admin.advertisement_category')}}">Advertisement Category</a> </li>
                            @endcan
                            @can('advertisement-list')
                            <li> <a class="@if($current_route=="admin.campaign_group") active @endif" href="{{route('admin.campaign_group')}}"> Campaign Group </a> </li>
                            @endcan
                            {{-- <li> <a class="@if($current_route=="admin.advertisement_report") active @endif" href="{{route('admin.advertisement_report')}}">Advertisement Report</a>
                    </li> --}}
                </ul>
                </li>
                @endif
                @if(auth()->user()->can('survey-list') || auth()->user()->can('survey-conducted-list') || auth()->user()->can('template-type-list') || auth()->user()->can('survey-template-list'))
                <li class="@if($current_route=="admin.survey" || $current_route=="admin.survey_conducted" || $current_route=="admin.template_type" || $current_route=="admin.survey_template" ) active @endif"> <a href="#" class="waves-effect"><i class="linea-icon linea-basic" data-icon=""></i> <span class="hide-menu">Survey Management<span class="fa arrow"></span></span></a>
                    <ul class="nav nav-second-level">
                        @can('survey-list')
                        <li> <a class="@if($current_route=="admin.survey") active @endif" href="{{route('admin.survey')}}">Survey</a> </li>
                        @endcan

                        @can('survey-conducted-list')
                        <li> <a class="@if($current_route=="admin.survey_conducted") active @endif" href="{{route('admin.survey_conducted')}}">Survey Conducted</a> </li>
                        @endcan

                        @can('template-category-list')
                        <li> <a class="@if($current_route=="admin.template_type") active @endif" href="{{route('admin.template_type')}}">Template Category</a> </li>
                        @endcan

                        @can('survey-template-list')
                        <li> <a class="@if($current_route=="admin.survey_template") active @endif" href="{{route('admin.survey_template')}}">Survey Template</a> </li>
                        @endcan
                    </ul>
                </li>
                @endif
                    <li class="@if($current_route=="admin.advertisement_content" || $current_route=="admin.contest_content" || $current_route=="admin.survey_content" || $current_route=="admin.survey_question_content" || $current_route=="admin.survey_term_condition_content" || $current_route=="admin.processed_content") active @endif"> <a href="#" class="waves-effect"><i class="fa fa-newspaper-o" aria-hidden="true"></i> <span class="hide-menu">Inappropriate Content<span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a class="@if($current_route=="admin.advertisement_content") active @endif" href="{{route('admin.advertisement_content')}}">Advertisement</a> </li>
                            <li> <a class="@if($current_route=="admin.contest_content") active @endif" href="{{route('admin.contest_content')}}"> Contest </a> </li>
                            <li> <a class="@if($current_route=="admin.survey_content") active @endif" href="{{route('admin.survey_content')}}"> Survey </a> </li>
                            <li> <a class="@if($current_route=="admin.survey_question_content") active @endif" href="{{route('admin.survey_question_content')}}"> Survey Question</a> </li>
                            <li> <a class="@if($current_route=="admin.survey_term_condition_content") active @endif" href="{{route('admin.survey_term_condition_content')}}"> Survey Term Condition </a> </li>
                            <li> <a class="@if($current_route=="admin.processed_content") active @endif" href="{{route('admin.processed_content')}}"> Flagged User List </a> </li>
                        </ul>
                    </li>
                {{-- Your Client--}}
                @can('client-list')
                <li> <a href="{{ route('admin.clients') }}" class="waves-effect @if($current_route=="admin.clients") active @endif"><i class="fa fa-book" aria-hidden="true"></i> <span class="hide-menu">Clients</span></a> </li>
                @endcan

                <!-- Quick poll -->
                @can('poll-list')
                <li> <a href="{{ route('admin.quick_polls') }}" class="waves-effect @if($current_route=="admin.quick_polls") active @endif"><i class="fa fa-th-list" aria-hidden="true"></i> <span class="hide-menu">Polls</span></a> </li>
                @endcan
                <!-- Quick poll -->


                @can('payment-transaction-list')
                <li> <a href="{{ route('admin.payment_transaction') }}" class="waves-effect @if($current_route=="admin.payment_transaction") active @endif"><i class="fa fa-history" aria-hidden="true"></i> <span class="hide-menu">Payment Transaction</span></a> </li>
                @endcan

                @can('wallet-request-list')
                <li> <a href="{{ route('admin.user_wallet_request') }}" class="waves-effect @if($current_route=="admin.user_wallet_request") active @endif"><i class="fa fa-money" aria-hidden="true"></i> <span class="hide-menu">User Wallet Request</span></a> </li>
                @endcan
                @can('contest-list')
                <li> <a href="{{ route('admin.contest') }}" class="waves-effect @if($current_route=="admin.contest") active @endif"><i class="fa fa-trophy" aria-hidden="true"></i> <span class="hide-menu">Contest Campaign</span></a> </li>
                @endcan
                {{-- User activity points --}}
                @can('activity-points-list')
                <li> <a href="{{ route('admin.activity_points') }}" class="waves-effect @if($current_route=="admin.activity_points") active @endif"><i class="fa fa-history" aria-hidden="true"></i> <span class="hide-menu">User Activity Points</span></a> </li>
                @endcan
                </ul>
            </div>
        </div>
        <!-- Left navbar-header end -->
        <!-- Page Content -->
        <div id="page-wrapper">
            @yield('content')
            <!-- /.container-fluid -->
            <footer class="footer text-center"> {{ date('Y') }} &copy; Developed By <a target="_blank" href="{{$setting_details['url']}}">{{$setting_details['develop_by']}}</a> </footer>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="{{asset('admin_asset/assets/plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{{asset('admin_asset/assets/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="{{asset('admin_asset/assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') }}"></script>
    <!--slimscroll JavaScript -->
    <script src="{{asset('admin_asset/assets/js/jquery.slimscroll.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{asset('admin_asset/assets/js/waves.js') }}"></script>
    <!--Counter js -->
    <script src="{{asset('admin_asset/assets/plugins/bower_components/waypoints/lib/jquery.waypoints.js') }}"></script>
    <script src="{{asset('admin_asset/assets/plugins/bower_components/counterup/jquery.counterup.min.js') }}"></script>
    <!--Image Popup js -->
    <script src="{{asset('admin_asset/assets/plugins/bower_components/Magnific-Popup-master/dist/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{asset('admin_asset/assets/plugins/bower_components/Magnific-Popup-master/dist/jquery.magnific-popup-init.js') }}"></script>
    <!--Morris JavaScript -->
    <script src="{{asset('admin_asset/assets/plugins/bower_components/raphael/raphael-min.js') }}"></script>
    <!--<script src="{{asset('assets/plugins/bower_components/morrisjs/morris.js') }}"></script>-->
    <!-- Custom Theme JavaScript -->
    <script src="{{asset('admin_asset/assets/js/custom.js') }}"></script>
    <!--<script src="{{asset('assets/js/dashboard1.js') }}"></script>-->
    <!-- Sparkline chart JavaScript -->
    <script src="{{asset('admin_asset/assets/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{asset('admin_asset/assets/plugins/bower_components/jquery-sparkline/jquery.charts-sparkline.js') }}"></script>
    <script src="{{asset('admin_asset/assets/plugins/bower_components/toast-master/js/jquery.toast.js') }}"></script>

    <!--Style Switcher -->
    <script src="{{asset('admin_asset/assets/plugins/bower_components/styleswitcher/jQuery.style.switcher.js') }}"></script>
    <script src="{{asset('admin_asset/assets/plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{asset('admin_asset/assets/js/validate.js') }}"></script>
    <script src="{{asset('admin_asset/assets/plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>

    <script src="{{asset('admin_asset/assets/plugins/bower_components/custom-select/custom-select.min.js') }}" type="text/javascript"></script>
    <script src="{{asset('admin_asset/assets/plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{asset('admin_asset/assets/plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{asset('admin_asset/assets/plugins/bower_components/moment/moment.js') }}"></script>
    <script src="{{asset('admin_asset/assets/plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{asset('admin_asset/assets/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
    <!-- Date Picker Plugin JavaScript -->
    <script src="{{asset('admin_asset/assets/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!-- Date range Plugin JavaScript -->
    <script src="{{asset('admin_asset/assets/plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{asset('admin_asset/assets/plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{asset('admin_asset/assets/plugins/bower_components/dropify/dist/js/dropify.min.js') }}"></script>

    <script src="{{asset('admin_asset/assets/plugins/bower_components/toast-master/js/jquery.toast.js') }}"></script>

    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <script type="text/javascript" src="{{asset('admin_asset/assets/plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{asset('admin_asset/assets/plugins/bower_components/html5-editor/wysihtml5-0.3.0.js') }}"></script>
    <script src="{{asset('admin_asset/assets/plugins/bower_components/html5-editor/bootstrap-wysihtml5.js') }}"></script>
    <script type="text/javascript" src="{{asset('admin_asset/assets/plugins/bower_components/fancybox/ekko-lightbox.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('admin_asset/assets/js/jquery.doubleScroll.js') }}"></script>
    @yield('script')
</body>

</html>