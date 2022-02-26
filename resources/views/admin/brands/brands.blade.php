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
                        <li class="breadcrumb-item active">Brands</li>
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
                            <h3 class="card-title">Brands Table</h3>
                            <a href="{{ url('admin/add-edit-brand') }}"
                                style="max-width: 150px; float:right; display: inline-block;"
                                class="btn btn-block btn-success">Add Brand
                            </a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="tableData" class="table table-striped table-sm table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($brands as $brand)

                                    <tr>
                                        <td>{{ $brand->id }}</td>
                                        <td>{{ $brand->name }}</td>
                                        <td>
                                            @if($brand->status === 1)
                                            <a class="updateBrandStatus" id="brand-{{ $brand->id }}"
                                                brand_id="{{ $brand->id }}" href="javascript:void(0)"><i status="Active"
                                                    aria-hidden="true" class="fas fa-toggle-on fa-lg"></i></a>
                                            @else
                                            <a class="updateBrandStatus" id="brand-{{ $brand->id }}"
                                                brand_id="{{ $brand->id }}" href="javascript:void(0)"><i
                                                    status="Inactive" aria-hidden="true"
                                                    class="fas fa-toggle-off fa-lg"></i></a>
                                            @endif

                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a title="Edit brand"
                                                href="{{ url('admin/add-edit-brand/'.$brand->id) }}"><i
                                                    class="fas fa-edit"></i></a>
                                            &nbsp;
                                            <a title="Delete Brand" class="confirmDelete" name="brand"
                                                href="{{ url('admin/delete-brand/'.$brand->id) }}"><i
                                                    class="fas fa-trash"></i></a>
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