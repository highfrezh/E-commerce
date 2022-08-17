@extends('layouts.admin_layout.admin_layout')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Coupon</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Coupon</li>
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
            <form name="couponForm" id="couponForm" @if(empty($coupon['id']))
                action="{{ url('admin/add-edit-coupon') }}" @else
                action="{{ url('admin/add-edit-coupon/'.$coupon['id']) }}" @endif method="POST"
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
                                @if(empty($coupon['coupon_code']))
                                <div class="form-group">
                                    <label for="coupon_option">Coupon Option</label><br>
                                    <span><input type="radio" name="coupon_option" id="AutomaticCoupon"
                                            value="Automatic" checked>
                                        Automatic</span>
                                    &nbsp;&nbsp;
                                    <span><input type="radio" name="coupon_option" id="ManualCoupon" value="Manual">
                                        Manual</span>
                                    &nbsp;
                                </div>
                                <div class="form-group" style="display: none;" id="couponField">
                                    <label for="coupon_code">Coupon Code</label>
                                    <input type="text" name="coupon_code" class="form-control" id=""
                                        placeholder="Coupon Code">
                                </div>
                                @else
                                <input type="hidden" name="coupon_option" value="{{ $coupon['coupon_option'] }}">
                                <input type="hidden" name="coupon_code" value="{{ $coupon['coupon_code'] }}">
                                <div class="form-group" id="couponField">
                                    <label for="coupon_code">Coupon Code: </label>
                                    <span>{{ $coupon['coupon_code'] }}</span>
                                </div>
                                @endif
                                <div class="form-group">
                                    <label for="coupon_type">Coupon Type</label><br>
                                    <span><input type="radio" name="coupon_type" value="Multiple Times"
                                            @if(isset($coupon['coupon_type']) &&
                                            $coupon['coupon_type']=="Multiple Times" ) checked
                                            @elseif(!isset($coupon['coupon_type'])) checked @endif>
                                        Multiple Times</span>
                                    &nbsp;&nbsp;
                                    <span><input type="radio" name="coupon_type" value="Single Times"
                                            @if(isset($coupon['coupon_type']) && $coupon['coupon_type']=="Single Times"
                                            ) checked @endif>
                                        Single Times</span>
                                    &nbsp;
                                </div>
                                <div class="form-group">
                                    <label for="amount_type">Amount Type</label><br>
                                    <span><input type="radio" name="amount_type" value="Percentage"
                                            @if(isset($coupon['amount_type']) && $coupon['amount_type']=="Percentage" )
                                            checked @elseif(!isset($coupon['amount_type'])) checked @endif>
                                        Percentage</span>
                                    &nbsp;(in %)&nbsp;&nbsp;
                                    <span><input type="radio" name="amount_type" value="Fixed"
                                            @if(isset($coupon['amount_type']) && $coupon['amount_type']=="Fixed" )
                                            checked @endif>
                                        Fixed</span>
                                    &nbsp;(in USD)
                                </div>
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" name="amount" class="form-control" id="" placeholder="Amount"
                                        required @if(isset($coupon['amount'])) value="{{ $coupon['amount'] }}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="categories">Select Categories</label>
                                    <select name="categories[]" class="form-control select2" multiple required>
                                        <option>Select</option>
                                        @foreach ($categories as $section)
                                        <optgroup label="{{ $section['name'] }}"></optgroup>
                                        @foreach ($section['categories'] as $category)
                                        <option value="{{ $category['id'] }}" @if(in_array($category['id'],$selCats))
                                            selected @endif>
                                            &nbsp;&nbsp;&nbsp;--&nbsp;&nbsp;{{
                                            $category['category_name'] }}</option>
                                        @foreach ($category['subcategories'] as $subcategory)
                                        <option value="{{ $subcategory['id'] }}" @if(in_array($category['id'],$selCats))
                                            selected @endif>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--&nbsp;&nbsp;{{
                                            $subcategory['category_name'] }}</option>
                                        @endforeach
                                        @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="users">Select Users</label>
                                    <select name="users[]" class="form-control select2" multiple
                                        data-live-search="true">
                                        <option>Select</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user['email'] }}" @if(in_array($user['email'],$selUsers))
                                            selected @endif>{{ $user['email'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="coupon_code">Expiry Date</label>
                                    <input type="date" name="expiry_date" class="form-control" id=""
                                        placeholder="Expiry Date" required @if(isset($coupon['expiry_date']))
                                        value="{{ $coupon['expiry_date'] }}" @endif>
                                </div>
                            </div>

                            <div class="col-md-6">
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