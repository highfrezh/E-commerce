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
                        <li class="breadcrumb-item active">Couponss</li>
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
                            <h3 class="card-title">Coupons Table</h3>
                            <a href="{{ url('admin/add-edit-coupon') }}"
                                style="max-width: 150px; float:right; display: inline-block;"
                                class="btn btn-block btn-success">Add coupon
                            </a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="tableData" class="table table-striped table-sm table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Code</th>
                                        <th>Coupon Type</th>
                                        <th>Amount</th>
                                        <th>Expiry Date</th>
                                        @if($couponModule['edit_access'] ==1 || $couponModule['full_access']
                                        ==1)
                                        <th>Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($coupons as $coupon)

                                    <tr>
                                        <td>{{ $coupon['id'] }}</td>
                                        <td>{{ $coupon['coupon_code'] }}</td>
                                        <td>{{ $coupon['coupon_type'] }}</td>
                                        <td>
                                            {{ $coupon['amount'] }}
                                            @if ($coupon['amount_type'] == "Percentage")
                                            %
                                            @else
                                            Naira
                                            @endif
                                        </td>
                                        <td>{{ $coupon['expiry_date'] }}</td>
                                        <td>
                                            @if($couponModule['edit_access'] ==1 || $couponModule['full_access']
                                            ==1)
                                            @if($coupon['status'] === 1)
                                            <a class="updateCouponStatus" id="coupon-{{ $coupon['id'] }}"
                                                coupon_id="{{ $coupon['id'] }}" href="javascript:void(0)"><i
                                                    status="Active" aria-hidden="true"
                                                    class="fas fa-toggle-on fa-lg"></i></a>
                                            @else
                                            <a class="updateCouponStatus" id="coupon-{{ $coupon['id'] }}"
                                                coupon_id="{{ $coupon['id'] }}" href="javascript:void(0)"><i
                                                    status="Inactive" aria-hidden="true"
                                                    class="fas fa-toggle-off fa-lg"></i></a>
                                            @endif

                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a title="Edit coupon"
                                                href="{{ url('admin/add-edit-coupon/'.$coupon['id']) }}"><i
                                                    class="fas fa-edit"></i></a>
                                            @endif
                                            &nbsp;
                                            @if($couponModule['full_access'] ==1)
                                            <a title="Delete coupon" class="confirmDelete" name="coupon"
                                                href="{{ url('admin/delete-coupon/'.$coupon['id']) }}"><i
                                                    class="fas fa-trash"></i></a>
                                            @endif
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