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
                @can('admin-user-management-add')
                <a href="{{ route('admin.add_user') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add User</a>
                @endcan
                <p class="text-muted m-b-30"></p>
                <br>
                <div class="table-responsive">
                    <table id="user_table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Profile Image</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @if (count($user_list))
                                @foreach ($user_list as $key => $value)
                                    <tr>
                                        <td>
                                            @if(!empty($value->getRoleNames()))
                                                @foreach($value->getRoleNames() as $v)
                                                <label class="badge badge-success">{{ $v }}</label>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>{{$value['first_name']}}</td>
                                        <td>{{$value['last_name']}}</td>
                                        <td>{{$value['email']}}</td>
                                        <td>
                                            @if($value['profile_image'])
                                                @php
                                                $images = app(App\Lib\UploadFile::class)->get_file_path($value['profile_image'],'profile_image');
                                                @endphp
                                                <img src="{{asset('/storage/' . $images)}}" class="img-circle11" alt="" width="80" >
                                            @endif
                                        </td>
                                        <td>
                                            @can('admin-user-management-edit')
                                                @if ($value['status'] == "Enabled")
                                                    <a href="<?php echo url('admin/change_admin_status') ?>/{{$value['id']}}/Disabled" class="btn btn-success" title="Change Status">{{$value['status']}}</a>
                                                @else
                                                    <a href="<?php echo url('admin/change_admin_status') ?>/{{$value['id']}}/Enabled" class="btn btn-danger" title="Change Status">{{$value['status']}}</a>
                                                @endif
                                            @endcan
                                        </td>
                                        <td>
                                            @can('admin-user-management-edit')
                                                <a href="{{route('admin.edit_user',$value['id'])}}" class="btn btn-primary btn-circle"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @endif --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endsection
        @section('script')
        <script>
            $(document).ready(function() {
                jQuery('#relieved_date').datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    format: "dd-mm-yyyy"
                });
                var table = $('#user_table111').DataTable({
                });

                var table1 = $('#user_table').DataTable({
                    // dom: 'lBfrtip',
                    // buttons: ['excel'],
                    "processing": true,
                    "serverSide": true,
                    "responsive": true,
                    "stateSave": true,
                    // "order": [[0, "DESC"]],
                    "ajax": {
                        url: "<?php echo route('admin.get_admin_user_list'); ?>",
                        type: "GET",
                    },
                    "columns": [
                        {"taregts": 0,'data' : "role_name",
                            "render": function (data, type, row) {
                                        return '<label class="badge badge-success">'+row.role_name+'</label>'
                                    }
                        },
                        {"taregts": 1, 'data': 'first_name'},
                        {"taregts": 2, 'data': 'last_name'},
                        {"taregts": 3, 'data': 'email'},
                        {"taregts": 4,"searchable": false, "orderable": false,
                                "render": function (data, type, row) {
                                    if(row.profile_image=="")
                                    {
                                        return "";
                                    }
                                    else
                                    {
                                        var img = row.profile_image;
                                        return "<a href='#' data-toggle='modal' data-target='#galleryModal'><img width='80' src="+ img +"></a>";
                                    }
                                }
                        },
                        {
                        "taregts": 5,'data' : "status",
                        "render": function(data, type, row) {
                            var id = row.id;
                            var out = '';
                            @can('admin-user-management-edit')
                            if (row.status == 'Enabled') {
                                out += '<a href="<?php echo url("admin/change_admin_status") ?>' + '/' + id + '/Disabled' + '" class="btn btn-success" title="Change Status">' + row.status + '</a>';
                            } else {
                                out += '<a href="<?php echo url("admin/change_admin_status") ?>' + '/' + id + '/Enabled' + '" class="btn btn-danger" title="Change Status">' + row.status + '</a>';
                            }
                            @endcan
                            return out;
                        }
                    },
                        {"taregts": 6, "searchable": false, "orderable": false,
                        "render": function (data, type, row) {
                            var id = row.id;
                            var out="";
                            @can('admin-user-management-edit')
                                out = '<a href="<?php echo url("admin/edit_user") ?>'+'/'+id+'" class="btn btn-primary btn-circle" title="Edit Admin User"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                            @endcan
                            return out;
                        }
                    },
                    ]

                });
            })
        </script>
        <script>

        </script>
        @endsection
