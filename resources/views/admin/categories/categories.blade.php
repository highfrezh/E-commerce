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
                        <li class="breadcrumb-item active">Categories</li>
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
                            <h3 class="card-title">Categories Table</h3>
                            <a href="{{ url('admin/add-edit-category') }}" style="max-width: 150px; float:right; 
                            display: inline-block;" class="btn btn-block btn-success">Add Category</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="tableData" class="table table-striped table-sm table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category</th>
                                        <th>Parent Category</th>
                                        <th>Section</th>
                                        <th>Url</th>
                                        @if($categoryModule['edit_access'] ==1 || $categoryModule['full_access'] ==1)
                                        <th>Status</th>
                                        @endif
                                        @if($categoryModule['edit_access'] ==1 || $categoryModule['full_access'] ==1)
                                        <th>Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                    @if (!isset($category->parentcategory->category_name))
                                    <?php $parent_category = "Root"; ?>
                                    @else
                                    <?php $parent_category = $category->parentcategory->category_name?>
                                    @endif
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->category_name }}</td>
                                        <td>{{ $parent_category }}</td>
                                        <td>{{ $category->section->name }}</td>
                                        <td>{{ $category->url }}</td>
                                        <td>
                                            @if($categoryModule['edit_access'] ==1 || $categoryModule['full_access']
                                            ==1)
                                            @if($category->status === 1)
                                            <a class="updateCategoryStatus" id="category-{{ $category->id }}"
                                                category_id="{{ $category->id }}" href="javascript:void(0)"><i
                                                    status="Active" aria-hidden="true"
                                                    class="fas fa-toggle-on fa-lg"></i></a>
                                            @else
                                            <a class="updateCategoryStatus" id="category-{{ $category->id }}"
                                                category_id="{{ $category->id }}" href="javascript:void(0)"><i
                                                    status="Inactive" aria-hidden="true"
                                                    class="fas fa-toggle-off fa-lg"></i></a>
                                            @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($categoryModule['edit_access'] ==1 || $categoryModule['full_access']
                                            ==1)
                                            <a href="{{ url('admin/add-edit-category/'.$category->id) }}"><i
                                                    class="fas fa-edit"></i></a>
                                            @endif
                                            &nbsp;&nbsp;
                                            @if($categoryModule['full_access'] ==1)
                                            <a class="confirmDelete" name="Category"
                                                href="{{ url('admin/delete-category/'.$category->id) }}"><i
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