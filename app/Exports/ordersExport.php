<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrdersProduct;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ordersExport implements WithHeadings, FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // for exporting orders
        // return Order::all();
        $ordersData = Order::select('id','user_id','name','address','city','state','country','mobile',
        'email','order_status','payment_method','payment_gateway','grand_total')
        ->orderBy('id','Desc')->get();
        foreach ($ordersData as $key => $value) {
            $orderItems = OrdersProduct::select('id','product_code','product_name','product_color','product_size',
            'product_price','product_qty')->where('order_id',$value['id'])->get()->toArray();
            // echo "<pre>"; print_r($orderItems); die;
            $product_codes = "";
            $product_names = "";
            $product_colors = "";
            $product_sizes = "";
            $product_prices = "";
            $product_quantities = "";
            foreach ($orderItems as $item) {
                $product_codes .= $item['product_code'].",";
                $product_names .= $item['product_name'].",";
                $product_colors .= $item['product_color'].",";
                $product_sizes .= $item['product_size'].",";
                $product_prices .= $item['product_price'].",";
                $product_quantities .= $item['product_qty'].",";
            }
            $ordersData[$key]['product_codes'] = $product_codes;
            $ordersData[$key]['product_names'] = $product_names;
            $ordersData[$key]['product_colors'] = $product_colors;
            $ordersData[$key]['product_sizes'] = $product_sizes;
            $ordersData[$key]['product_prices'] = $product_prices;
            $ordersData[$key]['product_quantities'] = $product_quantities;
            // $ordersData = $ordersData->toArray();
            // echo "<pre>"; print_r($ordersData);die;
        }
        return $ordersData;
    }

    public function headings(): array{
        return ['Id', 'User Id', 'Name', 'Address','City','State', 'Country','Mobile',
        'Email','Order Status','Payment Method','Payment Gateway','Grand Total','Product Codes',
    'Product Names','Product Colors','Product Sizes','Product Prices','Product Quantities'];
    }
}
