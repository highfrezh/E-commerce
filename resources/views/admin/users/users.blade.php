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
                        <li class="breadcrumb-item active">Users</li>
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
                            <h3 class="card-title">Users Table</h3>
                            <table align="right">
                                <tr>
                                    <td>
                                        <a href="{{ url('admin/add-edit-user') }}"
                                            style="max-width: 150px; float:right; display: inline-block;"
                                            class="btn btn-block btn-success">Add User
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ url('admin/export-users') }}"
                                            style="max-width: 150px; float:right; display: inline-block;"
                                            class="btn btn-block btn-primary">Export Users
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
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Country</th>
                                        <th>Pincode</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)

                                    <tr>
                                        <td>{{ $user['id'] }}</td>
                                        <td>{{ $user['name'] }}</td>
                                        <td>{{ $user['address'] }}</td>
                                        <td>{{ $user['city'] }}</td>
                                        <td>{{ $user['state'] }}</td>
                                        <td>{{ $user['country'] }}</td>
                                        <td>{{ $user['pincode'] }}</td>
                                        <td>{{ $user['mobile'] }}</td>
                                        <td>{{ $user['email'] }}</td>
                                        <td>
                                            @if($user['status'] === 1)
                                            <a class="updateUserStatus" id="user-{{ $user['id'] }}"
                                                user_id="{{ $user['id'] }}" href="javascript:void(0)"><i status="Active"
                                                    aria-hidden="true" class="fas fa-toggle-on fa-lg"></i></a>
                                            @else
                                            <a class="updateUserStatus" id="user-{{ $user['id'] }}"
                                                user_id="{{ $user['id'] }}" href="javascript:void(0)"><i
                                                    status="Inactive" aria-hidden="true"
                                                    class="fas fa-toggle-off fa-lg"></i></a>
                                            @endif
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