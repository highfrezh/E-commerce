@extends('layouts.admin_layout.admin_layout')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>CMS Pages</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">CMS Pages</li>
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
            <form name="cmsPageForm" id="cmsPageForm" @if(empty($cmspage['id']))
                action="{{ url('admin/add-edit-cms-page') }}" @else
                action="{{ url('admin/add-edit-cms-page/'.$cmspage['id']) }}" @endif method="POST"
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
                                    <label for="title">Title*</label>
                                    <input type="text" name="title" class="form-control" id="title"
                                        placeholder="Enter Title" @if(!empty($cmspage['title']))
                                        value="{{ $cmspage['title'] }}" @else value="{{ old('title') }}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="url">URL*</label>
                                    <input type="text" name="url" class="form-control" id="url" placeholder="Enter Url"
                                        @if(!empty($cmspage['url'])) value="{{ $cmspage['url'] }}" @else
                                        value="{{ old('url') }}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="title">Description*</label>
                                    <textarea class="form-control" name="description" id="description" rows="2"
                                        placeholder="Description..."> @if(!empty($cmspage['description']))
                                        {{ $cmspage['description'] }} @else {{ old('description') }} @endif
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control" id="meta_title"
                                        placeholder="Enter Title" @if(!empty($cmspage['meta_title']))
                                        value="{{ $cmspage['meta_title'] }}" @else value="{{ old('meta_title') }}"
                                        @endif>
                                </div>
                                <div class="form-group">
                                    <label for="meta_keyword">Meta Keywords</label>
                                    <input type="text" name="meta_keyword" class="form-control" id="meta_keyword"
                                        placeholder="Meta Keywords" @if(!empty($cmspage['meta_keyword']))
                                        value="{{ $cmspage['meta_keyword'] }}" @else value="{{ old('meta_keyword') }}"
                                        @endif>
                                </div>
                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea class="form-control" name="meta_description" id="meta_description"
                                        rows="2" placeholder="Description..."> @if(!empty($cmspage['meta_description']))
                                        {{ $cmspage['meta_description'] }} @else {{ old('meta_description') }} @endif
                                    </textarea>
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