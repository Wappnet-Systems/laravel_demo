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
                <li><a href="{{ route($module_link) }}">{{ $module_title }}</a></li>

            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-md-12">

        </div>
        <div class="col-md-12">

            <div class="white-box">

                <div class="row">
                    <div class="col-sm-12 col-xs-12">
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

                    </div>
                </div>
                @can("client-add")
                    <a href="{{ route('admin.add_client') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Client</a>
                @endcan
                <p class="text-muted m-b-30"></p>
                <br>
                <!--main content area start-->
                <div class="table-responsive">
                    <table id="client_table" class="table table-striped">
                        <thead>
                        <th>Title</th>
                        <th>Link</th>
                        <th>Logo</th>
                        <th>Status</th>
                        <th>Actions</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection


@section('script')
<script>
    $(document).ready(function () {
        $('#client_table').DataTable({
            "processing": true,
                    "serverSide": true,
                    "responsive": true,
                    // "stateSave": true,
                    // "ordering": false,
                    "order": [[0, "ASC"]],
                    "ajax": {
                        url: "<?php echo route('admin.get_client_list'); ?>",
                        type: "GET",
                    },
                    "columns": [
                        {"taregts": 0, 'data': 'client_title'},
                        {"taregts": 1, 'data': 'client_link',
                            "render": function (data, type, row) {
                                return '<a href="'+row.client_link+'">'+row.client_link+'</a>';

                            }
                        },
                        {"taregts": 2, "searchable": false, "orderable": false,
                            "render": function (data, type, row) {
                                if(row.client_image=="")
                                    {
                                        return "";
                                    }
                                    else
                                    {
                                        var img = row.client_image;
                                        return "<a href='#' data-toggle='modal' data-target='#galleryModal'><img width='80' src="+ img +"></a>";
                                    }

                            }
                        },
                        {
                        "taregts": 3,'data': 'status',
                            "render": function(data, type, row) {
                                var id = row.id;
                                var out = '';
                                @can('client-edit')
                                if (row.status == 'Enabled') {
                                    out += '<a href="<?php echo url("admin/change_client_status") ?>' + '/' + id + '/Disabled' + '" class="btn btn-success" title="Change Status">' + row.status + '</a>';
                                } else {
                                    out += '<a href="<?php echo url("admin/change_client_status") ?>' + '/' + id + '/Enabled' + '" class="btn btn-danger" title="Change Status">' + row.status + '</a>';
                                }
                                @endcan
                                return out;
                            }
                        },
                        {"taregts": 4, "searchable": false, "orderable": false,
                        "render": function (data, type, row) {
                            var id = row.id;
                            var out="";
                            @can('client-edit')
                                out = '<a href="<?php echo url("admin/edit_client") ?>'+'/'+id+'" class="btn btn-primary btn-circle" title="Edit Template type"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                            @endcan
                            return out;
                        }
                    },
                    ]
        });
    });

</script>
@endsection
