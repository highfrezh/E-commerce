@extends('layouts.admin_layout.admin_layout')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Admin / Sub-Admin</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if ($errors->any())
            <div class="alert alert-danger mt-2 ">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
            <form name="adminForm" id="adminForm" @if(empty($admindata['id']))
                action="{{ url('admin/add-edit-admin-subadmin') }}" @else
                action="{{ url('admin/add-edit-admin-subadmin/'.$admindata['id']) }}" @endif method="POST"
                enctype="multipart/form-data">@csrf
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="admin_name">Admin Name</label>
                                    <input type="text" name="admin_name" class="form-control" id="admin_name"
                                        placeholder="Enter  Name" @if(!empty($admindata['name']))
                                        value="{{ $admindata['name'] }}" @else value="{{ old('admin_name') }}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="admin_mobile">Admin Mobile</label>
                                    <input type="text" name="admin_mobile" class="form-control" id="admin_mobile"
                                        placeholder="Enter Mobile" @if(!empty($admindata['mobile']))
                                        value="{{ $admindata['mobile'] }}" @else value="{{ old('admin_mobile') }}"
                                        @endif>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="admin_email">Admin Email</label>
                                    <input @if($admindata['id'] !="" ) disabled @else required @endif type="email"
                                        name="admin_email" class="form-control" id="admin_email"
                                        placeholder="Enter  Name" @if(!empty($admindata['email']))
                                        value="{{ $admindata['email'] }}" @else value="{{ old('admin_email') }}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="admin_mobile">Admin Type</label>
                                    <select @if($admindata['id'] !="" ) disabled @else required @endif name="admin_type"
                                        id="admin_type" class="form-control select2" style="width: 100%;">
                                        <option>Select</option>
                                        <option value="admin" @if(!empty($admindata['type']) &&
                                            $admindata['type']=="admin" ) selected @endif>
                                            Admin
                                        </option>
                                        <option value="subadmin" @if(!empty($admindata['type']) &&
                                            $admindata['type']=="subadmin" ) selected @endif>
                                            Sub-Admin
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="admin_image">Admin Image</label>
                                    <div class="custom-file mb-3">
                                        <input type="file" class="custom-file-input" id="admin_image"
                                            name="admin_image">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                    @if (!empty($admindata['image']))
                                    <a target="_blank"
                                        href="{{ url('images/admin_images/admin_photos/'.$admindata['image']) }}">
                                        ViewImage
                                    </a>
                                    <input type="hidden" name="current_admin_image" value="{{ $admindata['image'] }}">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="admin_password">Admin Password</label>
                                    <input type="password" name="admin_password" class="form-control"
                                        id="admin_password" placeholder="Enter Password">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection