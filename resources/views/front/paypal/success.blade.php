<?php use App\Models\Product; ?>
@extends('layouts.front_layout.front_layout')
@section('content')
<div class="span9">
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li class="active">PAYMENT COMFIRMED</li>
    </ul>
    <h5> PAYMENT COMFIRMED </h5>
    <hr class="soft">

    <div align="center">
        <h4>YOUR ORDER PAYMENT HAS BEEN COMFIRMED.</h4>
        <p>Thank for the payment. We will process your order very soon.</p>
        <p>Your order number is {{ Session::get('order_id') }} and total amount paid is ${{
            Session::get('grand_total') }}</p>
    </div>
</div>
@endsection

<?php
Session::forget('grand_total');
Session::forget('order_id');
Session::forget('couponCode');
Session::forget('couponAmount');
?>