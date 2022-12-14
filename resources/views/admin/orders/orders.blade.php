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
                        <li class="breadcrumb-item active">Orders</li>
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
                            <h3 class="card-title">Orders Table</h3>
                            <table align="right">
                                <tr>
                                    <td>
                                        <a href="{{ url('admin/export-orders') }}"
                                            style="max-width: 150px; float:right; display: inline-block;"
                                            class="btn btn-block btn-primary">Export Orders
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="tableData" class="table table-striped table-sm table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Order Date</th>
                                        <th>Customer Name</th>
                                        <th>Customer Email</th>
                                        <th>Ordered Products</th>
                                        <th>Order Amount</th>
                                        <th>Order Status</th>
                                        <th>Payment Method</th>
                                        @if($orderModule['edit_access'] ==1 || $orderModule['full_access']
                                        ==1)
                                        <th>Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order['id'] }}</td>
                                        <td>{{ date('d-m-Y', strtotime($order['created_at'])) }}</td>
                                        <td>{{ $order['name'] }}</td>
                                        <td>{{ $order['email'] }}</td>
                                        <td>
                                            @foreach ($order['orders_products'] as $pro)
                                            {{ $pro['product_code'] }} ({{ $pro['product_qty'] }}) <br>
                                            @endforeach
                                        </td>
                                        <td>${{ $order['grand_total'] }}</td>
                                        <td>{{ $order['order_status'] }}</td>
                                        <td>{{ $order['payment_method'] }}</td>
                                        @if($orderModule['edit_access'] ==1 || $orderModule['full_access']
                                        ==1)
                                        <td>
                                            <a title="View Order Details"
                                                href="{{ url('admin/orders/'.$order['id']) }}"><i
                                                    class="fas fa-file"></i></a>&nbsp;&nbsp;
                                            @if($order['order_status'] == "Shipped" || $order['order_status'] ==
                                            "Delivered")
                                            <a title="View Order Invoice"
                                                href="{{ url('admin/view-order-invoice/'.$order['id']) }}"
                                                target="_blank"><i class="fas fa-print"></i></a>&nbsp;&nbsp;
                                            <a title="Print PDF Invoice"
                                                href="{{ url('admin/print-pdf-invoice/'.$order['id']) }}"
                                                target="_blank"><i class="fas fa-file-pdf"></i></a>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
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