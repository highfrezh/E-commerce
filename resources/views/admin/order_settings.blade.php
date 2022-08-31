@extends('layouts.admin_layout.admin_layout')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Order Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Order Settings</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-9">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Update Order Settings</h3>
                        </div>
                        <!-- /.card-header -->
                        @if (Session::has('error_message'))
                        <div class="alert alert-danger alert-dismissible fade mt-2 show" role="alert">
                            {{ Session::get('error_message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span arial-hidden="true">&times;</span>
                            </button>
                        </div>

                        @endif
                        @if (Session::has('success_message'))
                        <div class="alert alert-success alert-dismissible fade mt-2 show" role="alert">
                            {{ Session::get('success_message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span arial-hidden="true">&times;</span>
                            </button>
                        </div>

                        @endif
                        <!-- form start -->
                        <form method="post" action="{{ url('/admin/order-setting') }}" name="updatePasswordForm"
                            id="updateOrderSettings">@csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="minCartValue">Min Cart Value</label>
                                    <input class="form-control" value="{{ $orderSettings['min_cart_value']}}"
                                        name="min_cart_value" id="min_cart_value" placeholder="Min Cart Value">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="maxCartValue">Max Cart Value</label>
                                    <input class="form-control" value="{{ $orderSettings['max_cart_value']}}"
                                        name="max_cart_value" id="max_cart_value" placeholder="Max Cart Value">
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->

                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
</div>
<!-- /.content-wrapper -->

@endsection