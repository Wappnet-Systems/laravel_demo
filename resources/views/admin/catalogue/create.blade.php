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
                <li><a href="{{ route($module_link) }}">{{ $module_title }}</a></li>
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
                        <form class="" method="POST" id="catelogue_form" action="{{route('admin.save_catelogue')}}" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                            <label>Name*</label>
                                                <input type="text" class="form-control" name="name" id="name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                            <label>Description*</label>
                                                <textarea name="description" class="form-control" id="description" cols="" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                            <label>Amount*</label>
                                                <input type="text" class="form-control" name="amount" id="amount">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                            <label>Image*</label>
                                                <input type="file" class="form-control" name="images[]" id="images"  accept="image/*">
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
    jQuery("#catelogue_form").validate({
        rules: {
            name : {
                required : true,
            },
            description : {
                required : true,
            },
            "images[]" : {
                required : true,
                extension: "png|jpg|jpeg|gif"
            },
            amount : {
                required : true,
                number: true,
            },
        },
    });

    $(document).ready(function () {

    });
</script>
@endsection
