@extends('front.layouts.app_dashboard')

@section('title', env("APP_NAME")." - My Account")

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.6.1/sweetalert2.min.css" integrity="sha512-+pGmJ3i6OuNjIlRy/REXeyodbWbqEAkHNCCeabR4Jsm46v6eGh4tlWb2h3TKpXQXZgsK1I9HwNYA3dbohQHkJA==" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{asset('front_asset/dashboard/assets/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_asset/assets/plugins/bower_components/fancybox/ekko-lightbox.min.css') }}"/>
    <!-- Start Page Content -->
    <div class="container">
        <!-- Start section -->
        {{-- Edit account --}}
        @php
        $button_show = "";
        $button_title = "";
        if(Auth::user()->social_status){
        $button_show = "disabled";
        $button_title = "You login from ".Auth::user()->social_status." and you change the password from there.";
        }
        @endphp
        @if (auth()->user()->is_free_subscription)
            <!-- Start Point -->
            <div class="box-space bg-white mb-4">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <h6 class="txt-black text-center font-medium"  style="margin-top: -25px;"> 
                            @if($full_icon_path)<img style="width: 100%; max-width: 100px; display: block; margin: 0 auto;" src="{{$full_icon_path}}" alt="">@endif
                            Total Points Earned : {{$total_earn_point}}
                        </h6>
                    </div>
                    @if($current_rank)
                        <div class="col-6 col-sm-6 col-md-6">
                            <span class="badge badge-pill badge-secondary" style="background-color: {{$current_color}}">{{$start_point}}</span>
                        </div>
                        <div class="col-6 col-sm-6 col-md-6 text-right">
                            <span class="badge badge-pill badge-secondary" style="background-color: {{$next_color}}">{{$end_point}}</span>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12  mt-15">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{$current_rank_percent}}%;background-color: {{$current_color}}" >{{$current_rank_percent}} %</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-6 col-md-6 mt-5">
                            <span class="badge badge-pill badge-secondary" style="background-color: {{$current_color}}">{{$current_rank_title}}</span>
                        </div>
                        <div class="col-6 col-sm-6 col-md-6 text-right mt-5">
                            @if($next_rank_title)<span class="badge badge-pill badge-secondary" style="background-color: {{$next_color}}">{{$next_rank_title}}  <i class="fa fa-arrow-right" aria-hidden="true"></i> </span>@endif
                        </div>
                    @endif
                </div>
            </div>
            <!-- End Poinf-->
        @endif
        <!-- Start section Wallet -->
        <div class="box-space bg-white mb-4">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    @php
                    $total_wallet = (Auth::user()->get_user_info)? Auth::user()->get_user_info['wallet_amount'] : "0";

                    $percent_wallet = $total_wallet / $get_wallet_amount * 100;
                    $amount_transfer_btn = false;
                    $progressbar_color = "bg-warning";
                    if($total_wallet >= $get_wallet_amount){
                    $progressbar_color = "bg-success";
                    $percent_wallet = 100;
                    $amount_transfer_btn = true;
                    }
                    @endphp
                    <h6 class="txt-black font-medium">Wallet Amount : $ {{$total_wallet}}</h6>
                </div>
                <div class="col-12 col-sm-12 col-md-12  mt-15">
                    <div class="progress">
                        <div title="Minimum Withdrawal Amount : $ {{$get_wallet_amount}}" class="progress-bar progress-bar-striped {{$progressbar_color}} progress-bar-animated" role="progressbar" style="width: {{$percent_wallet}}%;" aria-valuenow="{{$percent_wallet}}" aria-valuemin="0" aria-valuemax="100">{{$percent_wallet}}%</div>
                    </div>
                </div>
                <br>
                <br>
                @if ($amount_transfer_btn)
                <div class="col-12 col-sm-12 col-md-12  mt-15 text-right">
                    <a href="javascript:void(0)" onclick="transfer_amount()"><button class="btn btn-success btn-sm"><i class="fa fa-check" aria-hidden="true"></i> Withdrawal ($ {{$total_wallet}})</button></a>
                </div>
                @endif
            </div>
        </div>
        <!-- End section -->

        <div class="box-space bg-white mb-4">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    @if (!auth()->user()->is_free_subscription)    
                        @php
                            $date1 = date_create(date('Y-m-d',strtotime($plan_created_at)));
                            $date2 = date_create(date('Y-m-d'));
                            $diff = date_diff($date1,$date2);
                        @endphp
                        {{-- <p class="float-right">Joined {{($diff->format("%a") <= 10) ? $diff->format("%a")." days ago" : date('d F, Y',strtotime($plan_created_at))}} </p> --}}
                        <p class="float-right">Joined {{date('d F, Y',strtotime($plan_created_at))}} </p>
                    @endif
                    <h6 class="txt-black font-medium">Account Infomation</h6>
                </div>
            </div>
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
                            <div class="form-group col-md-2">
                                <label for="inputEmail4">Profile Image</label>
                                <input type="file" class="form-control" name="profile_image" id="profile_image" accept="image/jpg, image/jpeg, image/png" style="display:none;">
                                {{-- <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="profile_image" id="profile_image">
                                    <label class="custom-file-label" for="profile_image">Choose file</label>
                                </div> --}}

                            </div>
                            <div class="form-group col-md-2">
                                @if(!empty(Auth::user()->profile_image))
                                @php
                                $images = app(App\Lib\UploadFile::class)->get_s3_file_path("profile_image",Auth::user()->profile_image);
                                @endphp
                                <img class="img-circle show_img" src="{{$images}}" alt="user" style="height: 80px;width: 80px;cursor: pointer;" onclick="change_profile()" />
                                @else
                                <img class="img-circle show_img" src="{{asset('front_asset/dashboard/assets/images/default1.png') }}" alt="user" style="height: 80px;width: 80px;cursor: pointer;" onclick="change_profile()" />
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="inputEmail4">Phone No. </label>
                                <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Phone No." value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['phone_number'] : ""}}" maxlength="10" minlength="10">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputEmail4">Language </label>
                                <select name="language" id="language" class="form-control">
                                    <option value="">Select Language</option>
                                    <option value="English" {{(Auth::user()->get_user_info && Auth::user()->get_user_info['language'] == 'English')? "selected": ""}}>English</option>
                                    <option value="German" {{(Auth::user()->get_user_info && Auth::user()->get_user_info['language'] == 'German')? "selected": ""}}>German</option>
                                    <option value="French" {{(Auth::user()->get_user_info && Auth::user()->get_user_info['language'] == 'French')? "selected": ""}}>French</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputEmail4">DOB</label>
                                <input type="text" class="form-control" name="date_of_birth" id="date_of_birth" placeholder="DOB" value="{{(Auth::user()->get_user_info && Auth::user()->get_user_info['date_of_birth'])? date('d-m-Y',strtotime(Auth::user()->get_user_info['date_of_birth'])) : ""}}">
                            </div>
                            <div class="form-group col-md-3">
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
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Interest</label>
                                <select id="user_interest" name="user_interest[]" multiple class="form-control user_interest drp_multi_select">
                                    @if (count($site_interest))
                                    @foreach ($site_interest as $site_interest_key => $site_interest_value)
                                    <option value="{{$site_interest_key}}" {{($selected_interest->contains('site_interest_id',$site_interest_key)) ? "selected" : ""}}>{{$site_interest_value}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            {{-- @if ($plan_type != "Free Plan")
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Organization name</label>
                                <input type="text" class="form-control" name="organization_name" id="organization_name" placeholder="Organization name" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['organization_name'] : ""}}">
                            </div>
                            @endif --}}
                        </div>
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
                                    <input type="text" class="form-control" name="business_contact_number" id="business_contact_number" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['business_contact_number'] : ""}}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Business Contact Email</label>
                                    <input type="email" name="business_contact_email" class="form-control" id="business_contact_email" value="{{(Auth::user()->get_user_info)? Auth::user()->get_user_info['business_contact_email'] : ""}}">
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
                                <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="{{ !empty(Auth::user()->get_user_info) ? Auth::user()->get_user_info['address'] : "" }}">
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
        {{-- Change Password --}}
        @if (empty($button_show))
        <div class="box-space bg-white mb-4">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 mb-30">
                    <h6 class="txt-black font-medium">Change Password</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <form action="{{route('front.change_password')}}" method="post" id="change_password_form" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Old Password</label>
                                <input type="password" class="form-control" name="old_password" id="old_password">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">New Password</label>
                                <input type="password" class="form-control" name="password" id="password">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Confirm Password</label>
                                <input type="password" class="form-control" name="c_password" id="c_password">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <button type="submit" class="btn btn-gold" {{$button_show}} title="{{$button_title}}">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
        <!-- End section -->
    </div>
    <!-- End Page Content -->
