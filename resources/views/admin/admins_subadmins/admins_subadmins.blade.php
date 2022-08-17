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
                        <li class="breadcrumb-item active">Admins / Sub-Admins</li>
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
                            <h3 class="card-title">Admins / Sub-Admins Table</h3>
                            <a href="{{ url('admin/add-edit-admin-subadmin') }}"
                                style="max-width: 205px; float:right; display: inline-block;"
                                class="btn btn-block btn-success">Add Admin / Sub-Admin
                            </a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="tableData" class="table table-striped table-sm table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins_subadmins as $admin)

                                    <tr>
                                        <td>{{ $admin->id }}</td>
                                        <td>{{ $admin->name }}</td>
                                        <td>{{ $admin->mobile }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td>{{ $admin->type }}</td>
                                        <td>
                                            @if($admin->type != "superadmin")
                                            @if($admin->status=== 1)
                                            <a class="updateAdminStatus" id="admin-{{ $admin->id }}"
                                                admin_id="{{ $admin->id }}" href="javascript:void(0)"><i status="Active"
                                                    aria-hidden="true" class="fas fa-toggle-on fa-lg"></i></a>
                                            @else
                                            <a class="updateAdminStatus" id="admin-{{ $admin->id }}"
                                                admin_id="{{ $admin->id }}" href="javascript:void(0)"><i
                                                    status="Inactive" aria-hidden="true"
                                                    class="fas fa-toggle-off fa-lg"></i></a>
                                            @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($admin->type != "superadmin")
                                            <a title="Set Roles/Permissions"
                                                href="{{ url('admin/update-role/'.$admin->id) }}"><i
                                                    class="fas fa-unlock"></i></a>
                                            &nbsp;
                                            <a title="Edit Admins / Sub-Admins"
                                                href="{{ url('admin/add-edit-admin-subadmin/'.$admin->id) }}"><i
                                                    class="fas fa-edit"></i></a>
                                            &nbsp;
                                            <a title="Delete Admins / Sub-Admins" class="confirmDelete" name="product"
                                                href="{{ url('admin/delete-admin/'.$admin->id) }}"><i
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