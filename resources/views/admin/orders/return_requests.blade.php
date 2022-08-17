@extends('layouts.admin_layout.admin_layout')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Catalogues</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Return Requests</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if (Session::has('success_message'))
                    <div class="alert alert-success alert-dismissible fade mt-2 show" role="alert">
                        {{ Session::get('success_message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span arial-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Return Requests</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="tableData" class="table table-striped table-sm table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Order ID</th>
                                        <th>User ID</th>
                                        <th>Product Size</th>
                                        <th>Product Code</th>
                                        <th>Return Reason</th>
                                        <th>Comment</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($return_requests as $request)
                                    <tr>
                                        <td>{{ $request['id'] }}</td>
                                        <td><a target="_blank" href="{{ url('admin/orders/'.$request['order_id']) }}">{{
                                                $request['order_id'] }}</a></td>
                                        <td>{{ $request['user_id'] }}</td>
                                        <td>{{ $request['product_size'] }}</td>
                                        <td>{{ $request['product_code'] }}</td>
                                        <td>{{ $request['return_reason'] }}</td>
                                        <td>{{ $request['comment'] }}</td>
                                        <td>{{ date('d-m-Y h:i:s', strtotime($request['created_at'])) }}</td>
                                        <td>
                                            <form action="{{ url('admin/return-requests/update') }}" method="post">@csrf
                                                <input type="hidden" name="return_id" value="{{ $request['id'] }}">
                                                <select name="return_status" id="return_status" class="form-control">
                                                    <option value="Pending" @if($request['return_status']=="Pending" )
                                                        selected @endif>Pending</option>
                                                    <option value="Approved" @if($request['return_status']=="Approved" )
                                                        selected @endif>Approved</option>
                                                    <option value="Rejected" @if($request['return_status']=="Rejected" )
                                                        selected @endif>Rejected</option>
                                                </select>
                                                <input class="btn-small btn-success" style="margin-top:5px;"
                                                    type="submit" value="Update">
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection