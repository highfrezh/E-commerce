@extends('layouts.front_layout.front_layout')
@section('content')
<div class="span9">
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li class="active">{{ $title }}</li>
    </ul>
    <h3> {{ $title }}</h3>
    <hr class="soft">
    @if (Session::has('error_message'))
    <div class="alert alert-danger" role="alert">
        {{ Session::get('error_message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php Session::forget('error_message');?>
    @endif
    @if (Session::has('success_message'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('success_message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php Session::forget('success_message');?>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger" style="margin-top: 10px">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="row">
        <div class="span4">
            <div class="well">
                Enter your delivery address details.<br><br>
                <form id="deliveryAddressForm" @if(empty($address['id']))
                    action="{{ url('/add-edit-delivery-address') }}" @else
                    action="{{ url('/add-edit-delivery-address/'.$address['id']) }}" @endif method="post">@csrf
                    <div class="control-group">
                        <label class="control-label" for="name">Name</label>
                        <div class="controls">
                            <input class="span3" type="text" id="name" name="name" @if(isset($address['name']))
                                value="{{ $address['name'] }}" @else value="{{ old('name') }}" @endif placeholder="Name"
                                required>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="address">Address</label>
                        <div class="controls">
                            <input class="span3" type="text" id="address" name="address" @if(isset($address['address']))
                                value="{{ $address['address'] }}" @else value="{{ old('address') }}" @endif
                                placeholder="Address">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="city">City</label>
                        <div class="controls">
                            <input class="span3" type="text" id="city" name="city" @if(isset($address['city']))
                                value="{{ $address['city'] }}" @else value="{{ old('city') }}" @endif
                                placeholder="City">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="state">State</label>
                        <div class="controls">
                            <input class="span3" type="text" id="state" name="state" @if(isset($address['state']))
                                value="{{ $address['state'] }}" @else value="{{ old('state') }}" @endif
                                placeholder="State">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="country">Country</label>
                        <div class=" controls">
                            <select class="span3" name="country" id="country">
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                <option value="{{ $country['country_name'] }}"
                                    @if($country['country_name']==$address['country']) selected
                                    @elseif($country['country_name']==old('country')) selected @endif>{{
                                    $country['country_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pincode">Pincode</label>
                        <div class=" controls">
                            <input class="span3" type="text" id="pincode" name="pincode" @if(isset($address['pincode']))
                                value="{{ $address['pincode'] }}" @else value="{{ old('pincode') }}" @endif
                                placeholder="Pincode">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="mobile">Mobile</label>
                        <div class="controls">
                            <input class="span3" type="text" id="mobile" name="mobile" @if(isset($address['mobile']))
                                value="{{ $address['mobile'] }}" @else value="{{ old('mobile') }}" @endif
                                placeholder="Mobile">
                        </div>
                    </div>
                    <div class="controls">
                        <button type="submit" class="btn block">Submit</button>
                        <a href="{{ url('checkout') }}" class="btn block" style="float: right;">Back</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="span1"> &nbsp;</div>
    </div>

</div>
@endsection