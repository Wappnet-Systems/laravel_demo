<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('front_asset/dashboard/assets/images/fevicon-icon.svg') }}" sizes="any"
        type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('front_asset/dashboard/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front_asset/dashboard/assets/css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('front_asset/dashboard/assets/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('front_asset/dashboard/assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front_asset/dashboard/assets/css/jquery-confirm.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front_asset/dashboard/assets/css/jquery.toast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front_asset/dashboard/assets/css/dashboard-style.css') }}">
    <link rel="stylesheet" href="{{ asset('front_asset/dashboard/assets/css/custom.css') }}">
    @stack('css')
    <style>
    .error {
        color: red;
    }

    table.dataTable thead th {
        white-space: nowrap
    }

    .topbar .serach-section.navbar-search {
        width: 30%;
        float: left;
        top: 20px;
        position: absolute;
        left: 315px;
        margin: 0 auto;
    }

    .pagination .page-item.active .page-link {
        z-index: 0 !important;
    }

    #user_cards {
        overflow-y: auto;
        overflow-x: hidden;
        max-height: 380px;
    }
    .topbar .serach-section.navbar-search {
        position: unset;
    }
    </style>
    <script src="{{ asset('front_asset/dashboard/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('front_asset/dashboard/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('front_asset/dashboard/assets/js/bootstrap.min.js') }}"></script>
</head>
@php
$current_route = Route::currentRouteName();
@endphp

