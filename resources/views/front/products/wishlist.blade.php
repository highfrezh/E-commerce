@extends('layouts.front_layout.front_layout')
@section('content')
<div class="span9">
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li class="active"> Wishlist</li>
    </ul>
    <h3> Wishlist [ <small><span class="totalWishlistItems">{{ (totalWishlistItems())}} </span> item(s)</small>]<a
            href="{{ url('/') }}" class="btn btn-large pull-right"><i class="icon-arrow-left"></i> Continue Shopping
        </a></h3>
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

    <div id="AppendWishlistItems">
        @include('front.products.wishlist_items')
    </div>
</div>
@endsection