@extends('layouts.admin_layout.admin_layout')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product Attribute</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Product Attribute</li>
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
            <form name="addAttributeForm" id="attributeForm"
                action="{{ url('admin/add-attributes/'.$productdata['id']) }}" method="POST">@csrf
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
                                        <input id="size" name="size[]" type="text" value="" placeholder="Size"
                                            style="width: 100px;" required />
                                        <input id="sku" name="sku[]" type="text" value="" placeholder="SKU"
                                            style="width: 100px;" required />
                                        <input id="price" name="price[]" type="number" value="" placeholder="Price"
                                            style="width: 100px;" required />
                                        <input id="stock" name="stock[]" type="number" value="" placeholder="Stock"
                                            style="width: 100px;" required />
                                        <a href="javascript:void(0);" class="add_button" title="Add field">Add</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Add Attributes</button>
                    </div>
                </div>
            </form>

            <form name="editAttributeForm" id="editAttributeForm"
                action="{{ url('admin/edit-attributes/'.$productdata['id']) }}" method="post">@csrf
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
                                    <th>Size</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; ?>
                                @foreach ($productdata['attributes'] as $attribute)
                                <input style="display: none;" type="number" name="attrId[]"
                                    value="{{ $attribute['id'] }}">
                                <tr>
                                    <td>
                                        {{ $count }}
                                    </td>
                                    <td>{{ $attribute['size']}}</td>
                                    <td>{{ $attribute['sku']}}</td>
                                    <td>
                                        <input type="number" name="price[]" value="{{ $attribute['price']}}">
                                    </td>
                                    <td>
                                        <input type="number" name="stock[]" value="{{ $attribute['stock']}}">
                                    </td>
                                    <td>
                                        @if($attribute['status'] === 1)
                                        <a class="updateAttributeStatus" id="attribute-{{ $attribute['id'] }}"
                                            attribute_id="{{ $attribute['id'] }}" href="javascript:void(0)"><i
                                                status="Active" aria-hidden="true"
                                                class="fas fa-toggle-on fa-lg"></i></a>
                                        @else
                                        <a class="updateAttributeStatus" id="attribute-{{ $attribute['id'] }}"
                                            attribute_id="{{ $attribute['id'] }}" href="javascript:void(0)"><i
                                                status="Inactive" aria-hidden="true"
                                                class="fas fa-toggle-off fa-lg"></i></a>
                                        @endif
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a title="Delete Attribute" class="confirmDelete" name="attribute"
                                            href="{{ url('admin/delete-attribute/'.$attribute['id']) }}"><i
                                                class="fas fa-trash text-primary"></i></a>
                                    </td>
                                </tr>
                                <?php $count++?>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update Attributes</button>
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