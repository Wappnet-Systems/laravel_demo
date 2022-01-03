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
                    <div class="col-sm-12 col-xs-6">
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
                        <form class="" method="POST" id="user_form" action="{{route('admin.create_user')}}" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                            <label>First Name*</label>
                                            <input type="text" class="form-control" name="first_name" id="first_name">
                                            <input type="hidden" name="id" >
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                            <label>Last Name*</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                            <label>Email*</label>
                                            <input type="email" class="form-control email" name="email" id="email">
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                            <label>Role*</label>
                                            <select name="role" id="role" class="form-control">
                                                <option value="">Select Role</option>
                                                @if (count($roles))
                                                    @foreach ($roles as $key => $value)
                                                        <option value="{{$value}}">{{$value}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label>Password*</label>
                                            <input type="password" class="form-control" name="password" id="password">
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label>Confirm Password*</label>
                                            <input type="password" class="form-control" name="c_password" id="c_password">
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label>Profile</label>
                                            <input type="file" class="form-control" name="profile_image" id="profile_image" accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                                    <a href="{{ URL::previous() }}"><button type="button" class="btn btn-default waves-effect m-l-5"> Cancel </button></div></a>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection


@section('script')
<script>
    $(document).ready(function(){
        jQuery('#date_of_birth').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy',
            endDate : new Date(),
        });
    });
    $.validator.addMethod("email", function(value) {
        return /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(value) // password check

    }, 'Invalid Email Address.');

    $("#user_form").validate({
        rules: {
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: '{{ route("admin.check_email") }}',
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        email: function () {
                            return $('#email').val();
                        }
                    }
                }
            },
            password: {
                required: true,
                minlength : 8,
            },
            c_password: {
                equalTo : "#password",
            },
            role: {
                required: true,
            },
        },
        messages : {
            c_password: {
                equalTo : "Password and confirm password not match",
            },
            email: {
                remote: "Email already in use."
            }
        }
    });

</script>
@endsection
