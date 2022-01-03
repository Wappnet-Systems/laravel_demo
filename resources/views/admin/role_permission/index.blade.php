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
                @can('role-permission-add')
                    <a href="{{ route('admin.add_role_permission') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Role /Permission</a>
                @endcan
                <p class="text-muted m-b-30"></p>
                <br>
                <div class="table-responsive">
                    <table id="role_table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($roles))
                                @foreach ($roles as $key => $value)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$value['name']}}</td>
                                        <td>
                                            @can('role-permission-edit')
                                                <a href="{{route('admin.edit_role_permission',$value['id'])}}" class="btn btn-info btn-rounded" title="Edit Role / Permission"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            @endcan
                                            @can('role-permission-delete')
                                                <a href="{{route('admin.delete_role_permission',$value['id'])}}" class="btn btn-danger btn-rounded" title="Delete Role / Permission"><i class="fa fa-times" aria-hidden="true"></i></a>
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
        @endsection
        @section('script')
        <script>
            $(document).ready(function() {
                var table = $('#role_table').DataTable({
                });
            })
        </script>
        <script>

        </script>
        @endsection
