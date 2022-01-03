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
                    <div class="col-sm-6 col-xs-6">
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
                        <form class="" method="POST" id="change_password" action="{{ route('admin.savepassword') }}" autocomplete="off">
                            @csrf
                            <div class="form-group">
                                <label>Current Password</label>
                                <div>
                                    <input type="password" name="old_password" class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}" value="" placeholder="Current Password" />
                                    @if ($errors->has('old_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('old_password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <div>
                                    <input type="password" name="new_password" id="new_password" class="form-control{{ $errors->has('new_password') ? ' is-invalid' : '' }}" placeholder="New Password" value="" />
                                    @if ($errors->has('new_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <div>
                                    <input type="password" name="re_password" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" placeholder="Confirm New Password" value="" />
                                    @if ($errors->has('re_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('re_password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
                                <!--<button type="reset" class="btn btn-default waves-effect m-l-5"> Cancel </button></div>-->
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

    // Check Password Format
    $.validator.addMethod("pwcheck", function(value) {
        return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/.test(value) // password check

    }, 'Password must contain one upparcase, one lowercase, one special character, min 8 to 10 characters long.');
    // Password not containt First and Last Name
    $.validator.addMethod("check_first_last_name", function(value) {

        let first_string = "{{Auth::user()->first_name}}";
        let last_string = "{{Auth::user()->last_name}}";
        let firstname = first_string.toLowerCase();
        let lastname = last_string.toLowerCase();
        let password = $("#new_password").val().toLowerCase();
        var testNames = new RegExp(firstname + '|' + lastname, "gi");
        return !testNames.test(password)
    }, 'Your password can’t include your first or last name.');


    $("#change_password").validate({
        ignore: [],
        rules: {
            old_password: {
                required: true,

            },
            new_password: {
                required: true,
                pwcheck: true,
                check_first_last_name: true,
            },
            re_password: {
                required: true,
                equalTo: '#new_password',
            },
        },
        messages: {
            old_password: {
                required: "Current password is require."
            },
            new_password: {
                required: "New password is require."
            },
            re_password: {
                required: "Confirm password is require.",
                equalTo: "Password and confirm password do not match."
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());

        }

    });
</script>
@endsection