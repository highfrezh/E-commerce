@extends('layouts.front_layout.front_layout')
@section('content')
<div class="span9">
    <ul class="breadcrumb">
        <li><a href="{{ url('/') }}">Home</a><span class="divider"></span></li>
        <li class="active"><a href="{{ url('/orders') }}">Orders</a></li>
    </ul>
    <h3>Orders</h3>
    @if (Session::has('success_message'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success_message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span arial-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (Session::has('error_message'))
    <div class="alert alert-danger" role="alert">
        {{ Session::get('error_message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span arial-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <hr class="soft">

    <div class="row">
        <div class="span8">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>Order ID</th>
                    <th>Order Products</th>
                    <th>Payment Method</th>
                    <th>Grand Total</th>
                    <th>Created on</th>
                    <th></th>
                </tr>
                @foreach ($orders as $order)
                <tr>
                    <td><a href="{{ url('orders/'.$order['id']) }}">{{ $order['id'] }}</a></td>
                    <td>
                        @foreach ($order['orders_products'] as $pro)
                        {{ $pro['product_code'] }} <br>
                        @endforeach
                    </td>
                    <td>{{ $order['payment_method'] }}</td>
                    <td>${{ $order['grand_total'] }}</td>
                    <td>{{ date('d-m-Y', strtotime($order['created_at'])) }}</td>
                    <td><a href="{{ url('orders/'.$order['id']) }}">View Details</a></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection