@extends('layouts.admin_app')

@section('content')
<style>
    .error {
        color :red;
    }
</style>
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">{{ $page_title }}</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                {{-- <li><a href="{{ route($module_link) }}">{{ $module_title }}</a></li> --}}
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
                        <div class="col-md-12 col-xs-12">
                        <form class="" method="POST" id="client_form" action="{{route('admin.save_client')}}" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                            <label>Title *</label>
                                                <input type="text" class="form-control" name="client_title" id="client_title">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                            <label>Link *</label>
                                                <input type="text" class="form-control" name="client_link" id="client_link">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                            <label>Logo *</label>
                                                <input type="file" class="form-control" name="client_image" id="client_image" accept="image/*">
                                                <span class="help-block"> Please select 150 x 150 image </span>
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
</div>
@endsection


@section('script')
<script>
    jQuery("#client_form").validate({
        rules: {
            client_title : {
                required : true,
            },
            client_link : {
                required : true,
                url: true,
            },
            client_image : {
                required : true,
                extension: "jpg|png|jpeg|svg"
            },
        },
    });

    $(document).ready(function () {

    });
</script>
@endsection
