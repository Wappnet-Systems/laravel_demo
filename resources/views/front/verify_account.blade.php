@extends('front.layouts.app')

@section('content')

<section class="login-section bg-off-white">
    <div class="container main-container bg-img-contain" style="background-image: url('front_asset/assets/images/worldcup_icon_opacity.svg');">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 mx-auto">
                <div class="user-wrapper bg-white box-shadow-style text-center">
                    <!-- Start Login Form -->
                    
                    @if ($status == "error")
                        <div class="alert alert-danger alert-dismissable">
                            {{ $message }}
                        </div>
                    @endif
                    @if ($status == "success")
                        <div class="alert alert-success alert-dismissable">
                            {{ $message }}
                        </div>
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

</script>
@endsection
