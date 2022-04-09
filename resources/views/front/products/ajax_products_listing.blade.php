<?php use App\Models\Product; ?>
<div class="tab-pane  active" id="blockView">
    <ul class="thumbnails">
        @foreach ($categoryProducts as $product)
        <li class="span3">
            <div class="thumbnail" style="height: 320px">
                <a href="{{ url('product/'.$product['id']) }}">
                    @if(isset($product['main_image']))
                    <?php $product_image_path = 'images/product_images/small/'.$product['main_image']?>
                    @else
                    <?php $product_image_path = '';?>
                    @endif
                    @if(!empty($product['main_image']) && file_exists($product_image_path))
                    <img style="width: 150px;" src="{{ asset('images/product_images/small/'.$product['main_image']) }}"
                        alt="">
                    @else
                    <img style="width: 150px;" src="{{ asset('images/product_images/small/no-image.png') }}" alt="">
                    @endif
                </a>
                <div class="caption">
                    <h5>{{ $product['product_name'] }}</h5>
                    <p>
                        {{ $product['brand']['name'] }}
                    </p>
                    <?php $discounted_price = Product::getDiscountedPrice($product['id']); ?>
                    <h4 style="text-align:center">
                        {{-- <a class="btn" href="{{ url('product/'.$product['id']) }}">
                            <i class="icon-zoom-in"></i>
                        </a> --}}
                        <a class="btn" href="#">
                            Add to <i class="icon-shopping-cart"></i>
                        </a>
                        <a class="btn btn-primary" href="#">
                            @if($discounted_price > 0)
                            <del>${{ $product['product_price'] }}</del>
                            <font color="yellow">${{ $discounted_price }}</font>
                            @else
                            ${{ $product['product_price'] }}
                            @endif
                        </a>
                    </h4>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
    <hr class="soft">
</div>