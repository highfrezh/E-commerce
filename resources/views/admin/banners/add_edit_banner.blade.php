@extends('layouts.admin_layout.admin_layout')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Banner</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Banner</li>
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
            <form name="bannerForm" id="bannerForm" @if(empty($banner['id']))
                action="{{ url('admin/add-edit-banner') }}" @else
                action="{{ url('admin/add-edit-banner/'.$banner['id']) }}" @endif method="POST"
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
                                    <label for="title">Banner Title</label>
                                    <input type="text" name="title" class="form-control" id="title"
                                        placeholder="Enter Title " @if(!empty($banner['title']))
                                        value="{{ $banner['title'] }}" @else value="{{ old('title') }}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="link">Banner Link</label>
                                    <input type="text" name="link" class="form-control" id="link"
                                        placeholder="Enter Link" @if(!empty($banner['link']))
                                        value="{{ $banner['link'] }}" @else value="{{ old('link') }}" @endif>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="alt">Banner Alternate Text</label>
                                    <input type="text" name="alt" class="form-control" id="alt" placeholder="Enter Alt"
                                        @if(!empty($banner['alt'])) value="{{ $banner['alt'] }}" @else
                                        value="{{ old('alt') }}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="Banner Image">Banner Image</label>
                                    <div class="custom-file mb-3">
                                        <input type="file" class="custom-file-input" id="image" name="image">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                    <div>Recommended Image Size: Width: 1170px, Height:480px</div>
                                    @if (!empty($banner['image']))
                                    <div>
                                        <img style="width: 80px; height:50px; margin-top: 5px;"
                                            src="{{ asset('images/banner_images/'.$banner['image']) }}">

                                    </div>
                                    @endif
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