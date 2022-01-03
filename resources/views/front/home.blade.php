@extends('front.layouts.app')

@section('content')
	<!-- Start Home Main Slider Section -->
	<section class="main-home-slider">
		<div class="container">
			<div class="row hero hero-bg-img" data-arrows="true" data-autoplay="true" style="background-image: url('{{$banner_rightimage["banner_image_full_path"]}}')">
				<!--Start hero-slide-->
				@foreach ($banner_data as $key => $banner)
					<div class="hero-slide">
						<div class="header-content slide-content col-12 col-sm-12 col-md-6">
							<div class="title font-italic font-medium">{{$banner->title}}</div>
							<div class="content-text mb-40 font-medium txt-gray">{{$banner->description}} </div>
							@if ($key == 0)
								<a class="btn btn-gold w-max" href="{{route('front.login')}}" tabindex="0"> Sign up is easy & free</a>
							@endif
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</section>
	<!-- End Home Main Slider Section -->
	<!-- Start Analysis Section -->
	<section class="bg-light-gray analysis-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-12 col-sm-12 col-md-12">
					<div class="title txt-gray text-center font-italic mb-20">Campaign Demonstration</div>
				</div>
			</div>

			<div class="row">
				<div class="col-12 col-sm-12 col-md-12 col-lg-6 d-flex-column-center m-lg-center">
					<div class="sub-title">{{$demonstration_data[0]->title}}</div>
					<div class="content-text mb-40 txt-gray">{{$demonstration_data[0]->description}} </div>
					<a class="btn btn-gold w-max" href="{{route('front.add_contest')}}" tabindex="0">Create Contest</a>
				</div>
				<div class="col-12 col-sm-12 col-md-12 col-lg-6 m-lg-center">
					<!-- Start right hand slider -->
					<div class="survey-slider-one">
						<div class="left-img">
							<img src="{{asset('front_asset/assets/images/slider/img_right_hand_1.png') }}" alt="img">
						</div>

						<div class="middle-img survey-right-hand-slider" data-arrows="true" data-autoplay="true" style="background-image: url('front_asset/assets/images/slider/img_right_hand_2.png')">

							<div class="survey-box">
								<div class="small-title font-medium pb-0">Contest 1</div>
								<div class="bottom-line-off-white"></div>
								<div class="content-section">
									<div class="contest_campaign_form">
										<input type="hidden" name="_token" value="jjehTX9tJfg4nlpw2iv21JgkPe5EtwLVqkJS1bym" tabindex="0">
										<input type="hidden" name="uuid" value="test95309194-9a14-49aa-8d6b-1ee1485521f8" tabindex="0">
										<div class="form-row">
											<div class="cc_form_group form-group col-md-12">
												<label for="inputEmail4">Link</label>
												<input type="text" name="contest_link" id="contest_link" class="cc_form_control form-control" tabindex="0">
											</div>
										</div>
										<div class="text-center or">OR</div>
										<div class="form-row">
											<div class="cc_form_group form-group col-md-12">
												<label for="inputEmail4">Image</label>
												<input type="file" name="contest_image" id="contest_image" class="cc_form_control form-control" accept="image/*" tabindex="0">
											</div>
										</div>
										<div class="text-center or">OR</div>
										<div class="form-row">
											<div class="cc_form_group form-group col-md-12">
												<label for="inputEmail4">Video</label>
												<input type="file" name="contest_video" id="contest_video" class="cc_form_control form-control" accept="video/*" tabindex="0">
											</div>
										</div>
										<div class="text-center or">OR</div>
										<div class="form-row">
											<div class="cc_form_group form-group col-md-12">
												<label for="inputEmail4">Audio</label>
												<input type="file" name="contest_audio" id="contest_audio" class="cc_form_control form-control" accept="audio/*" tabindex="0">
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="survey-box">
								<div class="small-title font-medium pb-0">Contest 2</div>
								<div class="bottom-line-off-white"></div>
								<div class="content-section">
									<div class="contest_campaign_form">
										<input type="hidden" name="_token" value="jjehTX9tJfg4nlpw2iv21JgkPe5EtwLVqkJS1bym" tabindex="0">
										<input type="hidden" name="uuid" value="test95309194-9a14-49aa-8d6b-1ee1485521f8" tabindex="0">
										<div class="form-row">
											<div class="cc_form_group form-group col-md-12">
												<label for="inputEmail4">Link</label>
												<input type="text" name="contest_link" id="contest_link" class="cc_form_control form-control" tabindex="0">
											</div>
										</div>
										<div class="text-center or">OR</div>
										<div class="form-row">
											<div class="cc_form_group form-group col-md-12">
												<label for="inputEmail4">Image</label>
												<input type="file" name="contest_image" id="contest_image" class="cc_form_control form-control" accept="image/*" tabindex="0">
											</div>
										</div>
										<div class="text-center or">OR</div>
										<div class="form-row">
											<div class="cc_form_group form-group col-md-12">
												<label for="inputEmail4">Video</label>
												<input type="file" name="contest_video" id="contest_video" class="cc_form_control form-control" accept="video/*" tabindex="0">
											</div>
										</div>
										<div class="text-center or">OR</div>
										<div class="form-row">
											<div class="cc_form_group form-group col-md-12">
												<label for="inputEmail4">Audio</label>
												<input type="file" name="contest_audio" id="contest_audio" class="cc_form_control form-control" accept="audio/*" tabindex="0">
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
						<div class="left-img">
							<img src="{{asset('front_asset/assets/images/slider/img_right_hand_3.png') }}" alt="img">
						</div>
					</div>
					<!-- End right hand slider -->
				</div>
			</div>

			<div class="row flex-column-reverse flex-lg-row">
				<div class="col-12 col-sm-12 col-md-12 col-lg-6 m-lg-center">
					<!-- Start left hand slider -->
					<div class="survey-slider-one">
						<div class="left-img">
							<img src="{{asset('front_asset/assets/images/slider/img_left_hand_1.png') }}" alt="img">
						</div>

						<div class="middle-img survey-left-hand-slider" data-arrows="true" data-autoplay="true" style="background-image: url('front_asset/assets/images/slider/img_left_hand_2.png')">

							<div class="survey-box">
								<div class="small-title font-medium pb-0">Physical Fitness Survey</div>
								<div class="bottom-line-off-white"></div>
								<div class="content-section">
									<ul class="faqs-items">
										<li class="faq-question pb-10">1. How often do you exercise?</li>
										<div class="faq-answers">
											<div class="custom-checkboxes" role="" aria-labelledby="custom-chk-label">
												<div class="single-chk">
													<input type="checkbox" id="chk1" value="Once a week">
													<label for="chk1" data-content="Get out of bed">Once a week</label>
												</div>
												<div class="single-chk">
													<input type="checkbox" id="chk2" value="Evry other day">
													<label for="chk2" data-content="Get out of bed">Evry other day</label>
												</div>
												<div class="single-chk">
													<input type="checkbox" id="chk3" value="Several times a month">
													<label for="chk3" data-content="Get out of bed">Several times a month</label>
												</div>
												<div class="single-chk">
													<input type="checkbox" id="chk4" value="Very rarely">
													<label for="chk4" data-content="Get out of bed">Very rarely</label>
												</div>
											</div>
										</div>
									</ul>
								</div>
							</div>

							<div class="survey-box">
								<div class="small-title font-medium pb-0">Physical Fitness Survey</div>
								<div class="bottom-line-off-white"></div>
								<div class="content-section">
									<ul class="faqs-items">
										<li class="faq-question pb-10">2. How often do you exercise?</li>
										<div class="faq-answers">
											<div class="custom-checkboxes" role="" aria-labelledby="custom-chk-label">
												<div class="single-chk">
													<input type="checkbox" id="chk1" value="Once a week">
													<label for="chk1" data-content="Get out of bed">Once a week</label>
												</div>
												<div class="single-chk">
													<input type="checkbox" id="chk2" value="Evry other day">
													<label for="chk2" data-content="Get out of bed">Evry other day</label>
												</div>
												<div class="single-chk">
													<input type="checkbox" id="chk3" value="Several times a month">
													<label for="chk3" data-content="Get out of bed">Several times a month</label>
												</div>
												<div class="single-chk">
													<input type="checkbox" id="chk4" value="Very rarely">
													<label for="chk4" data-content="Get out of bed">Very rarely</label>
												</div>
											</div>
										</div>
									</ul>
								</div>
							</div>

						</div>
						<div class="left-img">
							<img src="{{asset('front_asset/assets/images/slider/img_left_hand_3.png') }}" alt="img">
						</div>
					</div>
					<!-- End left hand slider -->
				</div>
				<div class="col-12 col-sm-12 col-md-12 col-lg-6 d-flex-column-center m-lg-center">
					<div class="sub-title">{{$demonstration_data[1]->title}}</div>
					<div class="content-text mb-40 txt-gray">{{$demonstration_data[1]->description}}</div>
					<a class="btn btn-gold w-max" href="{{route('front.create_survey')}}" tabindex="0">Create Survey</a>
				</div>
			</div>

		</div>
	</section>
	<!-- End Analysis Section -->
	<!-- Start Our Client Section -->
	<section class="bg-white our-client-section">
		<div class="container">
			<div class="row">
				<div class="col-12 col-sm-12 col-md-12">
					<div class="title txt-gray text-center font-italic mb-20">@lang('messages.client_who_trust_us')</div>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="center slider our-client-slider">
				<div class="single-client">
					<img class="mx-auto img-fluid" src="{{asset('front_asset/assets/images/slider/client_img_2.svg') }}" alt="img">
				</div>
				<div class="single-client">
					<img class="mx-auto img-fluid" src="{{asset('front_asset/assets/images/slider/client_img_3.svg') }}" alt="img">
				</div>
				<div class="single-client">
					<img class="mx-auto img-fluid" src="{{asset('front_asset/assets/images/slider/client_img_4.svg') }}" alt="img">
				</div>
				<div class="single-client">
					<img class="mx-auto img-fluid" src="{{asset('front_asset/assets/images/slider/client_img_5.svg') }}" alt="img">
				</div>
				<div class="single-client">
					<img class="mx-auto img-fluid" src="{{asset('front_asset/assets/images/slider/client_img_6.svg') }}" alt="img">
				</div>
				<div class="single-client">
					<img class="mx-auto img-fluid" src="{{asset('front_asset/assets/images/slider/client_img_1.svg') }}" alt="img">
				</div>
				<div class="single-client">
					<img class="mx-auto img-fluid" src="{{asset('front_asset/assets/images/slider/client_img_2.svg') }}" alt="img">
				</div>
				<div class="single-client">
					<img class="mx-auto img-fluid" src="{{asset('front_asset/assets/images/slider/client_img_3.svg') }}" alt="img">
				</div>
				<div class="single-client">
					<img class="mx-auto img-fluid" src="{{asset('front_asset/assets/images/slider/client_img_4.svg') }}" alt="img">
				</div>
				<div class="single-client">
					<img class="mx-auto img-fluid" src="{{asset('front_asset/assets/images/slider/client_img_5.svg') }}" alt="img">
				</div>
				<div class="single-client">
					<img class="mx-auto img-fluid" src="{{asset('front_asset/assets/images/slider/client_img_6.svg') }}" alt="img">
				</div>
			</div>
		</div>
	</section>
	<!-- End Our Client Section -->
@endsection

@section('script')
	<script>
		$(document).ready(function() {
			if ("{{session('success')}}") {
				$.toast({
					heading: "{{session('success')}}",
					// text: "{{session('success')}}",
					position: 'top-right',
					loaderBg: '#ff6849',
					icon: 'success',
					hideAfter: 10000
				});
			}

			if ("{{session('error')}}") {
				$.toast({
					heading: "{{session('error')}}",
					// text: "{{session('error')}}",
					position: 'top-right',
					loaderBg: '#ff6849',
					icon: 'error',
					hideAfter: 10000
				});
			}

		})
	</script>
@endsection