@extends('front.layouts.app_dashboard')

@section('title', "Welcome to ".env("APP_NAME"))

@section('content')
    <link rel="stylesheet" type="text/css" href="{{asset('admin_asset/assets/plugins/bower_components/fancybox/ekko-lightbox.min.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <style>
        .pac-container {
            background-color: #FFF;
            z-index: 20;
            position: fixed;
            display: inline-block;
            float: left;
        }

        .modal {
            z-index: 20;
        }

        .modal-backdrop {
            z-index: 10;
        }
        .loading-image {
        position: absolute;
        top: 50%;
        left: 50%;
        z-index: 1;
        }
        .loader
        {
            display: none;
            width:200px;
            height: 200px;
            position: fixed;
            top: 50%;
            left: 50%;
            text-align:center;
            margin-left: -50px;
            margin-top: -100px;
            z-index:2;
            overflow: auto;
        }
    </style>
    <div class="loader">
        <center>
            <img class="loading-image" src="{{asset('front_asset/dashboard/assets/images/loader.gif') }}" alt="loading..">
        </center>
    </div>
    <!-- Start Page Content -->
    <div class="container">
        <div class="box-space bg-white mb-4">
            <!-- MultiStep Form three-->
            <div class="card">
                <form id="multistep-form" class="ms-form">
                    <!-- progressbar -->
                    <ul class="row" id="progressbar">
                        <a href="javascript:void(0);" id="account" class="active step-wizard step1 col-12 col-sm-12 col-md-3" data-toggle="modal" data-target="#popupstep1">
                            <li class=""><h6 class="txt-black">Create an account</h6></li>
                        </a>
                        <a href="javascript:void(0);" id="profile" class="  step-wizard step2 col-12 col-sm-12 col-md-3" >
                            <li class=""><h6 class="txt-black">Complete your profile</h6></li>
                        </a>
                        @if(Auth::user()->roles[0]['name'] == "Normal User")
                        <a href="{{route('front.timeline')}}" id="survey" class="step-wizard step3 col-12 col-sm-12 col-md-3">
                            <li class=""><h6 class="txt-black">Checkout latest Contests & Surveys</h6></li>
                        </a>
                        @else 
                            <a href="{{route('front.create_survey')}}" id="contest" class="step-wizard step3 col-12 col-sm-12 col-md-3">
                            <li class=""><h6 class="txt-black">Create Surveys</h6></li>
                        </a>
                        @endif
                        <div class="col-12 col-sm-12 col-md-3">
                            <!-- Start Dynamic Progress bar -->
                            <div class="circle_master">
                                <!-- <div class="circle-progressbar text-center mb-30">
                                    <div class="circle" data-percent="0">
                                        <strong></strong>
                                    </div>
                                </div> -->
                            </div>
                            <!-- End Dynamic Progress bar -->
                        </div>
                        <!-- End Dynamic Progress bar -->
                    </ul>
                </form>
            </div>
            <!-- End MultiStep Form three-->
        </div>
        <!-- acccount & address section start-->
        <div class="box-space bg-white mb-4">
            <h6 class="txt-black font-medium">Account Infomation</h6>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <form action="{{route('front.save_account_detail')}}" method="post" id="save_account_detail_form" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">First Name</label>
                                <input type="text" class="form-control" name="first_name" id="first_name" value="{{Auth::user()->first_name}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Last Name</label>
                                <input type="text" class="form-control" name="last_name" id="last_name" value="{{Auth::user()->last_name}}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Email</label>
                                <input type="email" class="form-control" id="email" value="{{Auth::user()->email}}" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Phone No. </label>
                                <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Phone No." value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['phone_number'] : ""}}" maxlength="10" minlength="10">
                            </div>
                            <!-- <div class="form-group col-md-6">
                                <label for="inputEmail4">Organization name</label>
                                <input type="text" class="form-control" name="organization_name" id="organization_name" placeholder="Organization name" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['organization_name'] : ""}}">
                            </div> -->

                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Language </label>
                                <select name="language" id="language" class="form-control">
                                    <option value="">Select Language</option>
                                    <option value="English" {{(Auth::user()->get_user_info && Auth::user()->get_user_info['language'] == 'English')? "selected": ""}}>English</option>
                                    <option value="German" {{(Auth::user()->get_user_info && Auth::user()->get_user_info['language'] == 'German')? "selected": ""}}>German</option>
                                    <option value="French" {{(Auth::user()->get_user_info && Auth::user()->get_user_info['language'] == 'French')? "selected": ""}}>French</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">DOB</label>
                                <input type="text" class="form-control" name="date_of_birth" id="date_of_birth" placeholder="DOB" value="{{(Auth::user()->get_user_info && Auth::user()->get_user_info['date_of_birth'])? date('d-m-Y',strtotime(Auth::user()->get_user_info['date_of_birth'])) : ""}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Gender</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{(Auth::user()->get_user_info && Auth::user()->get_user_info['gender'] == 'Male')? "selected": ""}}>Male</option>
                                    <option value="Female" {{(Auth::user()->get_user_info && Auth::user()->get_user_info['gender'] == 'Female')? "selected": ""}}>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">

                        </div>
                        <br>
                        @if (!auth()->user()->is_free_subscription)
                        <h6 class="txt-black font-medium">Business Infomation</h6>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Business logo <span class="text-danger">*Upload maximum 2 MB size Image.</span></label>
                                @if(Auth::user()->get_user_info['business_logo'])
                                    @php
                                        $images = app(App\Lib\UploadFile::class)->get_s3_file_path("business_logo",Auth::user()->get_user_info['business_logo']);
                                        @endphp
                                        <a class="lightbox" target="_blank" href="{{$images}}"> View
                                            <img src="{{$images}}" style="display:none;" alt="gallery" class="all studio" height="80"/>
                                        </a>
                                @endif
                                @if(Auth::user()->get_user_info['business_logo'] || Auth::user()->get_user_info['business_logo'] == null)
                                        @php
                                        $images = app(App\Lib\UploadFile::class)->get_s3_file_path("business_logo",Auth::user()->get_user_info['business_logo']);
                                        @endphp
                                <input type="file" class="form-control {{($images) ? : 'required'}}" name="business_logo" id="business_logo" accept="image/*">
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label>Business Contact Number</label>
                                <input type="text" class="form-control" name="business_contact_number" id="business_contact_number" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['business_contact_number'] : ""}}" maxlength="10" minlength="10">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Business Contact Email</label>
                                <input type="email" name="business_contact_email" class="form-control" id="email" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['business_contact_email'] : ""}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Business Name</label>
                                <input type="text" class="form-control" name="organization_name" id="organization_name" placeholder="Organization name" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['organization_name'] : ""}}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Business Website</label>
                                <input type="text" class="form-control" name="business_website" id="business_website" placeholder="Business Website" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['business_website'] : ""}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Business Registration Number</label>
                                <input type="text" class="form-control" name="business_registration_number" id="business_registration_number" placeholder="Business Registration Number" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['business_registration_number'] : ""}}">
                            </div>
                        </div>
                        @endif

                        <div class="form-row">

                        </div>
                        <br>
                        <h6 class="txt-black font-medium">Address Infomation</h6>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Search Address</label>
                                <input type="text" class="form-control" name="autocomplete" id="autocomplete" placeholder="Search Address">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Address</label>
                                <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['address'] : ""}}">
                                <input type="hidden" name="latitude" id="latitude" value="{{ !empty(Auth::user()->get_user_info) ? Auth::user()->get_user_info['latitude'] : "" }}">
                                <input type="hidden" name="longitude" id="longitude" value="{{ !empty(Auth::user()->get_user_info) ? Auth::user()->get_user_info['longitude'] : "" }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Unit/Street</label>
                                <input type="text" class="form-control" name="unit_street" id="unit_street" placeholder="Unit/Street" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['unit_street'] : ""}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">City</label>
                                <input type="text" class="form-control" name="city" id="city" placeholder="City" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['city'] : ""}}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">State</label>
                                <input type="text" class="form-control" name="state" id="state" placeholder="State" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['state'] : ""}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Country</label>
                                <input type="text" class="form-control" name="country" id="country" placeholder="Country" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['country'] : ""}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Postal Code</label>
                                <input type="text" class="form-control" name="postal_code" id="postal_code" placeholder="Postal Code" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['postal_code'] : ""}}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <button type="submit" class="btn btn-gold" title="">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- acccount & address section end-->
        <!-- Start popupstep2 Modal Popup -->
        <div class="modal fade" id="popupstep2" tabindex="-1" role="dialog" aria-labelledby="popupstep2" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gold">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12">
                                    <h4 class="text-center txt-white font-bold mb-0">Personal Information</h4>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="overflow: scroll;height: 400px;">
                        <div class="container">
                            <form action="" method="#" class="common-form ms-form" id="personal_info_form">
                                @csrf
                                <div class="row">
                                    <div class="col-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" value="{{Auth::user()->first_name}}">
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" value="{{Auth::user()->last_name}}">
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                        <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Phone No." value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['phone_number'] : ""}}" maxlength="10" minlength="10">
                                        </div>
                                    </div>
                                    <div class="col-3 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="date_of_birth" id="date_of_birth" placeholder="DOB" value="{{(Auth::user()->get_user_info && Auth::user()->get_user_info['date_of_birth'])? date('d-m-Y',strtotime(Auth::user()->get_user_info['date_of_birth'])) : ""}}">
                                        </div>
                                    </div>
                                    <div class="col-3 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <select name="gender" id="gender" class="form-control">
                                                <option value="">Select Gender</option>
                                                <option value="Male" {{(Auth::user()->get_user_info && Auth::user()->get_user_info['gender'] == 'Male')? "selected": ""}}>Male</option>
                                                <option value="Female" {{(Auth::user()->get_user_info && Auth::user()->get_user_info['gender'] == 'Female')? "selected": ""}}>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    @if (!auth()->user()->is_free_subscription)
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="organization_name" id="organization_name" placeholder="Organization name" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['organization_name'] : ""}}">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="autocomplete" id="autocomplete" placeholder="Search Address">
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['address'] : ""}}">
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="unit_street" id="unit_street" placeholder="Unit/Street" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['unit_street'] : ""}}">
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="city" id="city" placeholder="City" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['city'] : ""}}">
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="state" id="state" placeholder="State" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['state'] : ""}}">
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="country" id="country" placeholder="Country" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['country'] : ""}}">
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="postal_code" id="postal_code" placeholder="Postal Code" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['postal_code'] : ""}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12">
                                        <button type="button" class="btn btn-gold action-button">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End popupstep2 Modal Popup -->
    </div>
    <!-- End Page Content -->
