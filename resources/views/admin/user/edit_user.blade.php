@extends('layouts.admin_app')

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">{{ $page_title }}</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><a href="#">{{ $page_title }}</a></li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">

                <div class="row">
                    <div class="col-md-12">
                        @if (session('error'))
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('error') }}
                        </div>
                        @endif
                        @if (session('success'))
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('success') }}
                        </div>
                        @endif
                        <div class="col-md-6 col-xs-12">
                        <form class="form-horizontal form-material" method="post" id="user_profile" action="{{route('admin.update_user')}}">
                            @csrf
                                <div class="form-group">
                                    <label class="col-md-12">First Name</label>
                                    <div class="col-md-12">
                                    <input type="text" placeholder="Enter First Name" name="first_name" id="first_name" class="form-control form-control-line" value="{{$user_detail->first_name}}">
                                    </div>
                                    <input type="hidden" name="id" id="id" value="{{$user_detail->id}}">
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Last Name</label>
                                    <div class="col-md-12">
                                    <input type="text" placeholder="Enter Last Name" name="last_name" id="last_name" class="form-control form-control-line" value="{{$user_detail->last_name}}">
                                    </div>
                                    <input type="hidden" name="id" id="id" value="{{$user_detail->id}}">
                                </div>
                                <div class="form-group">
                                    <label for="example-email" class="col-md-12">Email</label>
                                    <div class="col-md-12">
                                    <input type="email" placeholder="Enter Email" name="email" id="email" class="form-control form-control-line" name="example-email" id="example-email" readonly value="{{$user_detail->email}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                    <button class="btn btn-success">Update Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="education_div_count" id="education_div_count" value="0" />
    <input type="hidden" name="experience_div_count" id="experience_div_count" value="0" />
</div>
@endsection


@section('script')
<script>
    jQuery("#user_profile").validate({

        rules: {
            first_name : {
                required : true,
            },
            last_name : {
                required : true,
            },
            email: {
                required: true,
                email: true,
            },
        },
        messages:{
            email:{
                remote:"Email already used. Please try with other email."
            }
        }
    });

    $(document).ready(function () {

    });
</script>
@endsection
