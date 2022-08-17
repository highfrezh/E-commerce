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
                                        <th>Required Size</th>
                                        <th>Product Code</th>
                                        <th>Return Reason</th>
                                        <th>Comment</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($exchange_requests as $exchange)
                                    <tr>
                                        <td>{{ $exchange['id'] }}</td>
                                        <td><a target="_blank"
                                                href="{{ url('admin/orders/'.$exchange['order_id']) }}">{{
                                                $exchange['order_id'] }}</a></td>
                                        <td>{{ $exchange['user_id'] }}</td>
                                        <td>{{ $exchange['product_size'] }}</td>
                                        <td>{{ $exchange['required_size'] }}</td>
                                        <td>{{ $exchange['product_code'] }}</td>
                                        <td>{{ $exchange['exchange_reason'] }}</td>
                                        <td>{{ $exchange['comment'] }}</td>
                                        <td>{{ date('d-m-Y h:i:s', strtotime($exchange['created_at'])) }}</td>
                                        <td>
                                            <form action="{{ url('admin/exchange-requests/update') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="exchange_id" value="{{ $exchange['id'] }}">
                                                <select name="exchange_status" id="exchange_status"
                                                    class="form-control">
                                                    <option value="Pending" @if($exchange['exchange_status']=="Pending"
                                                        ) selected @endif>Pending</option>
                                                    <option value="Approved"
                                                        @if($exchange['exchange_status']=="Approved" ) selected @endif>
                                                        Approved</option>
                                                    <option value="Rejected"
                                                        @if($exchange['exchange_status']=="Rejected" ) selected @endif>
                                                        Rejected</option>
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