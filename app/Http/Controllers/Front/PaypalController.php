<?php

namespace App\Http\Controllers\Front;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PaypalController extends Controller
{
    public function paypal()
    {
        if(Session::has('order_id')){
            $orderDetails = Order::where('id',Session::get('order_id'))->first()->toArray();
            $nameArr = explode(' ',$orderDetails['name']);
            return view('front.paypal.paypal')->with(compact('orderDetails','nameArr'));
        }else{
            return redirect('/cart');
        }
    }

    public function success()
    {
        if(Session::has('order_id')){
            // Empty the User Cart
            Cart::where('user_id',Auth::user()->id)->delete();
            return view('front.paypal.success');
        }else{
            return redirect('/cart');
        }
    }

    public function fail()
    {
        return view('front.paypal.fail');
    }

    public function ipn(Request $request)
    {
        $data = $request->all();
        // dd($data); die;
        if ($data['payment_status'] == "Completed") {
            //Process the Order
            $order_id = Session::get('order_id');

            //Update Order status to paid
            Order::where('id',$order_id)->update(['order_status' => 'Paid']);

            //Send Order Sms
                // $message = "Dear Customer, your order ".$order_id."has been successfully placed with highfrezh.com. We will initimate ypou once your order is shipped.";
                // $mobile = Auth::user()->mobile;
                // Sms::sendSms($message,$mobile);

                $orderDetails = Order::with('orders_products')->where('id',$order_id)->first()->toArray();
                $userDetails = User::where('id',$orderDetails['user_id'])->first()->toArray();

                
            // Reduce stock script starts
            foreach($orderDetails['orders_products'] as $oder ){
                //current product stock
                $getProductStock = ProductsAttribute::where(['product_id'=>$order['product_id'],
                'size'=>$order['size']])->first()->toArray();
                //calculate new stock
                $newStock = $getProductStock['stock'] - $order['quantity'];
                //Updare New stock
                ProductsAttribute::where(['product_id' => $order['product_id'], 
                'size' => $order['size']])->update(['stock' => $newStock]);
            }
            // Reduce stock script end

                //Send Order Email
                // $email = Auth::user()->email;
                // $messageData = [
                //     'email' => $email,
                //     'name' => Auth::user()->name,
                //     'order_id' => $order_id,
                //     'orderDetails' => $orderDetails
                //     // 'userDetails' => $userDetails
                // ];
                // Mail::send('emails.order',$messageData, function($message) use($email) {
                //     $message->to($email)->subject('Order Placed - highfrezh.com');
                // });
        }
    }
}
