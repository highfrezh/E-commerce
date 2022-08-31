<?php use App\Models\Product; ?>
@extends('layouts.front_layout.front_layout')
@section('content')
<div class="span9">
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li class="active"> Checkout</li>
    </ul>
    <h3> CHECKOUT [ <small><span class="totalCartItems">{{
                totalCartItems() }} </span> item(s)</small>]<a href="{{ url('/cart') }}"
            class="btn btn-large pull-right"><i class="icon-arrow-left"></i> Back to Cart</a></h3>
    <hr class="soft">
    @if (Session::has('error_message'))
    <div class="alert alert-danger alert-dismissible show" role="alert">
        {{ Session::get('error_message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span arial-hidden="true">&times;</span>
        </button>
    </div>
    <?php Session::forget('error_message');?>
    @endif
    @if (Session::has('success_message'))
    <div class="alert alert-success alert-dismissible show" role="alert">
        {{ Session::get('success_message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span arial-hidden="true">&times;</span>
        </button>
    </div>
    <?php Session::forget('success_message');?>
    @endif

    <form action="{{ url('/checkout') }}" name="checkoutForm" id="checkoutForm" method="POST">@csrf
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>
                        <strong>DELIVERY ADDRESSES </strong>
                        <a href="{{ url('add-edit-delivery-address') }}" class="btn btn-success">Add Address</a>
                    </td>
                </tr>
                @foreach($deliveryAddresses as $address)
                <tr>
                    <td>
                        <div class="control-group" style="float: left; margin-top: -2px; margin-right: 5px;">
                            <input type="radio" id="address{{ $address['id'] }}" name="address_id"
                                value="{{ $address['id'] }}" shipping_charges="{{ $address['shipping_charges'] }}"
                                gst_charges="{{ $address['gst_charges'] }}" total_price="{{ $total_price }}"
                                coupon_amount="{{ Session::get('couponAmount') }}">
                        </div>
                        <div class="control-group">
                            <label class="control-label">{{ $address['name'] }}, {{ $address['address'] }}, {{
                                $address['city'] }}, {{ $address['state'] }}, {{ $address['country'] }} ({{
                                $address['mobile'] }})</label>
                        </div>
                    </td>
                    <td><a href="{{ url('add-edit-delivery-address/'.$address['id']) }}">Edit</a> | <a
                            href="{{ url('delete-delivery-address/'.$address['id']) }}" class="addressDelete">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th colspan="2">Description</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Category/Product <br> Discount</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $total_price = 0; ?>
                @foreach($userCartItems as $item)
                <?php $attrPrice = Product::getDiscountedAttrPrice($item['product_id'], $item['size']);?>
                <tr>
                    <td> <img width="60"
                            src="{{ asset('images/product_images/small/'.$item['product']['main_image']) }}" alt="">
                    </td>
                    <td colspan="2">{{ $item['product']['product_name'] }}({{ $item['product']['product_code'] }})<br>
                        Color : {{ $item['product']['product_color'] }} <br>
                        Size: {{ $item['size'] }}
                    </td>
                    <td>
                        <div>
                            {{ $item['quantity'] }}
                        </div>
                    </td>
                    <td>${{ $attrPrice['product_price'] * $item['quantity'] }}</td>
                    <td>${{ $attrPrice['discount'] * $item['quantity'] }}</td>
                    <td>$ {{ $attrPrice['final_price'] * $item['quantity'] }}</td>
                </tr>
                <?php $total_price = $total_price + ($attrPrice['final_price'] * $item['quantity']); ?>
                @endforeach
                <tr>
                    <td colspan="6" style="text-align:right">Sub Total: </td>
                    <td>${{ $total_price }}</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:right">Coupon Discount: </td>
                    <td class="couponAmount">
                        @if(Session::has('couponAmount')) -
                        ${{ Session::get('couponAmount') }}
                        @else
                        $0.00
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:right">Shipping Charges: </td>
                    <td class="shipping_charges">$0</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:right">GST Charges: </td>
                    <td class="gst_charges">${{ $totalGST }}</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:right"><strong>GRAND TOTAL (${{ $total_price }} - <span
                                class="couponAmount">@if(Session::has('couponAmount'))
                                ${{ Session::get('couponAmount') }}
                                @else
                                $0.00
                                @endif</span> + <span class="gst_charges">${{ $totalGST }}</span> + <span
                                class="shipping_charges">$0</span>) =</strong>
                    </td>
                    <td class="label label-important" style="display:block"> <strong class="grand_total">${{
                            $grand_total =
                            $total_price + $totalGST -
                            Session::get('couponAmount')
                            }}
                            <?php Session::put('grand_total', $grand_total);?>
                        </strong></td>
                </tr>
            </tbody>
        </table>

        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>
                        <form id="ApplyCoupon" method="POST" action="javascript:void(0);" class="form-horizontal"
                            @if(Auth::check()) user="1" @endif>@csrf
                            <div class="control-group">
                                <label class="control-label"><strong> PAYMENT GATEWAY: </strong> </label>
                                <div class="controls">
                                    <span>
                                        <input type="radio" name="payment_gateway" id="COD"
                                            value="COD"><strong>COD</strong>
                                        &nbsp;&nbsp;
                                        <input type="radio" name="payment_gateway" id="Paypal"
                                            value="Paypal"><strong>Paypal</strong>
                                        &nbsp;&nbsp;
                                        <input type="radio" name="payment_gateway" id="Payumoney"
                                            value="Payumoney"><strong>Payumoney</strong>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>

            </tbody>
        </table>
        <a href="{{ url('/cart') }}" class="btn btn-large"><i class="icon-arrow-left"></i> Back to Cart</a>
        <button type="submit" class="btn btn-large pull-right">Place Order <i class="icon-arrow-right"></i></button>
    </form>

</div>
@endsection