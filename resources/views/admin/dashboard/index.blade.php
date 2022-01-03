@extends('layouts.admin_app')

@section('content')
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Dashboard</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li class="active">Dashboard</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="white-box">
                    <div class="row row-in">
                        <div class="col-lg-3 col-sm-6 row-in-br">
                        <a href="{{ route('admin.user') }}">
                            <div class="col-in row">
                                <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-users" aria-hidden="true"></i>
                                    <h5 class="text-muted vb">Admin Users</h5>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <h3 class="counter text-right m-t-15 text-danger">{{$total_admin_user}}</h3>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{$total_admin_user}}%"> <span class="sr-only">4% Complete (success)</span> </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>
                        <div class="col-lg-3 col-sm-6 row-in-br b-r-none">
                            <a href="{{ route('admin.get_normal_users') }}">
                            <div class="col-in row">
                                <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-users" aria-hidden="true"></i>
                                    <h5 class="text-muted vb">Normal Users</h5>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <h3 class="counter text-right m-t-15 text-megna">{{$total_normal_user}}</h3>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-megna" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{$total_normal_user}}%"> <span class="sr-only">4% Complete (success)</span> </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-sm-6 row-in-br">
                            <a href="{{ route('admin.get_premium_user') }}">
                            <div class="col-in row">
                                <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-users" aria-hidden="true"></i>
                                    <h5 class="text-muted vb">Premium Users</h5>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <h3 class="counter text-right m-t-15 text-primary">{{$total_premium_user}}</h3>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{$total_premium_user}}%"> <span class="sr-only">4% Complete (success)</span> </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-sm-6  b-0">
                            <a href="{{ route('admin.get_business_user') }}">
                            <div class="col-in row">
                                <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-users" aria-hidden="true"></i>
                                    <h5 class="text-muted vb">Business Users</h5>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <h3 class="counter text-right m-t-15 text-success">{{$total_business_user}}</h3>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{$total_business_user}}%"> <span class="sr-only">4% Complete (success)</span> </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                    <div class="row row-in">
                        <div class="col-lg-3 col-sm-6 row-in-br">
                        <a href="{{ route('admin.get_sub_business_user') }}">
                            <div class="col-in row">
                                <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-users" aria-hidden="true"></i>
                                    <h5 class="text-muted vb">Sub-Business Users</h5>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <h3 class="counter text-right m-t-15 text-danger">{{$total_sub_business_user}}</h3>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{$total_sub_business_user}}%"> <span class="sr-only">4% Complete (success)</span> </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>
                        <div class="col-lg-3 col-sm-6 row-in-br  b-r-none">
                            <a href="{{ route('admin.plan_subscription') }}">
                            <div class="col-in row">
                            <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-columns" aria-hidden="true"></i>
                                <h5 class="text-muted vb">Subscription Plans</h5>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <h3 class="counter text-right m-t-15 text-megna">{{$total_plan}}</h3>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="progress">
                                <div class="progress-bar progress-bar-megna" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{$total_plan}}%"> <span class="sr-only">40% Complete (success)</span> </div>
                                </div>
                            </div>
                            </div>
                        </a>
                        </div>
                        <div class="col-lg-3 col-sm-6 row-in-br">
                            <a href="{{ route('admin.catalogue') }}">
                            <div class="col-in row">
                            <div class="col-md-6 col-sm-6 col-xs-6"> <i class="linea-icon linea-basic" data-icon="&#xe00b;"></i>
                                <h5 class="text-muted vb">Total Catalogue</h5>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <h3 class="counter text-right m-t-15 text-primary">{{$total_catalogue}}</h3>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="progress">
                                <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{$total_catalogue}}%"> <span class="sr-only">40% Complete (success)</span> </div>
                                </div>
                            </div>
                            </div>
                        </a>
                        </div>
                        <div class="col-lg-3 col-sm-6  b-0">
                            <a href="{{route('admin.survey')}}">
                                <div class="col-in row">
                                    <div class="col-md-6 col-sm-6 col-xs-6"> <i class="linea-icon linea-basic" data-icon="&#xe016;"></i>
                                    <h5 class="text-muted vb">Total Survey</h5>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                    <h3 class="counter text-right m-t-15 text-success">{{$total_survey}}</h3>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: {{$total_survey}}%"> <span class="sr-only">40% Complete (success)</span> </div>
                                    </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('admin_asset/assets/plugins/bower_components/morrisjs/morris.js') }}"></script>
    <script type="text/javascript">
    </script>
@endsection
