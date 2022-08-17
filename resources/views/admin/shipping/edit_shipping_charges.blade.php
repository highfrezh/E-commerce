@extends('layouts.admin_layout.admin_layout')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shipping Charges</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Shipping Charges</li>
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
            {{ Session::forget('success_message') }}
            @endif
            <form name="shippingForm" id="shippingForm"
                action="{{ url('admin/edit-shipping-charges/'.$shippingDetails['id']) }}" method="POST"
                enctype="multipart/form-data">@csrf
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                        {{--
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div> --}}
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country_name">Country Name</label>
                                    <input type="text" class="form-control" id="country_name" placeholder="Country Name"
                                        value="{{ $shippingDetails['country'] }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="0_500g">Shipping Charges (0_500g)</label>
                                    <input type="text" name="0_500g" class="form-control" id="0_500g"
                                        placeholder="Shipping Charges" @if(!empty($shippingDetails['0_500g']))
                                        value="{{ $shippingDetails['0_500g'] }}" @else value="{{ old('0_500g') }}"
                                        @endif>
                                </div>
                                <div class="form-group">
                                    <label for="501_1000g">Shipping Charges (501_1000g)</label>
                                    <input type="text" name="501_1000g" class="form-control" id="501_1000g"
                                        placeholder="Shipping Charges" @if(!empty($shippingDetails['501_1000g']))
                                        value="{{ $shippingDetails['501_1000g'] }}" @else value="{{ old('501_1000g') }}"
                                        @endif>
                                </div>
                                <div class="form-group">
                                    <label for="1001_2000g">Shipping Charges (1001_2000g)</label>
                                    <input type="text" name="1001_2000g" class="form-control" id="1001_2000g"
                                        placeholder="Shipping Charges" @if(!empty($shippingDetails['1001_2000g']))
                                        value="{{ $shippingDetails['1001_2000g'] }}" @else
                                        value="{{ old('1001_2000g') }}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="2001_5000g">Shipping Charges (2001_5000g)</label>
                                    <input type="text" name="2001_5000g" class="form-control" id="2001_5000g"
                                        placeholder="Shipping Charges" @if(!empty($shippingDetails['2001_5000g']))
                                        value="{{ $shippingDetails['2001_5000g'] }}" @else
                                        value="{{ old('2001_5000g') }}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="above_5000g">Shipping Charges (above_5000g)</label>
                                    <input type="text" name="above_5000g" class="form-control" id="above_5000g"
                                        placeholder="Shipping Charges" @if(!empty($shippingDetails['above_5000g']))
                                        value="{{ $shippingDetails['above_5000g'] }}" @else
                                        value="{{ old('above_5000g') }}" @endif>
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