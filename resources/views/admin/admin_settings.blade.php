@extends('layouts.admin_layout.admin_layout')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Admin Settings</li>
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
                            <h3 class="card-title">Update Password</h3>
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
                        <form method="post" action="{{ url('/admin/update-current-pwd') }}" name="updatePasswordForm"
                            id="updatePasswordForm">@csrf
                            <div class="card-body">
                                <?php /*<div class="form-group">
                                    <label for="exampleInputEmail1">Admin Name</label>
                                    {{-- {{ Auth::guard('admin')->user()->name }} for calling admin guard directly --}}
                                    <input class="form-control" value="{{ $adminDetails->name }}" name="admin_name"
                                        id="admin_name" placeholder="Admin/Subadmin Name">
                                </div>
                                */?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email Address</label>
                                    <input value="{{ $adminDetails->email }}" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Admin Type</label>
                                    <input value="{{ $adminDetails->type }}" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Current Password</label>
                                    <input type="password" name="current_pwd" id="current_pwd" class="form-control"
                                        placeholder="Password" required>
                                    <div id="chkCurrentPwd"></div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">New Password</label>
                                    <input type="password" name="new_pwd" id="new_pwd" class="form-control"
                                        placeholder="Password" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Comfirm Password</label>
                                    <input type="password" name="confirm_pwd" id="comfirm_pwd" class="form-control"
                                        placeholder="Password" required>
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