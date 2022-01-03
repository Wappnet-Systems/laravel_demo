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
        <div class="col-md-12 col-lg-12 col-sm-12">
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
            <div class="white-box">
                @can('admin-catalogue-add')
                    <a href="{{ route('admin.add_catalogue') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Catalogue</a>
                @endcan
                <p class="text-muted m-b-30"></p>
                <br>
                <div class="table-responsive">
                    <table id="catalogue_table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Images</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($catalogue))
                                @foreach ($catalogue as $key => $value)
                                <tr>
                                    <td>{{$value['name']}}</td>
                                    <td>{{$value['description']}}</td>
                                    <td>{{$value['amount']}}</td>
                                    <td>
                                        @if (count($value['get_catelogue_images']))
                                            @php
                                                $all_images = [];
                                                foreach ($value['get_catelogue_images'] as $key1 => $value1){
                                                    $all_images[$key1] = app(App\Lib\UploadFile::class)->get_s3_file_path("catelogue_images",$value1['image']);
                                                }
                                                $image_string = implode(',',$all_images);
                                                // $image_string = json_encode($all_images);

                                            @endphp
                                            <textarea id="image_{{$value['id']}}" style="display: none;">{{$image_string}}</textarea>
                                            <button type="button" class="btn btn-primary btn-rounded" title="View Images" onclick="show_images('{{$value['id']}}')"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                        @endif
                                    </td>
                                    <td>
                                        @can('admin-catalogue-edit')
                                            @if ($value['status'] == "Enabled")
                                                <a href="<?php echo url('admin/change_catalogue_status') ?>/{{$value['id']}}/Disabled" class="btn btn-success" title="Change Status">{{$value['status']}}</a>
                                            @else
                                                <a href="<?php echo url('admin/change_catalogue_status') ?>/{{$value['id']}}/Enabled" class="btn btn-danger" title="Change Status">{{$value['status']}}</a>
                                            @endif
                                        @endcan
                                    </td>
                                    <td>
                                        @can('admin-catalogue-edit')
                                                <a href="{{route('admin.edit_catalogue',$value['id'])}}" class="btn btn-info btn-rounded" title="Edit Catalogue"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        @endcan
                                        @can('admin-catalogue-delete')
                                        {{-- <a href="{{route('admin.delete_catalogue',$value['id'])}}" class="btn btn-danger btn-rounded" title="Delete Catalogue"><i class="fa fa-times" aria-hidden="true"></i></a> --}}
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="images_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Catelogue Images</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="render_images">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
        @endsection
        @section('script')
        <script>
            $(document).ready(function() {
                var table = $('#catalogue_table').DataTable({
                });
            })
            function delete_confirm(id){
                // alert(id);
                swal({
                    title: "Are you sure?",
                    // text: "You will not be able to recover this imaginary file!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function(isConfirm){
                    if (isConfirm) {
                        window.location = "{{url('delete_catalogue')}}/"+id;
                    } else {
                        swal("Cancelled", "Your record is safe :)", "error");
                    }
                });
            }

            function show_images(id){
                $("#images_model").modal('show');
                var all_images = $("#image_"+id).val();
                var res_array = all_images.split(",");
                $("#render_images").html("");
                var html = '';
                html += '<div id="gallery-content"><div id="gallery-content-center-full isotope"> ';
                $.each( res_array, function(key, obj){
                    html +='<img src="'+obj+'" alt="gallery" class="all studio" width="100" height="100"/>&nbsp;&nbsp;';
                });
                html += '</div></div>';

                $("#render_images").html(html);
            }
        </script>
        @endsection
