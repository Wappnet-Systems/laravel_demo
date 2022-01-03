@extends('front.layouts.app')

@section('content')

<section class="login-section bg-off-white">
    <div class="container main-container bg-img-contain" style="background-image: url('front_asset/assets/images/worldcup_icon_opacity.svg');">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 mx-auto">
                <div class="user-wrapper bg-white box-shadow-style text-center">
                    <!-- Start Login Form -->
                    @if ($status == "expired")
                        <div class="alert alert-danger alert-dismissable">
                            {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                            Your reset password link expired. please reset again <a href="route('front.login')">click here</a>.
                        </div>
                    @endif
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
                    @if ($status == "valid")
                        <!-- Start Forgot Password Form -->
                            <form method="POST" action="{{route('front.reset_password_change')}}" class="common-form forgot_password_form" id="forgot_password_form">
                                @csrf
                                <div class="from-top-wrappper">
                                    <div class="title">Reset Your Password</div>
                                    <div class="form-group">
                                    <input type="hidden" name="reset_token" value="{{$token}}">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter New Password">
                                        {{--<span toggle="#password" class="fa fa-lg fa-eye field-icon toggle-password"></span>--}}
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" name="c_password" id="c_password" placeholder="Enter Confirm Password">
                                        {{--<span toggle="#c_password" class="fa fa-lg fa-eye field-icon toggle-password"></span>--}}
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-gold btn-login w-100" id="btn-reset-password">Reset Password</button>
                                    </div>
                                </div>
                            </form>
                            <!-- End Forgot Password Form -->
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!--/row-->
</section>
@endsection
@section('script')
<script>

    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

$(function () {

});
$("#forgot_password_form").validate({
    rules : {
        password : {
            required : true,
        },
        c_password : {
            required : true,
            equalTo : "#password"
        }
    }
})
</script>
@endsection
