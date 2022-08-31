<?php use App\Models\Product; ?>
@extends('layouts.front_layout.front_layout')
@section('content')
<div class="span9">
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li class="active">PAYPAL</li>
    </ul>
    <h5> PAYPAL </h5>
    <hr class="soft">

    <div align="center">
        <h4>YOUR ORDER HAS PLACED</h4>
        <p>Your order number is {{ Session::get('order_id') }} and total payable amount is ${{
            Session::get('grand_total') }}</p>
        <p>Please make payment by clicking on below Payment button </p>
        <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="POST">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="sb-hbrci16324923@business.example.com">
            <input type="hidden" name="item_name" value="{{ Session::get('order_id') }}">
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="amount" value="{{ round(Session::get('grand_total'),2) }}">
            <input type="hidden" name="first_name" value="{{ $nameArr[0] }}">
            <input type="hidden" name="last_name" value="{{ $nameArr[1] }}">
            <input type="hidden" name="address1" value="{{ $orderDetails['address'] }}">
            <input type="hidden" name="address2" value="">
            <input type="hidden" name="city" value="{{ $orderDetails['city']}}">
            <input type="hidden" name="state" value="{{ $orderDetails['state'] }}">
            <input type="hidden" name="zip" value="{{ $orderDetails['pincode'] }}">
            <input type="hidden" name="email" value="{{ $orderDetails['email'] }}">
            <input type="hidden" name="country" value="{{ $orderDetails['country'] }}">
            <input type="hidden" name="return" value="{{ url('paypal/success') }}">
            <input type="hidden" name="cancelreturn" value="{{ url('paypal/fail') }}">
            <input type="hidden" name="notify_url" value="{{ url('paypal/ipn') }}">
            <input type="image" src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_paynow_107x26.png"
                alt="Pay Now">
            <img src="https://paypalobjects.com/en_US/i/scr/pixel.gif" weight="1" height="1">
        </form>
    </div>
</div>
@endsection

<?php
Session::forget('couponCode');
Session::forget('couponAmount');
?>