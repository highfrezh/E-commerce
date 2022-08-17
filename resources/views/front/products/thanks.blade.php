<?php use App\Models\Product; ?>
@extends('layouts.front_layout.front_layout')
@section('content')
<div class="span9">
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li class="active">THANKS</li>
    </ul>
    <h5> THANKS </h5>
    <hr class="soft">

    <div align="center">
        <h4>YOUR ORDER HAS PLACED SUCCESSFULLY</h4>
        <p>Your order number is {{ Session::get('order_id') }} and grand total is ${{ Session::get('grand_total') }}</p>
    </div>
</div>
@endsection

<?php
Session::forget('grand_total');
Session::forget('order_id');
Session::forget('couponCode');
Session::forget('couponAmount');
?>