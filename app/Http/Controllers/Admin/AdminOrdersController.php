<?php

namespace App\Http\Controllers\Admin;

use Dompdf\Dompdf;
use App\Models\Sms;
use App\Models\User;
use App\Models\Order;
use App\Models\OrdersLog;
use App\Models\AdminsRole;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use App\Models\OrdersProduct;
use App\Models\ReturnRequest;
use App\Models\ExchangeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AdminOrdersController extends Controller
{
    public function orders()
    {
        Session::put('page', 'orders');
        $orders = Order::with('orders_products')->orderBy('id','Desc')->get()->toArray();

         // Set Admin/Subadmin for Orders
        $orderModuleCount = AdminsRole::where(['admin_id'=>Auth::guard('admin')->user()->id,
        'module'=>'orders'])->count();
        if (Auth::guard('admin')->user()->type == "superadmin") {
            $orderModule['view_access'] = 1;
            $orderModule['edit_access'] = 1;
            $orderModule['full_access'] = 1;
        }else if($orderModuleCount == 0 ){
            $message = "The feature is restricted for you!";
            Session::flash('error_message',$message);
            return redirect('admin/dashboard');
        }else{
            $orderModule = AdminsRole::where(['admin_id'=>Auth::guard('admin')->user()->id,
        'module'=>'orders'])->first()->toArray();
        }

        return view('admin.orders.orders')->with(compact('orders','orderModule'));
    }

    public function orderDetails($id)
    {
        $orderDetails = Order::with('orders_products')->where('id',$id)->first()->toArray();
        $userDetails = User::where('id', $orderDetails['user_id'])->first()->toArray();
        $orderStatuses = OrderStatus::where('status',1)->get()->toArray();
        $orderLog = OrdersLog::where('order_id',$id)->orderBy('id','Desc')->get()->toArray();
        return view('admin.orders.order_details')->with(compact('orderDetails','userDetails','orderStatuses','orderLog'));
    }

    public function updateOrderStatus(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            //Update order status
            Order::where('id',$data['order_id'])->update(['order_status' => $data['order_status']]);
            Session::put('success_message', 'Order Status has been updated Successfully!');

            // Update Courier Name and Tracking Number
            if (!empty($data['courier_name']) && !empty($data['tracking_number'])) {
                Order::where('id',$data['order_id'])->update(['courier_name' => $data['courier_name'], 'tracking_number' => $data['tracking_number']]);
            }else{
                Order::where('id',$data['order_id'])->update(['courier_name' => "", 'tracking_number' => ""]);
            }

            //Get Delivery Details 
            $deliveryDetails = Order::select('mobile','email','name')->where('id',$data['order_id'])->first()->toArray();

            //Get Order details
            $orderDetails = Order::with('orders_products')->where('id',$data['order_id'])->first()->toArray();

            // Send Order Status SMS
            // $message = "Dear customer, your Order #".$data['order_id']." status has been updated to ".$data['order_status']." placed with highfrezh.com";
            //     $mobile = $deliveryDetails['mobile'];
            //     Sms::sendSms($message, $mobile);

             //Send Order Status Update Email
                $email = $deliveryDetails['email'];
                $messageData = [
                    'email' => $email,
                    'name' => $deliveryDetails['name'],
                    'order_id' => $data['order_id'],
                    'order_status' => $data['order_status'],
                    'courier_name' => $data['courier_name'],
                    'tracking_number' => $data['tracking_number'],
                    'orderDetails' => $orderDetails
                ];
                Mail::send('emails.order_status',$messageData, function($message) use($email) {
                    $message->to($email)->subject('Order Status Updated- highfrezh.com');
                });

                // Update Order Log
                $log = new OrdersLog;
                $log->order_id = $data['order_id'];
                $log->order_status = $data['order_status'];
                $log->save();

            return redirect()->back();
        }
    }

    public function viewOrderInvoice($id)
    {
        $orderDetails = Order::with('orders_products')->where('id',$id)->first()->toArray();
        $userDetails = User::where('id', $orderDetails['user_id'])->first()->toArray();
        return view('admin.orders.order_invoice')->with(compact('orderDetails','userDetails'));
    }
    
    public function printPDFInvoice($id)
    {
        $orderDetails = Order::with('orders_products')->where('id',$id)->first()->toArray();
        $userDetails = User::where('id', $orderDetails['user_id'])->first()->toArray();   
        
        $output = "";
        //instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($output);
        
        //(Optional) setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        //Render the HTML as PDF to Browser
        $dompdf->stream();

        return view('admin.orders.order_invoice')->with(compact('orderDetails','userDetails'));
    }

    public function returnRequests()
    {
        Session::put('page','return_requests');
        $return_requests = ReturnRequest::get()->toArray();
        return view('admin.orders.return_requests')->with(compact('return_requests'));
    }

    public function returnRequestUpdate(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // dd($data);die;
            //Get Return Details
            $returnDetails = ReturnRequest::where('id',$data['return_id'])->first()->toArray();

            //Update Return Status in return_request table
            ReturnRequest::where('id',$data['return_id'])->update(['return_status' => $data['return_status']]);

            //Update Return Status in Orders_products table
            OrdersProduct::where(['order_id' => $returnDetails['order_id'], 'product_code' => $returnDetails['product_code'], 
            'product_size' => $returnDetails['product_size']])->update(['item_status' => 'Return '.$data['return_status']]);

            //Get user Details
            $userDetails = User::select('name','email')->where('id',$returnDetails['user_id'])->first()->toArray();

            //Send Return Status Email
            // $email = $userDetails['email'];
            // $return_status = $data['return_status'];
            // $messageData = ['userDetails'=>$userDetails,'returnDetails'=>$returnDetails,'return_status' => $return_status];
            // Mail::send('emails.return_request',$messageData,function($message)use($email,$return_status){
            //     $message->to($email)->subject('Return Request '.$return_status);
            // });
            
            $message = 'Return Request has been '.$data['return_status'].' and email sent to user';
            Session::flash('success_message',$message);
            return redirect('admin/return-requests');
        }
    }
    
    public function exchangeRequests()
    {
        Session::put('page','exchange_requests');
        $exchange_requests = ExchangeRequest::get()->toArray();
        return view('admin.orders.exchange_requests')->with(compact('exchange_requests'));
    }
    
    public function exchangeRequestUpdate(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // dd($data);die;
            //Get Exchange Details
            $exchangeDetails = ExchangeRequest::where('id',$data['exchange_id'])->first()->toArray();

            //Update Exchange Status in exchange_request table
            ExchangeRequest::where('id',$data['exchange_id'])->update(['exchange_status' => $data['exchange_status']]);

            //Update Exchange Status in Orders_products table
            OrdersProduct::where(['order_id' => $exchangeDetails['order_id'], 'product_code' => $exchangeDetails['product_code'], 
            'product_size' => $exchangeDetails['product_size']])->update(['item_status' => 'Exchange '.$data['exchange_status']]);

            //Get user Details
            $userDetails = User::select('name','email')->where('id',$exchangeDetails['user_id'])->first()->toArray();

            //Send Exchange Status Email
            // $email = $userDetails['email'];
            // $exchange_status = $data['exchange_status'];
            // $messageData = ['userDetails'=>$userDetails,'exchangeDetails'=>$exchangeDetails,'exchange_status' => $exchange_status];
            // Mail::send('emails.exchange_request',$messageData,function($message)use($email,$exchange_status){
            //     $message->to($email)->subject('Exchange Request '.$exchange_status);
            // });
            
            $message = 'Exchange Request has been '.$data['exchange_status'].' and email sent to user';
            Session::flash('success_message',$message);
            return redirect('admin/exchange-requests');
        }
    }
}
