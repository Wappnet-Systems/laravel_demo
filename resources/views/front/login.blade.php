@extends('front.layouts.app')

@section('content')

<section class="login-section bg-off-white">
	<div class="container main-container bg-img-contain" style="background-image: url('front_asset/assets/images/worldcup_icon_opacity.svg');">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="common-title" style="text-align: center;">Check Out The Latest Contests / Surveys</div>
				<div class="title" style="text-align: center;">“Win More With Cash And Fantastic Prizes”</div>
				<div class="video-section">
					<img class="img-fluid" src="{{asset('front_asset/assets/images/video_img.svg') }}" alt="video">
				</div>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<div class="user-wrapper bg-white box-shadow-style text-center">
					<!-- Start Login Form -->
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
					<form method="POST" class="common-form login-form" action="{{ route('front.authenticate') }}" id="login-form" style="display:block">
						@csrf
						<div class="from-top-wrappper">
							<div class="title">Login to Your Account</div>
							<div class="form-group">
								<input type="email" class="form-control" name="email" id="email" placeholder="Email">
							</div>

							<div class="form-group">
								<input type="password" class="form-control" name="password" id="password" placeholder="Password">
								{{--<span toggle="#password" class="fa fa-lg fa-eye field-icon toggle-password"></span>--}}
							</div>
							<div class="form-group text-md-right text-sm-center">
								<a href="javaScript:void(0);" class="move-to-forgot-password" id="forgot-password">Forgot Password ?</a>
							</div>
							<div class="form-group">
								<button class="btn btn-gold btn-login w-100" id="btn-login">Login</button>
							</div>
							<div class="form-group from-middle-wrappper">
								<div class="d-flex-cut-center">
									<span class="small-line-left"></span>
									<span>or login with</span>
									<span class="small-line-right"></span>
								</div>
							</div>
							<div class="form-group from-middle-wrappper">
								<ul class="social-media-wrappper">
									<li>
										<a href="{{route('front.google_login')}}" class="social-media">
											<img class="img-fluid" src="{{asset('front_asset/assets/images/icons/google-icon.svg') }}" alt="google">
										</a>
									</li>
									<li>
										<a href="{{route('front.facebook_login')}}" class="social-media">
											<img class="img-fluid" src="{{asset('front_asset/assets/images/icons/facebook-icon.svg') }}" alt="facebook">
										</a>
									</li>
									{{-- <li>
											<a href="#" class="social-media">
												<img class="img-fluid" src="{{asset('front_asset/assets/images/icons/instagram-icon.svg') }}" alt="instagram">
									</a>
									</li> --}}
									<li>
										<a href="{{route('front.linkedin_login')}}" class="social-media">
											<img class="img-fluid" src="{{asset('front_asset/assets/images/icons/linkedin-icon.svg') }}" alt="linkedin">
										</a>
									</li>
									{{-- <li>
											<a href="#" class="social-media">
												<img class="img-fluid" src="{{asset('front_asset/assets/images/icons/twitter-icon.svg') }}" alt="twitter">
									</a>
									</li> --}}
								</ul>
							</div>
						</div>

						<div class="from-bottom-wrappper">
							<div class="form-change-section">
								<span>Don’t have an account?</span>
								<a href="javaScript:void(0);" class="move-to-signup-form"> Sign Up</a>
							</div>
						</div>
					</form>
					<!-- End Login Form -->

					<!-- Start  Sign Up Form -->
					<form action="{{ route('front.new_user') }}" method="post" class="common-form sign-up-form" id="sign-up-form" style="display:none">
						@csrf
						<div class="from-top-wrappper">
							<div class="title">Create a New Account</div>
							<div class="form-group">
								<input type="text" class="form-control" name="first_name" id="firstname" placeholder="First Name">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="last_name" id="lastname" placeholder="Last Name">
							</div>
							<div class="form-group">
								<input type="email" class="form-control" name="email" id="sign-up-email" placeholder="Email">
							</div>
							<div class="form-group">
								<input type="password" class="form-control" name="password" id="sign-up-password" placeholder="Password">
								{{--<span toggle="#sign-up-password" class="fa fa-lg fa-eye field-icon toggle-password"></span>--}}
							</div>
							<div class="form-group">
								<button class="btn btn-gold btn-sign-up w-100" id="btn-sign-up">Sign Up</button>
							</div>
							<div class="form-group from-middle-wrappper">
								<div class="d-flex-cut-center">
									<span class="small-line-left"></span>
									<span>or sign up with</span>
									<span class="small-line-right"></span>
								</div>
							</div>
							<div class="form-group from-middle-wrappper">
								<ul class="social-media-wrappper">
									<li>
										<a href="{{route('front.google_login')}}" class="social-media">
											<img class="img-fluid" src="{{asset('front_asset/assets/images/icons/google-icon.svg') }}" alt="google">
										</a>
									</li>
									<li>
										<a href="{{route('front.facebook_login')}}" class="social-media">
											<img class="img-fluid" src="{{asset('front_asset/assets/images/icons/facebook-icon.svg') }}" alt="facebook">
										</a>
									</li>
									{{-- <li>
											<a href="#" class="social-media">
												<img class="img-fluid" src="{{asset('front_asset/assets/images/icons/instagram-icon.svg') }}" alt="instagram">
									</a>
									</li> --}}
									<li>
										<a href="{{route('front.linkedin_login')}}" class="social-media">
											<img class="img-fluid" src="{{asset('front_asset/assets/images/icons/linkedin-icon.svg') }}" alt="linkedin">
										</a>
									</li>
									{{-- <li>
											<a href="#" class="social-media">
												<img class="img-fluid" src="{{asset('front_asset/assets/images/icons/twitter-icon.svg') }}" alt="twitter">
									</a>
									</li> --}}
								</ul>
							</div>
						</div>

						<div class="from-bottom-wrappper">
							<div class="form-change-section">
								<span>Already have an account?</span>
								<a href="javaScript:void(0);" class="move-to-login-form"> Login</a>
							</div>
						</div>
					</form>
					<!-- End  Sign Up Form -->

					<!-- Start Forgot Password Form -->
					<form method="POST" action="{{ route('front.send_new_password') }}" class="common-form forgot-password-form" id="forgot-password-form" style="display:none">
						@csrf
						<div class="from-top-wrappper">
							<div class="title">Forgot Your Password</div>
							<div class="form-group">
								<input type="email" class="form-control" name="email" id="forgot-password--email" placeholder="Email">
							</div>
							<div class="form-group ">
								<input type="submit" class="btn btn-gold btn-login w-100 btn-reset-password" id="btn-reset-password" value="Reset Password">
								{{--<a href="javascript:void(0)" class="btn btn-gold btn-login w-100 btn-reset-password" id="btn-reset-password">Reset Password</a>--}}
							</div>
						</div>

						<div class="from-bottom-wrappper">
							<div class="form-change-section">
								<span>You got your password</span>
								<a href="javaScript:void(0);" class="move-to-login-form"> Login</a>
							</div>
						</div>
					</form>
					<!-- End Forgot Password Form -->
				</div>
			</div>
		</div>
	</div>
	<!--/row-->
