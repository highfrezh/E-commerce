<?php use App\Models\Product; ?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Product</th>
            <th colspan="2">Description</th>
            <th>View/Delete</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        <?php $total_price = 0; ?>
        @foreach($userWishlistItems as $item)
        <tr>
            <td> <img width="60" src="{{ asset('images/product_images/small/'.$item['product']['main_image']) }}"
                    alt=""></td>
            <td colspan="2">{{ $item['product']['product_name'] }}({{ $item['product']['product_code'] }})<br>
                Color : {{ $item['product']['product_color'] }}
            </td>
            <td>
                <div class="input-append">
                    <a target="_blank" href="{{ url('product/'.$item['product']['id']) }}"><button
                            class="btn btnItemUpdate" type="button" title="view product">
                            <i class="icon-file"></i>
                        </button></a>
                    <button class="btn btn-danger wishlistItemDelete" type="button" data-wishlistid="{{ $item['id'] }}"
                        title="delete product">
                        <i class="icon-remove icon-white"></i>
                    </button>
                </div>
            </td>
            <td>${{ $item['product']['product_price'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>