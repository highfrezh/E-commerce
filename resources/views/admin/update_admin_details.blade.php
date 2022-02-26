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
                            <h3 class="card-title">Update Admin Details</h3>
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

                        @if ($errors->any())
                        <div class="alert alert-danger mt-2 ">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>

                        @endif

                        <!-- form start -->
                        <form method="post" action="{{ url('/admin/update-admin-details') }}" name="updateAdminDetails"
                            id="updateAdminDetails" enctype="multipart/form-data">@csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email Address</label>
                                    <input value="{{ Auth::guard('admin')->user()->email}}" class="form-control"
                                        readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Admin Type</label>
                                    <input value="{{ Auth::guard('admin')->user()->type}}" class="form-control"
                                        readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Admin Name</label>
                                    {{-- {{ Auth::guard('admin')->user()->name }} for calling admin guard directly --}}
                                    <input class="form-control" value="{{ Auth::guard('admin')->user()->name}}"
                                        name="admin_name" id="name" placeholder="Admin/Subadmin Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mobile</label>
                                    <input type="text" name="admin_mobile"
                                        value="{{ Auth::guard('admin')->user()->mobile}}" id="admin_mobile"
                                        class="form-control" placeholder="Admin mobile number" required>
                                    <div id="chkCurrentPwd"></div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Images</label>
                                    <input type="file" name="admin_image" id="admin_image" class="form-control"
                                        accept="image/*">
                                    @if (!empty(Auth::guard('admin')->user()->image))
                                    <a target="_blank"
                                        href="{{ url('images/admin_images/admin_photos/'.Auth::guard('admin')->user()->image) }}">
                                        ViewImage
                                    </a>
                                    <input type="hidden" name="current_admin_image"
                                        value="{{ Auth::guard('admin')->user()->image }}">
                                    @endif
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