</section>
@endsection
@section('script')
<link href="{{asset('admin_asset/assets/plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
<script src="{{asset('admin_asset/assets/plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
<script>

	/*$('.btn-reset-password').on('click', function(e) {
		$('.btn-reset-password').addClass('disabled');
	});*/

	$(".toggle-password").click(function() {
		$(this).toggleClass("fa-eye fa-eye-slash");
		var input = $($(this).attr("toggle"));
		if (input.attr("type") == "password") {
			input.attr("type", "text");
		} else {
			input.attr("type", "password");
		}
	});
	if ("{{session('account_verify')}}") {
		// alert("{{session('account_verify_email')}}");
		swal({
			title: "Are you sure?",
			text: "Verify Your Account, You will receive email verification link on " + "{{session('account_verify_email')}}" + " email.",
			// text: "You want to reset verification link in "+"{{session('account_verify_email')}}"+" email?",   
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes",
			closeOnConfirm: false,
			showLoaderOnConfirm: true,
		}, function() {
			$.ajax({
				type: "POST",
				url: "{{url('api/resend_verification_link')}}",
				data: {
					"_token": "{{csrf_token()}}",
					email: "{{session('account_verify_email')}}"
				},
				success: function(res) {
					if (res.status === true) {
						swal("Success", res.msg, "success");
					} else {
						swal("Error", res.msg, "error");
					}
				}
			})

		});
	}
	$(document).ready(function() {
		setTimeout(() => {
			$('#email').val("");
			$('#password').val("");
			$('#sign-up-email').val("");
			$('#sign-up-password').val("");
		}, 500);
	})

	//Login Part Start
	$("#login-form").validate({
		rules: {
			email: {
				required: true,
				email: true,
			},
			password: {
				required: true,
			},
		},
		messages: {
			email: {
				required: "Please enter email",
			},
			password: {
				required: "Please enter password"
			},
		}
	})
	$('#btn-login').on('click', function() {
		if ($("#login-form").valid()) {
			$("#login-form").submit();
		}
	})
	//Login Part End
	// Check email Format
	$.validator.addMethod("email", function(value) {
		return /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(value) // password check

	}, 'Invalid Email Address.');

	//Signup Part Start
	// Check Password Format
	$.validator.addMethod("pwcheck", function(value) {
		return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/.test(value) // password check

	}, 'Password must contain one upparcase, one lowercase, one special character, min 8 to 10 characters long.');
	// Password not containt First and Last Name
	$.validator.addMethod("check_first_last_name", function(value) {
      
		let firstname = $("#firstname").val().toLowerCase();
		let lastname = $("#lastname").val().toLowerCase();
		let password = value.toLowerCase();
		var testNames = new RegExp(firstname+'|'+lastname, "gi");
		return !testNames.test(password)
	}, 'Your password can’t include your first or last name.');

	$("#sign-up-form").validate({
		rules: {
			email: {
				required: true,
				email: true,
				remote: {
					url: "{{route('front.check_email')}}",
					type: "post",
					data: {
						"_token": "{{csrf_token()}}",
						email: function() {
							return $("#sign-up-email").val();
						}
					}
				}
			},
			password: {
				required: true,
				pwcheck: true,
				check_first_last_name: true,
			},
			first_name: {
				required: true,
			},
			last_name: {
				required: true,
			},
		},
		messages: {
			email: {
				required: "Please enter email",
				remote: "Email already exists try another"
			},
			first_name: {
				required: "Please enter firstname"
			},
			last_name: {
				required: "Please enter lastname"
			},
			password: {
				required: "Please enter password"
			},
		}
	})
	$('#btn-sign-up').on('click', function() {
		if ($("#sign-up-form").valid()) {
			$("#sign-up-form").submit();
		}
	})
	//Signup Part End

	// Forgot Password Part Start

	$("#forgot-password-form").validate({
		rules: {
			email: {
				required: true,
				email: true,
			},
		},
		messages: {
			email: {
				required: "Please enter email",
			},
		}
	})

	// forgot-password-ajax
	/*$('#btn-reset-password').on('click', function() {
		if ($("#forgot-password-form").valid()) {
			var forgot_form = $('#forgot-password-form').serialize();

			$.ajax({
				type: "POST",
				url: "{{ route('front.send_new_password') }}",
				data: forgot_form,
				success: function(data) {
					data = JSON.parse(data);
					$('#forgot-password-form')[0].reset();
					if (data.status == false) {
						$.toast({
							heading: 'Forgot Password',
							text: data.message,
							position: 'top-right',
							loaderBg: '#ff6849',
							icon: 'error',
							hideAfter: 3500

						});
					} else {
						// alert(data.message);
						$.toast({
							heading: 'Forgot Password',
							text: data.message,
							position: 'top-right',
							loaderBg: '#ff6849',
							icon: 'success',
							hideAfter: 3500
						});
						$(".move-to-login-form").trigger('click');
					}
				},
				error: function(request, status, error) {
					$.toast({
						heading: 'Forgot Password',
						text: "Error Occurred. Try Again!",
						position: 'top-right',
						loaderBg: '#ff6849',
						icon: 'error',
						hideAfter: 3500

					});
				}
			})

			// $("#forgot-password-form").submit();
		}
	})*/
	// Forgot Password Part End
</script>
@endsection