<?php

namespace App\Http\Controllers\front;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrdersLog;
use Illuminate\Http\Request;
use App\Models\OrdersProduct;
use App\Models\ReturnRequest;
use App\Models\ExchangeRequest;
use App\Models\ProductsAttribute;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrdersController extends Controller
{
    public function orders()
    {
        $orders = Order::with('orders_products')->where('user_id',Auth::user()->id)->orderBy('id','Desc')->get()->toArray();
        // dd($orders); die;
        return view('front.orders.orders')->with(compact('orders'));
    }

    public function orderDetails($id)
    {
        $orderDetails = Order::with('orders_products')->where('id',$id)->first()->toArray();
        // dd($orderDetails); die;
        return view('front.orders.order_details')->with(compact('orderDetails')); 
    }

    public function orderCancel($id, Request $request)
    {
        if($request->isMethod('post'))
        {
            $data = $request->all();
            if(isset($data['reason']) && empty($data['reason'])){
                return redirect()->back();
            }

            #Get user ID from Auth
            $user_id_auth = Auth::user()->id;

            //Get User ID from Order
            $user_id_order = Order::select('user_id')->where('id',$id)->first();

            if ( $user_id_auth == $user_id_order->user_id ) {
            
                #update order status to cancelled
                Order::where('id',$id)->update(['order_status'=>'Cancelled']);
        
                #Update Order Log
                $log = new OrdersLog;
                $log->order_id = $id;
                $log->order_status = "User Cancelled";
                $log->reason = $data['reason'];
                $log->updated_by = "User";
                $log->save();
                
                $message = "Order has been Cancelled";
                Session::flash('success_message',$message);
                return redirect()->back();
            }else{
                $message = "Your Order Cancellation Request is not valid";
                Session::flash('error_message',$message);
                return redirect('orders');
            }
        }
    }

    public function orderReturn($id, Request $request)
    {
        if($request->isMethod('post'))
        {
            $data = $request->all();
            // dd($data);die;

            if(isset($data['reason']) && empty($data['reason'])){
                return redirect()->back();
            }

            #Get user ID from Auth
            $user_id_auth = Auth::user()->id;

            //Get User ID from Order
            $user_id_order = Order::select('user_id')->where('id',$id)->first();

            if ( $user_id_auth == $user_id_order->user_id ) {
            
                if($data['return_exchange'] == "Return"){
                    # Product Details
                    $productArr = explode("-",$data['product_info']);
                    $product_code = $productArr[0];
                    $product_size = $productArr[1];
                
                    #Update Item Status
                    OrdersProduct::where(['order_id'=>$id,'product_code'=>$product_code,'product_size'=>$product_size])
                    ->update(['item_status'=>'Return Initiated']);

                    #Add Return Request
                    $return = new ReturnRequest;
                    $return->order_id = $id;
                    $return->user_id  = $user_id_auth;
                    $return->product_size = $product_size;
                    $return->product_code = $product_code;
                    $return->return_reason = $data['return_reason'];
                    $return->return_status = "Pending";
                    $return->comment = $data['comment'];
                    $return->save();

                    $message = "Return Request has been placed for the Ordered Product!";
                    Session::flash('success_message',$message);
                    return redirect()->back();

                }else if($data['return_exchange'] == "Exchange"){
                    # Product Details
                    $productArr = explode("-",$data['product_info']);
                    $product_code = $productArr[0];
                    $product_size = $productArr[1];
                
                    #Update Item Status
                    OrdersProduct::where(['order_id'=>$id,'product_code'=>$product_code,'product_size'=>$product_size])
                    ->update(['item_status'=>'Exchange Initiated']);

                    #Add Exchange Request
                    $exchange = new ExchangeRequest;
                    $exchange->order_id = $id;
                    $exchange->user_id  = $user_id_auth;
                    $exchange->product_size = $product_size;
                    $exchange->product_code = $product_code;
                    $exchange->required_size = $data['required_size'];
                    $exchange->exchange_reason = $data['return_reason'];
                    $exchange->exchange_status = "Pending";
                    $exchange->comment = $data['comment'];
                    $exchange->save();

                    $message = "Exchange Request has been placed for the Ordered Product!";
                    Session::flash('success_message',$message);
                    return redirect()->back();

                }else{
                    $message = "Your Order Return/Exchange Request is not valid";
                    Session::flash('error_message',$message);
                    return redirect('order');
                }

                
            }else{
                $message = "Your Order Return/Exchange Request is not valid";
                Session::flash('error_message',$message);
                return redirect('orders');
            }
        }
    }

    public function getProductSizes(Request $request)
    {
        $data = $request->all();

        //Get Product Details
        $productArr = explode("-",$data['product_info']);
        $product_code = $productArr[0];
        $product_size = $productArr[1];
        
        $productId = Product::select('id')->where('product_code',$product_code)->first();
        $product_id = $productId->id;
        $productSizes = ProductsAttribute::select('size')->where('product_id',$product_id)
        ->where('size','!=',$product_size)->where('stock','>',0)->get()->toArray();
        
        $appendSizes = '<option value"">Select Required Size</option>';
        foreach ($productSizes as $size) {
            $appendSizes .= '<option value="'.$size['size'].'">'.$size['size'].'</option>';
        }
        return $appendSizes;
    }
}