@endsection

@section('script')
    <script src="{{asset('front_asset/dashboard/assets/js/custom-dashboar-script.js') }}" charset="utf-8" async defer></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{$get_google_key}}&libraries=places"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.6.1/sweetalert2.min.js" integrity="sha512-mFlAlOUcf3ib0f5F3YU1izX0u9fTzHAnOxrms9iEtAGzSZ8psAi2x1YPtMtdal698/oMIQakfNUM/h73WfFtOA==" crossorigin="anonymous"></script>
    <script src="{{asset('front_asset/dashboard/assets/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('admin_asset/assets/plugins/bower_components/fancybox/ekko-lightbox.min.js') }}"></script>
    <script>
        $.validator.addMethod("email", function(value) {
            return /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(value) // password check
        }, 'Invalid Email Address.');

        $(document).ready(function() {
            $("#user_interest").select2();
            $("#transfer_type").trigger('change');

            jQuery('#date_of_birth').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy',
                endDate: new Date(),
                orientation: "top"
            });

            if ("{{session('success')}}") {
                $.toast({
                    heading: "{{session('success')}}",
                    // text: "{{session('success')}}",
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 5000
                });
            }
            
            if ("{{session('info')}}") {
                $.toast({
                    heading: "{{session('info')}}",
                    // text: "{{session('success')}}",
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 5000
                });
            }

            if ("{{session('error')}}") {
                $.toast({
                    heading: "{{session('error')}}",
                    // text: "{{session('error')}}",
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 5000
                });
            }
        });

        jQuery.validator.addMethod("noSpace", function(value, element) {
            return value == '' || value.trim().length != 0;
        }, "No space please and don't leave it empty");
        
        $.validator.addMethod('image_size', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param) 
        }, "Please Upload Less Than 2 Mb");

        $("#save_account_detail_form").validate({
            rules: {
                first_name: {
                    required: true,
                    noSpace: true,
                    maxlength: 255,
                },
                last_name: {
                    required: true,
                    noSpace: true,
                    maxlength: 255,
                },
                phone_number: {
                    required: true,
                    noSpace: true,
                    digits: true,
                    maxlength: 255,
                },
                gender: {
                    required: true,
                },
                language: {
                    required: true,
                },
                organization_name: {
                    noSpace : true,
                    required: function() {
                        return $("#organization_name").is(":visible");
                    },
                },
                business_contact_number : {
                    required : true,
                    digits : true,
                    noSpace : true,
                    maxlength : 255,
                },
                business_logo : {
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
                    no_space : true,
                    url: true,
                },
                address: {
                    required: true,
                    noSpace: true,
                    maxlength: 255,
                },
                unit_street: {
                    required: true,
                    noSpace: true,
                    maxlength: 255,
                },
                city: {
                    required: true,
                    noSpace: true,
                    maxlength: 255,
                },
                state: {
                    required: true,
                    noSpace: true,
                    maxlength: 255,
                },
                country: {
                    required: true,
                    noSpace: true,
                    maxlength: 255,
                },
                postal_code: {
                    required: true,
                    noSpace: true,
                    maxlength: 255,
                },
            },
            messages: {
                first_name: {
                    required: "Please enter firstname"
                },
                last_name: {
                    required: "Please enter lastname"
                },
                phone_number: {
                    required: "Please enter phone number",
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
                gender: {
                    required: "Please select gender",
                },
                address: {
                    required: "Please enter address",
                },
                unit_street: {
                    required: "Please enter unit/street",
                },
                city: {
                    required: "Please enter city",
                },
                state: {
                    required: "Please enter state",
                },
                country: {
                    required: "Please enter country",
                },
                postal_code: {
                    required: "Please enter postal code",
                }
            }
        });

        // Check Password Format
        $.validator.addMethod("pwcheck", function(value) {
            return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/.test(value) // password check

        }, 'Password must contain one upparcase, one lowercase, one special character, min 8 to 10 characters long.');
        
        // Password not containt First and Last Name
        $.validator.addMethod("check_first_last_name", function(value) {
        
            let firstname = $("#first_name").val().toLowerCase();
            let lastname = $("#last_name").val().toLowerCase();
            let password = $("#password").val().toLowerCase();
            var testNames = new RegExp(firstname+'|'+lastname, "gi");
            return !testNames.test(password)
        }, 'Your password can’t include your first or last name.');

        $("#change_password_form").validate({
            rules: {
                old_password: {
                    required: true,
                },
                password: {
                    required: true,
                    pwcheck: true,
                    check_first_last_name: true,
                },
                c_password: {
                    required: true,
                    equalTo: "#password"
                },
            }
        });

        function change_profile() {
            $("#profile_image").trigger('click');
        }

        function change_business_logo() {
            $("#business_logo").trigger('click');
        }

        $("#profile_image").change(function() {
            profile_image_readURL(this);
        });

        $("#business_logo").change(function() {
            business_logo_readURL(this);
        });

        function profile_image_readURL(input) {
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

        function business_logo_readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.business_logo_show_img').attr('src', e.target.result);
                    /* $('.show_img').hide();
                    $('.show_img').fadeIn(650); */
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

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

                switch (component.types[0]) {
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
    </script>
@endsection