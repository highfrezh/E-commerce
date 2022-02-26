@extends('layouts.admin_layout.admin_layout')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product Image</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Product Image</li>
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
            @if (Session::has('error_message'))
            <div class="alert alert-danger alert-dismissible fade mt-2 show" role="alert">
                {{ Session::get('error_message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span arial-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <form name="addImageForm" id="imageForm" action="{{ url('admin/add-images/'.$productdata['id']) }}"
                method="POST" enctype="multipart/form-data">@csrf
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
                                <input type="hidden" name="product_id" value="{{ $productdata['id'] }}">

                                <div class="form-group">
                                    <label for="product_name">Product Name:</label>&nbsp;
                                    {{ $productdata['product_name'] }}
                                </div>
                                <div class="form-group">
                                    <label for="product_code">Product Code:</label>&nbsp;
                                    {{ $productdata['product_code'] }}
                                </div>
                                <div class="form-group">
                                    <label for="product_color">Product Color:</label>&nbsp;
                                    {{ $productdata['product_color'] }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        @if($productdata['main_image'])
                                        <img style="width: 120px; height:100%"
                                            src="{{ asset('images/product_images/small/'.$productdata['main_image']) }}">
                                        @else
                                        <img style="width: 120px; height:150px;"
                                            src="{{ asset('images/product_images/small/no-image.png') }}" alt="">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field_wrapper">
                                    <div>
                                        <input class="form-control" multiple id="images" name="images[]" type="file"
                                            value="" required />
                                        <div>Multiple Image can be selected</div>
                                        {{-- <a href="javascript:void(0);" class="add_button" title="Add field">Add</a>
                                        --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Add Images</button>
                    </div>
                </div>
            </form>

            <form name="editImageForm" id="editImageForm" action="{{ url('admin/edit-images/'.$productdata['id']) }}"
                method="post">@csrf
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Products Table</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tableData" class="table table-striped table-sm table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; ?>
                                @foreach ($productdata['images'] as $image)
                                <input style="display: none;" type="number" name="ImageId[]" value="{{ $image['id'] }}">
                                <tr>
                                    <td>
                                        {{ $count }}
                                    </td>
                                    <td>
                                        <img style="width: 70px; height:50px;"
                                            src="{{ asset('images/product_images/small/'.$image['image']) }}" alt="">
                                    </td>
                                    <td>
                                        @if($image['status'] === 1)
                                        <a class="updateImageStatus" id="image-{{ $image['id'] }}"
                                            image_id="{{ $image['id'] }}" href="javascript:void(0)"><i status="Active"
                                                aria-hidden="true" class="fas fa-toggle-on fa-lg"></i></a>
                                        @else
                                        <a class="updateImageStatus" id="image-{{ $image['id'] }}"
                                            image_id="{{ $image['id'] }}" href="javascript:void(0)"><i status="Inactive"
                                                aria-hidden="true" class="fas fa-toggle-off fa-lg"></i></a>
                                        @endif
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a title="Delete Image" class="confirmDelete" name="image"
                                            href="{{ url('admin/delete-image/'.$image['id']) }}"><i
                                                class="fas fa-trash text-primary"></i></a>
                                    </td>
                                </tr>
                                <?php $count++?>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update Images</button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>

        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection