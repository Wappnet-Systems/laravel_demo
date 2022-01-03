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
                <p class="text-muted m-b-30"></p>
                <br>
                <div class="table-responsive">
                    <table id="user_table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Transaction Id</th>
                                <th>Transaction Type</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Created Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endsection
        @section('script')
        <script>
            $(document).ready(function() {
                $.fn.dataTableExt.oSort['time-date-sort-pre'] = function(value) {      
                    return Date.parse(value);
                };
                $.fn.dataTableExt.oSort['time-date-sort-asc'] = function(a,b) {      
                    return a-b;
                };
                $.fn.dataTableExt.oSort['time-date-sort-desc'] = function(a,b) {
                    return b-a;
                };
                var table1 = $('#user_table').DataTable({
                    // dom: 'lBfrtip',
                    // buttons: ['excel'],
                    "processing": true,
                    "serverSide": true,
                    "responsive": true,
                    // "stateSave": true,
                    columnDefs : [
                        { type: 'time-date-sort', 
                        targets: [5],
                        }
                    ],
                    order: [[ 5, "desc" ]],
                    "ajax": {
                        url: "<?php echo route('admin.payment_transaction'); ?>",
                        type: "GET",
                    },
                    "columns": [
                        {"taregts": 0,'data' : "get_user_name.first_name",'render' : function(data,index,row){
                            if(row.get_user_name.first_name){
                                return row.get_user_name.first_name+" "+row.get_user_name.last_name;
                            }else{
                                return "";
                            }
                        }},
                        {"taregts": 1, 'data': 'transaction_id'},
                        {"taregts": 2, 'data': 'transaction_type'},
                        {"taregts": 3, 'data': 'type'},
                        {"taregts": 4, 'data': 'amount'},
                        {"taregts": 5, 'data': 'created_at','render' : function(data,index,row){
                            return moment(row.created_at).format("DD-MM-YYYY h:mm A");
                        }},
                    ]

                });
            })
        </script>
        <script>

        </script>
        @endsection