@endsection

@section('script')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{$get_google_key}}&libraries=places"></script>
    <script type="text/javascript" src="{{asset('admin_asset/assets/plugins/bower_components/fancybox/ekko-lightbox.min.js') }}"></script>
    <script src="{{asset('front_asset/dashboard/assets/js/custom-dashboar-script.js') }}" charset="utf-8" async defer></script>
    <script>
        function animateElements() {
            $('.circle-progressbar').each(function () {
                var currentwidth = $('.circle-progressbar .circle').width(); 
                //alert(currentwidth);
                var elementPos = $(this).offset().top;
                //alert("elementPos__"+elementPos);
                var topOfWindow = $(window).scrollTop();
                //alert("topOfWindow__"+topOfWindow);
                var percent = $(this).find('.circle').attr('data-percent');
                var animate = $(this).data('animate');
                if (elementPos < topOfWindow + $(window).height() - 30 && !animate) {
                    $(this).data('animate', true);
                    $(this).find('.circle').circleProgress({
                        // startAngle: -Math.PI / 2,
                        value: percent / 100,
                        //size : 400,
                        size : currentwidth,
                        thickness: 12,
                        fill: {
                            gradient: ['#4f79e9', '#63c4c9']
                            //color: '#4f79e8'
                        }

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('strong').html((stepValue*100).toFixed(0) + "%" + '<span class="progress-complete">complete</span>');                    
                    }).stop();
                }
            });
        }
        
        function change_profile() {
            $("#business_logo").trigger('click');
        }

        $("#business_logo").change(function() {
            business_logo_readURL(this);
        });

        function business_logo_readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.show_img').attr('src', e.target.result);
                    /* $('.show_img').hide();
                    $('.show_img').fadeIn(650); */
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).ready(function(){
            jQuery('#date_of_birth').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy',
                endDate : new Date(),
                orientation: "top"
            });

            if("{{session('success')}}"){
                $.toast({
                    heading: "{{session('success')}}",
                    // text: "{{session('success')}}",
                    position: 'top-right',
                    loaderBg:'#ff6849',
                    icon: 'success',
                    hideAfter: 10000
                });
            }
            if("{{session('info')}}"){
                $.toast({
                    heading: "{{session('info')}}",
                    // text: "{{session('success')}}",
                    position: 'top-right',
                    loaderBg:'#ff6849',
                    icon: 'info',
                    hideAfter: 10000
                });
            }

            if("{{session('error')}}"){
                $.toast({
                    heading: "{{session('error')}}",
                    // text: "{{session('error')}}",
                    position: 'top-right',
                    loaderBg:'#ff6849',
                    icon: 'error',
                    hideAfter: 10000
                });
            }
        });

        // Profile Statistics
        function profile_statistics(){
            $.ajax({
                type : "GET",
                url : "{{route('front.get_profile_statistics')}}",
                dataType:"JSON",
                success:function(data){
                    // console.log(data);
                    var counter_percentage = data.counter;


                        $(".circle_master").empty();
                        $(".circle_master").append('<div class="circle-progressbar text-center mb-30">'+
                                                '<div class="circle" id="circle" data-percent="'+counter_percentage+'">'+
                                                '<strong></strong>'+
                                                '</div>'+
                                            '</div>'+
                                            '<li class="">'+
                                                    '<h6 class="txt-black">Profile Completion</h6>'+
                                                '</li>'
                                            );

                                            animateElements();
                                            
                    if(counter_percentage >= "100"){
                        $("#profile").addClass("active");
                    }else{
                        $("#profile").removeClass("active");
                    }
                }
            })
        }

        // Personal Info Validation
        jQuery.validator.addMethod("noSpace", function(value, element) {
            return value == '' || value.trim().length != 0;
        }, "No space please and don't leave it empty");

        $.validator.addMethod('image_size', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param) 
        }, "Please Upload Less Than 2 Mb");

        $("#save_account_detail_form").validate({
            rules : {
                first_name : {
                    required : true,
                    noSpace : true,
                    maxlength : 255,
                },
                last_name : {
                    required : true,
                    noSpace : true,
                    maxlength : 255,
                },
                phone_number : {
                    required : true,
                    digits : true,
                    noSpace : true,
                    maxlength : 255,
                },
                business_contact_number : {
                    required : true,
                    digits : true,
                    noSpace : true,
                    maxlength : 255,
                },
                business_logo : {
                    // required : true,
                    image_size : 2000000,
                },
                business_registration_number : {
                    required : true,
                    digits : true,
                    noSpace : true,
                    maxlength : 255,
                },
                business_contact_email : {
                    required : true,
                    email:true,
                    noSpace : true,
                    maxlength : 255,
                },
                business_website : {
                    required : true,
                    noSpace : true,
                    url: true,
                },
                gender : {
                    required : true,
                },
                organization_name : {
                    required : true,
                    noSpace : true,
                },
                address : {
                    required : true,
                    noSpace : true,
                    maxlength : 255,
                },
                unit_street : {
                    required : true,
                    noSpace : true,
                    maxlength : 255,
                },
                city : {
                    required : true,
                    noSpace : true,
                    maxlength : 255,
                },
                state : {
                    required : true,
                    noSpace : true,
                    maxlength : 255,
                },
                country : {
                    required : true,
                    noSpace : true,
                    maxlength : 255,
                },
                postal_code : {
                    required: true,
                    noSpace : true,
                    maxlength : 255,
                }
            },
            messages : {
                first_name: {
                    required : "Please enter firstname"
                },
                last_name: {
                    required : "Please enter lastname"
                },
                phone_number:{
                    required : "Please enter phone number",
                },
                business_contact_number:{
                    required : "Please enter phone number",
                },
                business_registration_number:{
                    required : "Please enter phone number",
                },
                business_contact_email:{
                    required : "Please enter Valid Email Address",
                },
                business_website:{
                    required : "Please enter Valid URL",
                },
                gender : {
                    required : "Please select gender",
                },
                address : {
                    required : "Please enter address",
                },
                unit_street : {
                    required : "Please enter unit/street",
                },
                city : {
                    required : "Please enter city",
                },
                state : {
                    required : "Please enter state",
                },
                country : {
                    required : "Please enter country",
                },
                postal_code : {
                    required: "Please enter postal code",
                }
            }
        });

        function personal_info_form(){
            if($("#personal_info_form").valid()){
                var form = $("#personal_info_form").serialize();
                $.ajax({
                    type : "POST",
                    url : "{{route('front.save_personal_info')}}",
                    data : form,
                    /* beforeSend: function(){
                        $('.loader').show()
                    }, */
                    success:function(params) {
                        // console.log(params);
                        var data = JSON.parse(params);
                        if(data.status == true){
                            $("#popupstep2").modal('hide');
                            profile_statistics();
                            $.toast({
                                heading: 'Personal Information',
                                text: data.message,
                                position: 'top-right',
                                loaderBg:'#ff6849',
                                icon: 'success',
                                hideAfter: 3500

                            });
                        }else{
                            $.toast({
                                heading: 'Personal Information',
                                text: data.message,
                                position: 'top-right',
                                loaderBg:'#ff6849',
                                icon: 'error',
                                hideAfter: 3500

                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                    if(errorThrown === "Unauthorized"){

                        $.toast({
                            heading: 'Unauthorized',
                            text: "Your status in inactive please contact admin.",
                            position: 'bottom-right',
                            loaderBg:'#ff6849',
                            icon: 'error',
                            hideAfter: 3000
                        });

                        setInterval(function(){
                            location.reload();
                        }, 3000);


                    }
                }
                    /* complete: function(){
                        $('.loader').hide();
                    } */
                })
            }
        }

        $(".action-button").on('click',function(){
            personal_info_form();
        })

        $(document).keypress(function(e) {
            if ($("#popupstep2").hasClass('show') && (e.keycode == 13 || e.which == 13)) {
                personal_info_form();
            }
        });

        // google address search
        var input = document.getElementById('autocomplete');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();

            if (!place.geometry) {
                window.alert("No details available for input: '" + place.name + "'");
                return;
            }

            // Set Location Info
            var fulladdress = place.formatted_address;
            var address = "";
            var unit_street = "";
            var city = "";
            var country = "";
            var state = "";
            var postal_code = "";
            var lat = place.geometry.location.lat();
            var lng = place.geometry.location.lng();
            $('#latitude').val(lat);
            $('#longitude').val(lng);
            for (var ac = 0; ac < place.address_components.length; ac++) {
                var component = place.address_components[ac];

                console.log(component);

                switch(component.types[0]) {
                    case 'route':
                        address = component.long_name;
                        $("#address").val(address);
                        break;
                    case 'street_number':
                        unit_street = component.long_name;
                        $("#unit_street").val(unit_street);
                        break;
                    case 'locality':
                        city = component.long_name;
                        $("#city").val(city);
                        break;
                    case 'administrative_area_level_1':
                        state = component.long_name;
                        $("#state").val(state);
                        break;
                    case 'country':
                        country = component.long_name;
                        $("#country").val(country);
                        break;
                    case 'postal_code':
                        postal_code = component.long_name;
                        $("#postal_code").val(postal_code);
                        break;
                }
            }
            
            if (address == '') $("#address").val('');
            if (postal_code == '') $("#postal_code").val('');
            if (unit_street == '') $("#unit_street").val('');
        });

        // delegate calls to data-toggle="lightbox"
        $(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function(event) {
            event.preventDefault();
            
            return $(this).ekkoLightbox({
                onShown: function() {
                    console.log(window.console);
                    if (window.console) {
                        // return console.log('Checking our the events huh?');
                    }
                },
                /* onNavigate: function(direction, itemIndex) {
                        if (window.console) {
                            return console.log('Navigating '+direction+'. Current item: '+itemIndex);
                        }
                } */
            });
        });
    </script>
@endsection