<body class="dashboard-body-wrappper">
    <div id="preloader-active" style="display: block;">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="{{ asset('front_asset/dashboard/assets/images/logo_100x100.png') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Start main dashboard-wrapper section -->
    <div class="dashboard-wrapper" id="dashboard-wrapper">
        <!-- Start sidebar section -->
        <ul class="navbar-nav white-box-shadow sidebar accordion toggled" id="accordionSidebar" style="filter: unset;z-index:1;">
            <!-- Start Sidebar Toggler (Sidebar) -->
            <div class="sidebar-brand d-flex align-items-center justify-content-start">
                <a href="javascript:void(0);" id="sidebar-toggle">
                    <img class="img-fluid" src="{{ asset('front_asset/dashboard/assets/images/menu-line-32x24.svg') }}"
                        alt="menu-line">
                </a>
            </div>
            <!-- End Sidebar Toggler (Sidebar) -->
            <!-- Nav Item - Dashboard -->
            <div class="sidebar-custom-menu">
                @if (Auth::user()->profile_check <= 100)
                    <li class="nav-item @if($current_route == " front.dashboard") active @endif">
                        <a class="nav-link" href="{{ route('front.dashboard') }}">
                            <span class="custom-icon">
                                <img class="img-fluid"
                                    src="{{ asset('front_asset/dashboard/assets/images/icons/home-icon-32x32.svg') }}"
                                    alt="home">
                            </span>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('timeline', 'web') || !auth()->user()->hasRole('Sub-Business Users'))
                    <li class="nav-item @if($current_route == " front.timeline") active @endif">
                        <a href="{{ route('front.timeline') }}" class="nav-link">
                            <span class="custom-icon">
                                <img class="img-fluid"
                                    src="{{ asset('front_asset/dashboard/assets/images/icons/blog-post-32x32.svg') }}"
                                    alt="home">
                            </span>
                            <span class="nav-text">Timeline</span>
                        </a>
                    </li>
                @endif
                    {{-- <li class="nav-item ">
						<a class="nav-link" href="#">
							<span class="custom-icon">
								<img  class="img-fluid" src="{{asset('front_asset/dashboard/assets/images/icons/comment-icon-32x32.svg') }}"
                    alt="home">
                    </span>
                    <span class="nav-text">Comment</span>
                    </a>
                    </li> --}}
                @if (auth()->user()->hasPermissionTo('all_notification', 'web') || !auth()->user()->hasRole('Sub-Business Users'))
                    <li class="nav-item @if($current_route == " front.all_notification") active @endif">
                        <a class="nav-link" href="{{ route('front.all_notification') }}">
                            <span class="custom-icon">
                                <img class="img-fluid"
                                    src="{{ asset('front_asset/dashboard/assets/images/icons/bell-icon-32x32.svg') }}"
                                    alt="home">
                            </span>
                            <span class="nav-text">Notification</span>
                        </a>
                    </li>
                @endif
                {{-- @if (auth()->user()->hasPermissionTo('timeline', 'web') || !auth()->user()->hasRole('Sub-Business Users')) --}}
                    <li class="nav-item @if($current_route == " front.create_survey" ||
                        $current_route=="front.survey_template" || $current_route=="front.add_survey" ||
                        $current_route=="front.survey_list" || $current_route=="front.user_survey_edit" ||
                        $current_route=="front.survey_winner_declare" || $current_route=="front.fill_survey_list" ||
                        $current_route=="front.show_fill_survey_info" ) active @endif">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSurvey"
                            aria-expanded="true" aria-controls="collapseSurvey">
                            <span class="custom-icon">
                                <img class="img-fluid"
                                    src="{{ asset('front_asset/dashboard/assets/images/icons/survey.svg') }}"
                                    alt="home">
                            </span>
                            <span class="nav-text">Survey <i class="fa fa-angle-down submenu_icon"
                                    aria-hidden="true"></i></span>
                        </a>
                        <div id="collapseSurvey" class="collapse submenu" aria-labelledby="headingTwo"
                            data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                @if ((auth()->user()->hasPermissionTo('create_survey', 'web') || auth()->user()->hasPermissionTo('add_survey', 'web') || auth()->user()->hasPermissionTo('survey_template', 'web')) || !auth()->user()->hasRole('Sub-Business Users'))
                                    <a class="collapse-item @if($current_route == " front.create_survey" ||
                                        $current_route=="front.survey_template" || $current_route=="front.add_survey" )
                                        active @endif" href="{{ route('front.create_survey') }}">Create Survey</a>
                                @endif
                                @if ((auth()->user()->hasPermissionTo('survey_list', 'web') || auth()->user()->hasPermissionTo('survey_winner_declare', 'web')) || !auth()->user()->hasRole('Sub-Business Users'))
                                    <a class="collapse-item @if($current_route == " front.survey_list" ||
                                        $current_route=="front.survey_list" || $current_route=="front.survey_winner_declare"
                                    ) active @endif" href="{{ route('front.survey_list') }}">Survey List</a>
                                @endif
                                @if ((auth()->user()->hasPermissionTo('fill_survey_list', 'web') || auth()->user()->hasPermissionTo('show_fill_survey_info', 'web')) || !auth()->user()->hasRole('Sub-Business Users'))
                                    <a class="collapse-item @if($current_route == " front.fill_survey_list" ||
                                    $current_route=='front.show_fill_survey_info' ) active @endif"
                                    href="{{ route('front.fill_survey_list') }}">Your Filled Survey List</a>
                                @endif
                                @if (auth()->user()->hasPermissionTo('filled_draft_list', 'web') || !auth()->user()->hasRole('Sub-Business Users'))
                                    <a class="collapse-item @if($current_route == " front.filled_draft_list") active @endif"
                                    href="{{ route('front.filled_draft_list') }}">Survey Filled Draft</a>
                                @endif
                            </div>
                        </div>
                    </li>
                {{-- @endif --}}
                @if ((auth()->user()->hasPermissionTo('favorite', 'web') || auth()->user()->hasPermissionTo('favorite_contest', 'web')) || !auth()->user()->hasRole('Sub-Business Users'))
                    <li class="nav-item @if($current_route == " front.favorite" ||
                        $current_route=="front.favorite_contest" ) active @endif">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFavorite"
                            aria-expanded="true" aria-controls="collapseFavorite">
                            <span class="custom-icon">
                                <img class="img-fluid"
                                    src="{{ asset('front_asset/dashboard/assets/images/timeline/heart-icon.svg') }}"
                                    alt="home">
                            </span>
                            <span class="nav-text">Favorite <i class="fa fa-angle-down submenu_icon"
                                    aria-hidden="true"></i></span>
                        </a>
                        <div id="collapseFavorite" class="collapse submenu" aria-labelledby="headingTwo"
                            data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                @if (auth()->user()->hasPermissionTo('favorite', 'web') || !auth()->user()->hasRole('Sub-Business Users'))
                                    <a class="collapse-item @if($current_route == " front.favorite") active @endif" href="{{ route('front.favorite') }}">Survey</a>
                                @endif
                                @if (auth()->user()->hasPermissionTo('favorite_contest', 'web') || !auth()->user()->hasRole('Sub-Business Users'))
                                    <a class="collapse-item @if($current_route == " front.favorite_contest") active @endif" href="{{ route('front.favorite_contest') }}">Contest</a>
                                @endif
                            </div>
                        </div>
                    </li>
                @endif
                {{-- @if (auth()->user()->hasPermissionTo('transaction_list', 'web') || !auth()->user()->hasRole('Sub-Business Users')) --}}
                    <li class="nav-item @if($current_route == " front.contest" || $current_route=='front.add_contest' ||
                        $current_route=='front.edit_contest' || $current_route=="front.your_participate_contest" )
                        active @endif">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseContest"
                            aria-expanded="true" aria-controls="collapseContest">
                            <span class="custom-icon">
                                <img class="img-fluid"
                                    src="{{ asset('front_asset/dashboard/assets/images/icons/contest.svg') }}"
                                    alt="home">
                            </span>
                            <span class="nav-text">Contest Campaign <i class="fa fa-angle-down submenu_icon"
                                    aria-hidden="true"></i></span>
                        </a>
                        <div id="collapseContest" class="collapse submenu" aria-labelledby="headingTwo"
                            data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                @if ((auth()->user()->hasPermissionTo('contest', 'web') || auth()->user()->hasPermissionTo('add_contest', 'web') || auth()->user()->hasPermissionTo('edit_contest', 'web')) || !auth()->user()->hasRole('Sub-Business Users'))
                                    <a class="collapse-item @if($current_route == " front.contest" ||
                                    $current_route=='front.add_contest' || $current_route=='front.edit_contest' ) active
                                    @endif" href="{{ route('front.contest') }}">Contest</a>
                                @endif
                                @if ((auth()->user()->hasPermissionTo('your_participate_contest', 'web')) || !auth()->user()->hasRole('Sub-Business Users'))
                                    <a class="collapse-item @if($current_route == " front.your_participate_contest") active
                                    @endif" href="{{ route('front.your_participate_contest') }}">Your Participle
                                    Contest</a>
                                @endif
                            </div>
                        </div>
                    </li>
                {{-- @endif --}}
                @if(auth()->user()->hasRole('Normal User'))
                    <li class="nav-item @if($current_route == " front.friend_list") active @endif">
                        <a href="{{ route('front.friend_list') }}" class="nav-link">
                            <span class="custom-icon">
                                <img class="img-fluid"
                                    src="{{ asset('front_asset/dashboard/assets/images/users/add-group.svg') }}"
                                    alt="home">
                            </span>
                            <span class="nav-text">Friends</span>
                        </a>
                    </li>
                @elseif (auth()->user()->hasRole('Business Users'))
                    <li class="nav-item @if($current_route == " front.followers.get" ||
                        $current_route=='front.followers.boards.get' ) active @endif">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFollowers"
                            aria-expanded="true" aria-controls="collapseContest">
                            <span class="custom-icon">
                                <img class="img-fluid"
                                    src="{{ asset('front_asset/dashboard/assets/images/users/add-group.svg') }}"
                                    alt="home">
                            </span>
                            <span class="nav-text">Manage Followers<i class="fa fa-angle-down submenu_icon"
                                    aria-hidden="true"></i></span>
                        </a>
                        <div id="collapseFollowers" class="collapse submenu" aria-labelledby="collapseFollowers"
                            data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item @if($current_route == " front.followers.get") active @endif"
                                    href="{{ route('front.followers.get') }}">Followers</a>
                                <a class="collapse-item @if($current_route == " front.followers.boards.get") active
                                    @endif" href="{{ route('front.followers.boards.get') }}">Board Settings</a>
                            </div>
                        </div>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('transaction_list', 'web') || !auth()->user()->hasRole('Sub-Business Users'))
                    <li class="nav-item @if($current_route == " front.transaction_list") active @endif">
                        <a href="{{ route('front.transaction_list') }}" class="nav-link">
                            <span class="custom-icon">
                                <img class="img-fluid"
                                    src="{{ asset('front_asset/dashboard/assets/images/icons/analytics.svg') }}"
                                    alt="home">
                            </span>
                            <span class="nav-text">Transactions</span>
                        </a>
                    </li>
                @endif
                {{-- @if (Auth::user()->roles[0]['name'] == "Business Users" && Auth::user()->business_account_approval == "1") --}}
                {{-- //11-11-21 Change --}}
                @if (!auth()->user()->hasRole('Normal User') && (auth()->user()->hasPermissionTo('campaigns', 'web') || !auth()->user()->hasRole('Sub-Business Users')))
                    <li class="nav-item @if($current_route == " front.campaigns" ||
                        $current_route=='front.add_advertisement' || $current_route=='front.edit_advertisement' ||
                        $current_route=='front.analyze_advertisement' ) active @endif">
                        <a href="{{ route('front.campaigns') }}" class="nav-link">
                            <span class="custom-icon">
                                {{-- <i class="fa fa-newspaper-o fa-lg txt-gold pr-15"></i> --}}
                                <img class="img-fluid"
                                    src="{{ asset('front_asset/dashboard/assets/images/icons/advertisement.svg') }}"
                                    alt="home">
                            </span>
                            <span class="nav-text">Advertisement</span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->hasRole('Business Users'))
                    <li class="nav-item @if($current_route == "front.business-user.get" ||
                        $current_route == 'front.business-user.add' || $current_route == 'front.business-user.edit') active @endif">
                        <a href="{{ route('front.business-user.get') }}" class="nav-link">
                            <span class="custom-icon">
                                <img class="img-fluid" src="{{ asset('front_asset/dashboard/assets/images/icons/business-users.svg') }}" alt="home">
                            </span>
                            <span class="nav-text">Business Sub Admins</span>
                        </a>
                    </li>
                @endif
            </div>
        </ul>
        <!-- End sidebar section -->
        <!-- Start Content Main Section Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Start top bar section -->
                <nav
                    class="navbar navbar-expand navbar-light bg-white topbar static-top low-box-shadow d-flex justify-content-between pt-0 pb-0" style="filter:unset">
                    <!-- Start Sidebar Toggler (Sidebar) -->
                    <!-- Start Responsive sidebar -->
					<div class="position-relative">
                        <ul class="navbar-nav accordion d-none toggled" id="accordionSidebar2"
                            data-target="#responsivesidebar" aria-expanded="true" aria-controls="responsivesidebar">
                            <div class="sidebar-brand d-flex align-items-center responsive-toggle-button justify-content-start">
                                <a href="javascript:void(0);" id="sidebar-toggle">
                                    <img class="img-fluid"
                                        src="{{ asset('front_asset/dashboard/assets/images/menu-line-32x24.svg') }}"
                                        alt="menu-line">
                                </a>
                            </div>
                            <!-- End Sidebar Toggler (Sidebar) -->
                        
                                <div id="responsivesidebar" class="sidebar-custom-menu-toggle d-none white-box-shadow sidebar sidebar-custom-menu"
                                    data-parent="#accordionSidebar2">
                                    @if (Auth::user()->profile_check <= 100) <li class="nav-item @if($current_route == "
                                        front.dashboard") active @endif">
                                        <a class="nav-link" href="{{ route('front.dashboard') }}">
                                            <span class="custom-icon">
                                                <img class="img-fluid"
                                                    src="{{ asset('front_asset/dashboard/assets/images/icons/home-icon-32x32.svg') }}"
                                                    alt="home">
                                            </span>
                                            <span class="nav-text">Dashboard</span>
                                        </a>
                                        </li>
                                        @endif
                                        <li class="nav-item @if($current_route == " front.timeline") active @endif">
                                            <a href="{{ route('front.timeline') }}" class="nav-link">
                                                <span class="custom-icon">
                                                    <img class="img-fluid"
                                                        src="{{ asset('front_asset/dashboard/assets/images/icons/blog-post-32x32.svg') }}"
                                                        alt="home">
                                                </span>
                                                <span class="nav-text">Timeline</span>
                                            </a>
                                        </li>
                                        {{-- <li class="nav-item ">
                                <a class="nav-link" href="#">
                                    <span class="custom-icon">
                                        <img  class="img-fluid" src="{{asset('front_asset/dashboard/assets/images/icons/comment-icon-32x32.svg') }}"
                                        alt="home">
                                        </span>
                                        <span class="nav-text">Comment</span>
                                        </a>
                                        </li> --}}
                                        <li class="nav-item @if($current_route == " front.all_notification") active @endif">
                                            <a class="nav-link" href="{{ route('front.all_notification') }}">
                                                <span class="custom-icon">
                                                    <img class="img-fluid"
                                                        src="{{ asset('front_asset/dashboard/assets/images/icons/bell-icon-32x32.svg') }}"
                                                        alt="home">
                                                </span>
                                                <span class="nav-text">Notification</span>
                                            </a>
                                        </li>
                                        <li class="nav-item @if($current_route == " front.create_survey" ||
                                            $current_route=="front.survey_template" || $current_route=="front.add_survey" ||
                                            $current_route=="front.survey_list" || $current_route=="front.user_survey_edit" ||
                                            $current_route=="front.survey_winner_declare" ||
                                            $current_route=="front.fill_survey_list" ||
                                            $current_route=="front.show_fill_survey_info" ) active @endif">
                                            <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                                data-target="#collapseSurvey" aria-expanded="true"
                                                aria-controls="collapseSurvey">
                                                <span class="custom-icon">
                                                    <img class="img-fluid"
                                                        src="{{ asset('front_asset/dashboard/assets/images/icons/survey.svg') }}"
                                                        alt="home">
                                                </span>
                                                <span class="nav-text">Survey <i class="fa fa-angle-down submenu_icon"
                                                        aria-hidden="true"></i></span>
                                            </a>
                                            <div id="collapseSurvey" class="collapse submenu" aria-labelledby="headingTwo"
                                                data-parent="#accordionSidebar2">
                                                <div class="bg-white py-2 collapse-inner rounded">
                                                    <a class="collapse-item @if($current_route == " front.create_survey" ||
                                                        $current_route=="front.survey_template" ||
                                                        $current_route=="front.add_survey" ) active @endif"
                                                        href="{{ route('front.create_survey') }}">Create Survey</a>
                                                    <a class="collapse-item @if($current_route == " front.survey_list" ||
                                                        $current_route=="front.survey_list" ||
                                                        $current_route=="front.survey_winner_declare" ) active @endif"
                                                        href="{{ route('front.survey_list') }}">Survey List</a>
                                                    <a class="collapse-item @if($current_route == " front.fill_survey_list" ||
                                                        $current_route=='front.show_fill_survey_info' ) active @endif"
                                                        href="{{ route('front.fill_survey_list') }}">Your Filled Survey List</a>
                                                    <a class="collapse-item @if($current_route == " front.filled_draft_list")
                                                        active @endif" href="{{ route('front.filled_draft_list') }}">Survey
                                                        Filled Draft</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="nav-item @if($current_route == " front.favorite" ||
                                            $current_route=="front.favorite_contest" ) active @endif">
                                            <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                                data-target="#collapseFavorite" aria-expanded="true"
                                                aria-controls="collapseFavorite">
                                                <span class="custom-icon">
                                                    <img class="img-fluid"
                                                        src="{{ asset('front_asset/dashboard/assets/images/timeline/heart-icon.svg') }}"
                                                        alt="home">
                                                </span>
                                                <span class="nav-text">Favorite <i class="fa fa-angle-down submenu_icon"
                                                        aria-hidden="true"></i></span>
                                            </a>
                                            <div id="collapseFavorite" class="collapse submenu" aria-labelledby="headingTwo"
                                                data-parent="#accordionSidebar2">
                                                <div class="bg-white py-2 collapse-inner rounded">
                                                    <a class="collapse-item @if($current_route == " front.favorite") active
                                                        @endif" href="{{ route('front.favorite') }}">Survey</a>
                                                    <a class="collapse-item @if($current_route == " front.favorite_contest")
                                                        active @endif" href="{{ route('front.favorite_contest') }}">Contest</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="nav-item @if($current_route == " front.contest" ||
                                            $current_route=='front.add_contest' || $current_route=='front.edit_contest' ||
                                            $current_route=="front.your_participate_contest" ) active @endif">
                                            <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                                data-target="#collapseContest" aria-expanded="true"
                                                aria-controls="collapseContest">
                                                <span class="custom-icon">
                                                    <img class="img-fluid"
                                                        src="{{ asset('front_asset/dashboard/assets/images/icons/contest.svg') }}"
                                                        alt="home">
                                                </span>
                                                <span class="nav-text">Contest Campaign <i class="fa fa-angle-down submenu_icon"
                                                        aria-hidden="true"></i></span>
                                            </a>
                                            <div id="collapseContest" class="collapse submenu" aria-labelledby="headingTwo"
                                                data-parent="#accordionSidebar2">
                                                <div class="bg-white py-2 collapse-inner rounded">
                                                    <a class="collapse-item @if($current_route == " front.contest" ||
                                                        $current_route=='front.add_contest' ||
                                                        $current_route=='front.edit_contest' ) active @endif"
                                                        href="{{ route('front.contest') }}">Contest</a>
                                                    <a class="collapse-item @if($current_route == "
                                                        front.your_participate_contest") active @endif"
                                                        href="{{ route('front.your_participate_contest') }}">Your Participle
                                                        Contest</a>
                                                </div>
                                            </div>
                                        </li>
                                        @if(Auth::user()->roles[0]['id'] == 4)
                                        <li class="nav-item @if($current_route == " front.friend_list") active @endif">
                                            <a href="{{ route('front.friend_list') }}" class="nav-link">
                                                <span class="custom-icon">
                                                    <img class="img-fluid"
                                                        src="{{ asset('front_asset/dashboard/assets/images/users/add-group.svg') }}"
                                                        alt="home">
                                                </span>
                                                <span class="nav-text">Friends</span>
                                            </a>
                                        </li>
                                        @else
                                        <li class="nav-item @if($current_route == " front.followers.get" ||
                                            $current_route=='front.followers.boards.get' ) active @endif">
                                            <a class="nav-link collapsed" href="#" data-toggle="collapse"
                                                data-target="#collapseFollowers" aria-expanded="true"
                                                aria-controls="collapseContest">
                                                <span class="custom-icon">
                                                    <img class="img-fluid"
                                                        src="{{ asset('front_asset/dashboard/assets/images/users/add-group.svg') }}"
                                                        alt="home">
                                                </span>
                                                <span class="nav-text">Manage Followers<i class="fa fa-angle-down submenu_icon"
                                                        aria-hidden="true"></i></span>
                                            </a>
                                            <div id="collapseFollowers" class="collapse submenu"
                                                aria-labelledby="collapseFollowers" data-parent="#accordionSidebar2">
                                                <div class="bg-white py-2 collapse-inner rounded">
                                                    <a class="collapse-item @if($current_route == " front.followers.get") active
                                                        @endif" href="{{ route('front.followers.get') }}">Followers</a>
                                                    <a class="collapse-item @if($current_route == " front.followers.boards.get")
                                                        active @endif" href="{{ route('front.followers.boards.get') }}">Board
                                                        Settings</a>
                                                </div>
                                            </div>
                                        </li>
                                        @endif
                                        <li class="nav-item @if($current_route == " front.transaction_list") active @endif">
                                            <a href="{{ route('front.transaction_list') }}" class="nav-link">
                                                <span class="custom-icon">
                                                    <img class="img-fluid"
                                                        src="{{ asset('front_asset/dashboard/assets/images/icons/analytics.svg') }}"
                                                        alt="home">
                                                </span>
                                                <span class="nav-text">Transactions</span>
                                            </a>
                                        </li>
                                        {{-- @if (Auth::user()->roles[0]['name'] == "Business Users" && Auth::user()->business_account_approval == "1") --}}
                                        {{-- //11-11-21 Change --}}
                                        @if (Auth::user()->roles[0]['name'] != "Normal User")
                                        <li class="nav-item @if($current_route == " front.campaigns" ||
                                            $current_route=='front.add_advertisement' ||
                                            $current_route=='front.edit_advertisement' ||
                                            $current_route=='front.analyze_advertisement' ) active @endif">
                                            <a href="{{ route('front.campaigns') }}" class="nav-link">
                                                <span class="custom-icon">
                                                    {{-- <i class="fa fa-newspaper-o fa-lg txt-gold pr-15"></i> --}}
                                                    <img class="img-fluid"
                                                        src="{{ asset('front_asset/dashboard/assets/images/icons/advertisement.svg') }}"
                                                        alt="home">
                                                </span>
                                                <span class="nav-text">Advertisement</span>
                                            </a>
                                        </li>
                                        @endif
                                </div>
                        </ul>
                    </div>
                      <!-- End Responsive sidebar -->
					<div class="logo-section">
                            <img class="img-fluid"
                                src="{{asset('front_asset/dashboard/assets/images/logo_64x64.png') }}" alt="logo">
                    </div>
                    <div class="serach-section d-none d-lg-inline-block form-inline navbar-search">
                        <form class="common-form topbar-serach">
                            <div class="form-group">
                                <input type="text" class="form-control input-serach-control" id="search_all"
                                    placeholder="SEARCH">
                                <i class="fa fa-search search-icon" aria-hidden="true"></i>
                            </div>
                        </form>
                        <div id="search_data" style=""></div>
                    </div>
                    <div class="right-topbar-section navbar-nav d-flex-cut-center">
                        {{-- @if ( $current_route != "front.create_survey")
								<li class="pr-10">
									<a href="{{route('front.create_survey')}}" class="btn btn-gold w-100 btn-create-survey">
                        <span class="custom-icon">
                            <img class="img-fluid filter-white"
                                src="{{asset('front_asset/dashboard/assets/images/icons/edit-note-icon-32x32.svg') }}"
                                alt="home">
                        </span>
                        Create Survey
                        </a>
                        </li>
                        @endif --}}
                        {{-- <li class="nav-item dropdown no-arrow mx-1">
								<a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fa fa-star txt-dark-gray"></i>
								</a>
								<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
									<h6 class="dropdown-header txt-uppercase">Important Notification</h6>
									<a class="dropdown-item d-flex align-items-center" href="#">
										<div class="mr-3">
											<div class="icon-circle bg-gold">
											<i class="fa fa-file-text text-white"></i>
											</div>
										</div>
										<div>
											<div class="small text-gray-500">December 12, 2019</div>
											<span class="font-weight-bold">A new monthly report is ready to download!</span>
										</div>
									</a>
									<a class="dropdown-item d-flex align-items-center" href="#">
										<div class="mr-3">
											<div class="icon-circle bg-gold">
												<i class="fa fa-credit-card text-white"></i>
											</div>
										</div>
										<div>
											<div class="small text-gray-500">December 7, 2019</div>
											$290.29 has been deposited into your account!
										</div>
									</a>
									<a class="dropdown-item d-flex align-items-center" href="#">
										<div class="mr-3">
											<div class="icon-circle bg-gold">
												<i class="fa fa-exclamation-triangle text-white"></i>
											</div>
										</div>
										<div>
											<div class="small text-gray-500">December 2, 2019</div>
											Spending Alert: We've noticed unusually high spending for your account.
										</div>
									</a>
									<a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
								</div>
							</li> --}}
                        @if(auth()->user()->is_free_subscription)
                            <li class="nav-item dropdown no-arrow d-flex align-items-center">
                                <a href="{{ route('front.account') }}" style="cursor: pointer">
                                    @if(isset($current_rank->full_icon_path) && !empty($current_rank->full_icon_path))
                                        <span class="">
                                            <img src="{{ $current_rank->full_icon_path }}" width="40px">
                                        </span>
                                    @endif
                                    <span class="ml-2" title="Total Points Earned">
                                        {{ $total_point }} / {{ !empty($current_rank->end_point) ? $current_rank->end_point : 0 }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="javascript:void(0)" onclick="clear_notification()"
                                id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fa fa-bell txt-dark-gray"></i>
                                <span class="badge badge-danger badge-counter">0</span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header txt-uppercase">Notification</h6>
                                <div id="last_notification"></div>
                                <a class="dropdown-item text-center small text-gray-500"
                                    href="{{ route('front.all_notification') }}">Show All</a>
                            </div>
                        </li>
                        <div class="circle-icon pl-15">
                            @if(Auth::user()->profile_image)
                            @php
                            $images =
                            app(App\Lib\UploadFile::class)->get_s3_file_path("profile_image",Auth::user()->profile_image);
                            @endphp
                            <img class="img-fluid img-circle" src="{{ $images }}" alt="user"
                                style="width: 40px;height: 40px;">
                            @else
                            <img class="img-fluid img-circle"
                                src="{{ asset('front_asset/dashboard/assets/images/default-avatar.png') }}" alt="user"
                                style="width: 40px;height: 40px;">
                            @endif
                        </div>
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle txt-light-black font-medium" href="#" id="alertsDropdown"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{Auth::user()->first_name." ".Auth::user()->last_name}} <i class="fa fa-angle-down"
                                    aria-hidden="true"></i>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in width-custom"
                                aria-labelledby="alertsDropdown">
                                {{-- <a class="dropdown-item d-flex align-items-center justify-content-left" href="#">
                                    <i class="fa fa-cog fa-lg txt-gold pr-15"></i> <span>Settings</span>
                                </a> --}}
                                @if (!auth()->user()->hasRole('Sub-Business Users'))
                                    <a class="dropdown-item d-flex align-items-center justify-content-left"
                                        href="{{ route('front.card') }}">
                                        <i class="fa fa-credit-card fa-lg txt-gold pr-15"></i> <span>My cards</span>
                                    </a>
                                @endif
                                @if (!auth()->user()->hasRole('Sub-Business Users'))
                                    <a class="dropdown-item d-flex align-items-center justify-content-left"
                                        href="{{ route('front.account') }}">
                                        <i class="fa fa-user fa-lg txt-gold pr-15"></i> <span>My Account</span>
                                    </a>
                                @endif
                                {{-- <a class="dropdown-item d-flex align-items-center justify-content-left" href="{{ route('front.chat') }}">
                                <i class="fa fa-comments fa-lg txt-gold pr-15"></i> <span>Chat</span>
                                </a> --}}
                                {{-- {{ route('front.logout') }} --}}
                                <a class="dropdown-item d-flex align-items-center justify-content-left"
                                    href="{{ route('front.logout') }}">
                                    <i class="fa fa-sign-out fa-lg txt-gold pr-15"></i> <span>Logout</span>
                                </a>
                            </div>
                        </li>
                    </div>
                </nav>
                <!-- End top bar section -->
                <!-- Start Page Content -->
                @yield('content')
                <!-- End Page Content -->
            </div>
            {{-- User Chat Start  --}}
            <section class="chatbox chatbox--tray chatbox--empty">
                <div class="main__container">
                    {{--  chat__mask--minimize --}}
                    {{-- <div class="chat__mask">
							<div class="chat__container">
								<div class="conversation__view">
									<header class="conversation__view__header">
										<div class="conversation__user__info">
											<span class="conversation__user__status"></span>
											<span class="conversation__user__name">John</span>
										</div>
										<div class="conversation__view__actions">
											<button class="conversation__actions__videocall" title="Start videocall">
												<svg viewBox="0 0 24 22">
													<path d="M16,16c0,1.1-0.9,2-2,2H2c-1.1,0-2-0.9-2-2V8c0-1.1,0.9-2,2-2h12c1.1,0,2,0.9,2,2V16z M24,6l-6,4.2v3.6l6,4.2V6z"/>
												</svg>
											</button>
											<button class="conversation__actions__close" title="Close conversation">
												<svg viewBox="0 0 22 22">
													<path d="M23,20.2L14.8,12L23,3.8L20.2,1L12,9.2L3.8,1L1,3.8L9.2,12L1,20.2L3.8,23l8.2-8.2l8.2,8.2L23,20.2z"/>
												</svg>
											</button>
										</div>
									</header>
									<div class="conversation__view__body">
										<div class="conversation__view__bubbles">
											<p class="chat__left__bubble">How are you?</p>
										</div>
									</div>
									<footer class="conversation__view__write">
										<div class="fab_field">
											<a id="fab_camera" class="fab_icon"><i class="fa fa-camera"></i></a>
											<a id="fab_send" class="fab_icon chat__conversation__send"><i class="fa fa-paper-plane"></i></a>
											<textarea id="chat__input" name="chat_message" placeholder="Send a message" class="chat_field chat_message"></textarea>
										</div>
									</footer>
								</div>
							</div>
						</div> --}}
                </div>
            </section>
            @if (Auth::user()->roles[0]['name'] != "Premium Users")
            <div class="fabs">
                <div class="chat">
                    <div class="chat_body chat_login" style="max-height: 300px;overflow-y: scroll;">
                        <h2>contacts</h2>
                        <ul id="render_chat_friends">
                            <li>
                                <a href="#">Not Found</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <a id="contact" class="fab"><i class="contact fa fa-comment"></i></a>
            </div>
            @endif
            {{-- User Chat End  --}}
            <!-- Start Footer-->
            <footer id="footer" class="fixed-bottom">
                <div class="container-fluid copyright-wrapper bg-gray pt-20 pb-15">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-7 col-lg-7">
                                <div class="copyright">
                                    Copyright <i class="fa fa-copyright"></i> <a
                                        href="{{ $setting_details['url'] }}">{{ $setting_details['develop_by'] }}</a> |
                                    {{ date('Y') }} All rights reserved.
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-5 col-lg-5">
                                <ul class="social-media-wrappper">
                                    @if ($sem['facebook'])
                                    <li>
                                        <a href="{{ $sem['facebook'] }}" class="social-media">
                                            <i class="fa fa-facebook" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    @endif
                                    @if ($sem['twiter'])
                                    <li>
                                        <a href="{{ $sem['twiter'] }}" class="social-media">
                                            <i class="fa fa-twitter" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    @endif
                                    @if ($sem['skype'])
                                    <li>
                                        <a href="{{ $sem['skype'] }}" class="social-media">
                                            <i class="fa fa-skype" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    @endif
                                    @if ($sem['whats_app'])
                                    <li>
                                        <a href="{{ $sem['whats_app'] }}" class="social-media">
                                            <i class="fa fa-whatsapp" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    @endif
                                    @if ($sem['insta'])
                                    <li>
                                        <a href="{{ $sem['insta'] }}" class="social-media">
                                            <i class="fa fa-instagram" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    @endif
                                    @if ($sem['linkedin'])
                                    <li>
                                        <a href="{{ $sem['linkedin'] }}" class="social-media">
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
            <!-- End Footer-->
        </div>
        <!-- End Content Main Section Wrapper -->
    </div>
    <!-- End main dashboard-wrapper section -->
    <!-- Scroll Top -->
    <a id="back-to-top" href="#" class="back-to-top" role="button"><i class="fa fa-arrow-up"></i></a>
    <script src="{{ asset('front_asset/dashboard/assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('front_asset/dashboard/assets/js/scroll-top.js') }}"></script>
    <script src="{{ asset('front_asset/dashboard/assets/js/circle-progress.js') }}"></script>
    <script src="{{ asset('front_asset/dashboard/assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('front_asset/dashboard/assets/js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('front_asset/dashboard/assets/js/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('front_asset/dashboard/assets/js/jquery-confirm.min.js') }}"></script>
    <script src="{{ asset('front_asset/dashboard/assets/js/socket.io.min.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/3.1.2/socket.io.min.js" integrity="sha512-fANg+hKlIqBdTzgYBT8eFIlZgKYTLij0S7Afvda/rw/Rm33I9+74HSdR1Urz2zGgCSZiQweMin46+l1obnoLWQ==" crossorigin="anonymous"></script> --}}
    <script src="{{ asset('front_asset/dashboard/assets/js/pusher.min.js') }}"></script>
    {{-- <script src="https://js.pusher.com/7.0/pusher.min.js"></script> --}}
    <script src="{{ asset('front_asset/dashboard/assets/js/custom-circle-pgrogress-script.js') }}" charset="utf-8" async
        defer></script>
    {{-- <script src="{{asset('front_asset/dashboard/assets/js/custom-dashboar-script.js') }}" charset="utf-8" async
    defer></script> --}}
    {{-- <script src="{{asset('front_asset/dashboard/assets/js/custom-muti-step-form-script.js') }}" charset="utf-8"
    async defer></script> --}}
    <script src="{{ asset('front_asset/dashboard/assets/js/custom-equalheight-script.js') }}" charset="utf-8"></script>
    @yield('script')
    <script>
    var start = 0;

    function removeSearchList() {
        $('#search_data').hide();
    }

    $(document).ready(function() {
        $('#search_all').val("");

        if ("{{Auth::check()}}") {
            notification_count();
        }

        // var socket = io();
        // const socket = io.connect("http://127.0.0.1:8890");

        /* io.on('connection', (socket) => {
        	console.log('a user connected');
        	socket.on('disconnect', () => {
        		console.log('user disconnected');
        	});
        }); */
    });

    if ("{{Auth::check()}}") {
        setInterval(notification_count, 5000);
    }

    function notification_count(type = "") {
        $.ajax({
            type: "POST",
            url: "{{ route('front.notification_count') }}",
            dataType: "json",
            data: {
                "_token": "{{ csrf_token() }}",
                type: type,
            },
            success: function(data) {
                $(".badge-counter").text(data.count);

                let last_html = '';
                if (data.last_notification.length) {
                    // credit-card
                    $.each(data.last_notification.slice(0, 3), function(index, row) {
                        last_html +=
                            '<div class="dropdown-item d-flex align-items-center" href="javascript:void(0)">' +
                            '<div class="mr-3">' +
                            '<div class="icon-circle bg-gold">' +
                            '<i class="fa fa-info text-white"></i>' +
                            '</div>' +
                            '</div>' +
                            '<div>' +
                            '<div class="small text-gray-500">' + moment(row.created_at).format(
                                "MMMM D,YYYY") + '</div>' +
                            '' + row.messages + '' +
                            '</div>' +
                            '</div>';
                    });
                } else {
                    last_html = '';
                }

                $("#last_notification").empty();
                $("#last_notification").append(last_html);
            }
        });
    }

    function clear_notification() {
        notification_count(1);
    }

    $("#search_all").on('keyup', function() {
        let search_text = $(this).val();

        if (search_text) {
            $('#search_data').show();

            $.ajax({
                type: "POST",
                url: "{{ route('front.search_all') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    search_text: search_text
                },
                dataType: "JSON",
                success: function(data) {
                    let search_data = '';

                    search_data += '<dl style="padding: 10px">';
                    if (data) {
                        let frd_url = "{{route('front.friend_list')}}";

                        search_data += '<dt>People (' + data.People.length + ')</dt>';
                        $.each(data.People, function(index, row) {
                            search_data += '<dd style="margin-left: 20px;"><a href="' +
                                frd_url + '/' + row.first_name + ' ' + row.last_name +
                                '"> ' + row.first_name + ' ' + row.last_name + '</a></dd>';
                        })

                        search_data += '<dt>Survey (' + data.Survey.length + ')</dt>';
                        $.each(data.Survey, function(index, row) {
                            if (row.filled_check > 0) {
                                let url = "{{ url('show_fill_survey_info') }}";
                                search_data += '<dd style="margin-left: 20px;"><a href="' +
                                    url + '/' + row.uuid + '">' + row
                                    .survey_strip_tags_title + '</a></dd>';
                            } else if (row.your_check > 0) {
                                let url = "{{ url('survey_list') }}";
                                search_data += '<dd style="margin-left: 20px;"><a href="' +
                                    url + '">' + row.survey_strip_tags_title + '</a></dd>';
                            } else {
                                let url = "{{ url('fill_survey') }}";
                                search_data += '<dd style="margin-left: 20px;"><a href="' +
                                    url + '/' + row.uuid + '">' + row
                                    .survey_strip_tags_title + '</a></dd>';
                            }
                        });
                    }

                    search_data += '</dl>';
                    $("#search_data").css({
                        "width": "95%",
                        "float": "left",
                        "background": "white",
                        "height": "300px",
                        "overflow-y": "scroll"
                    });

                    if (data.People.length == 0) {
                        $("#search_data").css({
                            "width": "95%",
                            "float": "left",
                            "background": "white",
                            "height": "100px",
                            "overflow-y": "scroll"
                        });
                    }

                    if (data.Survey.length == 0) {
                        $("#search_data").css({
                            "width": "95%",
                            "float": "left",
                            "background": "white",
                            "height": "100px",
                            "overflow-y": "scroll"
                        });
                    }

                    $("#search_data").html(search_data);
                }
            });

            document.addEventListener('click', removeSearchList);
        } else {
            $("#search_data").empty();
            $("#search_data").removeAttr("style");
        }
    });

    // Chat start
    hideChat(0);
    $('#contact').click(function() {
        toggleFab();
    });

    //Toggle chat and links
    function toggleFab() {
        $('.contact').toggleClass('zmdi-comment-outline');
        $('.contact').toggleClass('zmdi-close');
        $('.contact').toggleClass('is-active');
        $('.contact').toggleClass('is-visible');
        $('#contact').toggleClass('is-float');
        $('.chat').toggleClass('is-visible');
        $('.fab').toggleClass('is-visible');
    }

    function hideChat(hide) {}

    function get_user_friends() {
        $.ajax({
            type: "GET",
            url: "{{ route('front.get_user_friends') }}",
            success: function(res) {
                // render_chat_friends
                if (res.user_list.length) {
                    let render_friends_html = '';

                    $.each(res.user_list, function(index, row) {
                        let user_profile = '';

                        if (row.profile_image) {
                            user_profile = row.profile_image;
                        } else {
                            user_profile =
                                "{{asset('front_asset/dashboard/assets/images/default1.png') }}";
                        }

                        render_friends_html += '<li>' +
                            '<a href="javascript:void(0)" onclick="open_chat_board(this)" data-full_name="' +
                            row.first_name + ' ' + row.last_name + '" data-to_user_id="' + row.id +
                            '"><img class="img-circle" src="' + user_profile + '"> ' + row
                            .first_name + ' ' + row.last_name + '</a>' +
                            '</li>';
                    });

                    $('#render_chat_friends').empty();
                    $('#render_chat_friends').append(render_friends_html);
                } else {}
            }
        });
    }

    $(document).ready(function() {
        get_user_friends();

        // Minimize of the chat window.
        $(document).on("click", ".conversation__user__info", function() {
            $(".chat__mask").toggleClass("chat__mask--minimize");
        });

        function sendMessage() {
            // Get text message from input.
            let newMessage = $("#chat__input").val();
            let to_user_id = $("#to_user_id").val();

            // If input is empty and tries to send message, prevent sending.
            if (newMessage == "") {
                return false;
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('front.send_user_message') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'message': newMessage,
                        'to_user_id': to_user_id,
                    },
                    success: function(res) {
                        // getMessage(to_user_id,1);

                        let chat_messages_html = '';

                        // conversation__view__body
                        chat_messages_html += '<div class="conversation__view__bubbles">' +
                            '<p class="chat__right__bubble">' + newMessage + '</p>' +
                            '</div>';
                        $(".conversation__view__body").append(chat_messages_html);

                        $(".conversation__view__body").stop().animate({
                            scrollTop: $(".conversation__view__body")[0].scrollHeight
                        }, 100);
                    }
                })

                // Add to conversation.
                /* $('<article class="conversation__view__bubbles"><p class="chat__right__bubble">' +
                	newMessage +
                	"</p></article>"
                	).appendTo(".conversation__view__body"); */

                // Clear input.
                $("#chat__input").val("");
            }
        }

        $(document).on("click", ".chat__conversation__send", function() {
            sendMessage();
        });

        $("#chat__input").keypress(function(e) {
            if (e.which == 13) {
                sendMessage();
            }
        });

        // Show new message notification after x seconds.
        // setTimeout(newMessage, 4000);

        // Go to list view on new message notification.
        $(".conversation__new__message").on("click", function() {
            $(".chat__container").addClass("chat__list--active");
            $(".conversation__new__message").removeClass("chat__notification--active");
        });

        // Go to list view on chat list link.
        $(".list__view__link").on("click", function() {
            $(".chat__container").addClass("chat__list--active");
        });
        // Click to close chat window altogether.
        $(document).on("click", ".conversation__actions__close", function() {
            // $(".chat__mask").addClass("close__chat");
            $(".chat__mask").hide();
        });
    });

    function open_chat_board(e) {
        let page = 1;

        $(".conversation__view__body").empty();

        let to_user_id = $(e).attr('data-to_user_id');
        let full_name = $(e).attr('data-full_name');

        $('#contact').click();

        $(".main__container").empty();

        $(".main__container").append('<div class="chat__mask">' +
            '<div class="chat__container">' +
            '<div class="conversation__view">' +
            '<header class="conversation__view__header">' +
            '<div class="conversation__user__info">' +
            '<span class="conversation__user__status"></span>' +
            '<span class="conversation__user__name">' + full_name + '</span>' +
            '</div>' +
            '<div class="conversation__view__actions">' +
            '<button class="conversation__actions__close" title="Close conversation">' +
            '<svg viewBox="0 0 22 22">' +
            '<path d="M23,20.2L14.8,12L23,3.8L20.2,1L12,9.2L3.8,1L1,3.8L9.2,12L1,20.2L3.8,23l8.2-8.2l8.2,8.2L23,20.2z"/>' +
            '</svg>' +
            '</button>' +
            '</div>' +
            '</header>' +
            '<div class="conversation__view__body">' +
            /* '<div class="conversation__view__bubbles">'+
            	'<p class="chat__left__bubble">How are you?</p>'+
            '</div>'+ */
            '</div>' +
            '<footer class="conversation__view__write">' +
            '<div class="fab_field">' +
            '<a id="fab_camera" class="fab_icon"><i class="fa fa-camera"></i></a>' +
            '<a id="fab_send" class="fab_icon chat__conversation__send"><i class="fa fa-paper-plane"></i></a><input type="hidden" id="to_user_id" value="' +
            to_user_id + '">' +
            '<textarea id="chat__input" name="chat_message" placeholder="Send a message" class="chat_field chat_message"></textarea>' +
            '</div>' +
            '</footer>' +
            '</div>' +
            '</div>');

        getMessage(to_user_id, 1);

        $(".conversation__view__body").scrollTop($(".conversation__view__body")[0].scrollHeight);

        $('.conversation__view__body').scroll(function() {
            if ($('.conversation__view__body').scrollTop() == 0) {
                page++;

                getMessage(to_user_id, page);
            }
        });
    }

    function getMessage(to_user_id, page) {
        $.ajax({
            type: "POST",
            url: "{{ route('front.get_user_message') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                'to_user_id': to_user_id,
                'page': page,
            },
            success: function(res) {
                if (res.data.length) {
                    // $(".conversation__view__body").empty();

                    let chat_messages_html = '';

                    // conversation__view__body
                    res.data.reverse();

                    $.each(res.data, function(index, row) {
                        let dir_style = 'chat__left__bubble';

                        if (row.sender_id == "{{Auth::user()->id}}") {
                            dir_style = 'chat__right__bubble';
                        }

                        chat_messages_html += '<div class="conversation__view__bubbles">' +
                            '<p class="' + dir_style + '">' + row.message + '</p>' +
                            '</div>';
                    });

                    $(".conversation__view__body").append(chat_messages_html);

                    $(".conversation__view__body").stop().animate({
                        scrollTop: $(".conversation__view__body")[0].scrollHeight
                    }, 100);
                } else {
                    // $(".conversation__view__body").append('empty');
                }
            }
        });
    }

    // Using pusher
    Pusher.logToConsole = false;

    var pusher = new Pusher("{{env('PUSHER_APP_KEY')}}", {
        cluster: 'mt1'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('read_message', function(res) {
        if (res.to_user_id == "{{Auth::user()->id}}") {
            let chat_messages_html = '';

            // conversation__view__body
            chat_messages_html += '<div class="conversation__view__bubbles">' +
                '<p class="chat__left__bubble">' + res.message + '</p>' +
                '</div>';

            $(".conversation__view__body").append(chat_messages_html);

            $(".conversation__view__body").stop().animate({
                scrollTop: $(".conversation__view__body")[0].scrollHeight
            }, 100);
        }
    });
    // Chat end

    // Chat Notification
    channel.bind('notifications', function(res) {
        if (res.to_user_id == "{{Auth::user()->id}}") {
            $.toast({
                heading: "Notification",
                text: res.message,
                position: 'top-right',
                loaderBg: '#ff6849',
                icon: 'info',
                hideAfter: 5000
            });
        }
    });
    // Survey Invitation Notification
    channel.bind('survey_invitation', function(res) {
        if (res.to_user_id == "{{Auth::user()->id}}") {
            $.toast({
                heading: "Survey invitation",
                text: res.message,
                position: 'top-right',
                loaderBg: '#ff6849',
                icon: 'info',
                hideAfter: 5000
            });
        }
    });
    // Contest Invitation Notification
    channel.bind('contest_invitation', function(res) {
        if (res.to_user_id == "{{Auth::user()->id}}") {
            $.toast({
                heading: "Contest invitation",
                text: res.message,
                position: 'top-right',
                loaderBg: '#ff6849',
                icon: 'info',
                hideAfter: 5000
            });
        }
    });

    function checkUserStatus() {
        let checkRep = 0;

        $.ajax({
            url: "{{ route('front.check-ajax-user-status') }}",
            type: 'GET',
            async: false,
            dataType: 'json',
            success: function(data) {
                checkRep = data.status;
            }
        });

        if (checkRep == false) {
            $.confirm({
                boxWidth: '40%',
                useBootstrap: false,
                title: 'Warning!',
                icon: 'fa fa-warning',
                content: 'Your status in inactive please contact admin.',
                type: 'red',
                draggable: false,
                buttons: {
                    ok: {
                        btnClass: 'btn btn-danger',
                        action: function() {
                            window.location.href = "{{ route('front.login') }}";
                        }
                    }
                }
            });

            return false;
        }
    }

    /** Proloder Start */
    $(window).on('load', function() {
        $('#preloader-active').delay(450).fadeOut('slow');

        $('body').delay(450).css({
            'overflow': 'visible'
        });
    });
    /** Proloder End */
    $(document).ready(function() {
        if ($(window).width() < 768) {
            $('ul#accordionSidebar').css('display', 'none');
            $('ul#accordionSidebar2').removeClass('d-none');
        }
    });
    $('.responsive-toggle-button').on('click', function(){
        console.log('func call');
        $('.sidebar-custom-menu-toggle').toggleClass('d-none');
    })
    
    </script>
</body>

</html>