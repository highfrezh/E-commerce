<?php

namespace App\Http\Controllers\Front;

use App\Models\Sms;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Rating;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Models\OrdersProduct;
use App\Models\ShippingCharge;
use App\Models\DeliveryAddress;
use App\Models\ProductsAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class FrontProductsController extends Controller
{
    public function listing(Request $request)
    {
        Paginator::useBootstrap();
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $url = $data['url'];
            $categoryCount = Category::where(['url' => $url, 'status'=>1])->count();
            if ($categoryCount > 0) {
                $categoryDetails = Category::catDetails($url);
                // echo "<pre>"; print_r($categoryDetails['catIds']);
                $categoryProducts = Product::with('brand')->whereIn('category_id',$categoryDetails['catIds'])->
                where('status',1); //WhereIn is use the pass second argurment of Array

                // if fabric option is selected
                if (isset($data['fabric']) && !empty($data['fabric'])) {
                    $categoryProducts->whereIn('products.fabric',$data['fabric']); // "products.fabric" means products table fabric column;
                }

                // if sleeve option is selected
                if (isset($data['sleeve']) && !empty($data['sleeve'])) {
                    $categoryProducts->whereIn('products.sleeve',$data['sleeve']); // "products.sleeve" means products table fabric column;
                }

                // if fit option is selected
                if (isset($data['fit']) && !empty($data['fit'])) {
                    $categoryProducts->whereIn('products.fit',$data['fit']); // "products.fit" means products table fabric column;
                }

                // if pattern option is selected
                if (isset($data['pattern']) && !empty($data['pattern'])) {
                    $categoryProducts->whereIn('products.pattern',$data['pattern']); // "products.pattern" means products table fabric column;
                }

                // if occasion option is selected
                if (isset($data['occasion']) && !empty($data['occasion'])) {
                    $categoryProducts->whereIn('products.occasion',$data['occasion']); // "products.occasion" means products table fabric column;
                }
    
                //if sort option selected by User
                if (isset($data['sort']) && !empty($data['sort'])) {
                    if ($data['sort'] == "product_latest") {
                        $categoryProducts->orderBy('id','Desc');
                    }elseif ($data['sort'] == "product_name_a_z") {
                        $categoryProducts->orderBy('product_name','Asc');
                    }elseif ($data['sort'] == "product_name_z_a") {
                        $categoryProducts->orderBy('product_name','Desc');
                    }elseif ($data['sort'] == "price_lowest") {
                        $categoryProducts->orderBy('product_price','Asc');
                    }elseif ($data['sort'] == "price_highest") {
                        $categoryProducts->orderBy('product_price','Desc');
                    }else{
                        $categoryProducts->orderBy('id','Desc');
                    }
                }
    
                $categoryProducts = $categoryProducts->paginate(6);
                // echo "<pre>"; print_r($categoryProducts);die;
                $meta_title = $categoryDetails['catDetails']['meta_title'];
                $meta_description = $categoryDetails['catDetails']['meta_description'];
                $meta_keywords = $categoryDetails['catDetails']['meta_keywords'];
                return view('front.products.ajax_products_listing')->with(compact('categoryDetails','categoryProducts','url',
                'meta_title','meta_description','meta_keywords'));
            }else{
                abort(404);
            }
        }else {
            $url = Route::getFacadeRoot()->current()->uri();
            $categoryCount = Category::where(['url' => $url, 'status'=>1])->count();
            //Search Functionality start
            if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
                $search_product = $_REQUEST['search'];
                $categoryDetails['breadcrumbs'] = $search_product;
                $categoryDetails['catDetails']['category_name'] = $search_product;
                $categoryDetails['catDetails']['description'] = "Search Results for ".$search_product;
                $categoryProducts = Product::with('brand')->where(function($query)use($search_product){
                    $query->where('product_name','like','%'.$search_product.'%')
                    ->orWhere('product_code','like','%'.$search_product.'%')
                    ->orWhere('product_color','like','%'.$search_product.'%')
                    ->orWhere('description','like','%'.$search_product.'%');
                })->where('status',1);
                $categoryProducts = $categoryProducts->get();

                $page_name = "Search Results";
                return view('front.products.listing')->with(compact('categoryDetails','categoryProducts','page_name'));
            }else if ($categoryCount > 0) {
                $categoryDetails = Category::catDetails($url);
                // echo "<pre>"; print_r($categoryDetails['catIds']);
                $categoryProducts = Product::with('brand')->whereIn('category_id',$categoryDetails['catIds'])->
                where('status',1); //WhereIn is use the pass second argurment of as Array
                $categoryProducts = $categoryProducts->paginate(6);
                // echo "<pre>"; print_r($categoryProducts);die;

                //product filters
                $productFilters = Product::productFilters();
                $fabricArray = $productFilters['fabricArray'];
                $sleeveArray = $productFilters['sleeveArray'];
                $patternArray = $productFilters['patternArray'];
                $fitArray = $productFilters['fitArray'];
                $occasionArray = $productFilters['occasionArray'];

                $page_name = "listing";
                $meta_title = $categoryDetails['catDetails']['meta_title'];
                $meta_description = $categoryDetails['catDetails']['meta_description'];
                $meta_keywords = $categoryDetails['catDetails']['meta_keywords'];
                return view('front.products.listing')->with(compact('categoryDetails','categoryProducts','url','fabricArray',
                'sleeveArray','patternArray','fitArray','occasionArray','page_name','meta_title','meta_description','meta_keywords'));
            }else{
                abort(404);
            }
        }
    }

    public function detail($id)
    {
        $productDetails = Product::with(['category','brand','attributes'=>function($query){$query->where('status',1); },'images'])->find($id)->toArray();
        $total_stock = ProductsAttribute::where('product_id',$id)->sum('stock');
        $relatedProducts = Product::where('category_id', $productDetails['category']['id'])->where('id','!=', $id)
        ->inRandomOrder()->limit(3)->get()->toArray();
        // dd($relatedProducts); die;
        $groupProducts = array();
        if(!empty($productDetails['group_code'])){
            $groupProducts = Product::select('id','main_image')->where('id','!=',$id)->where(['group_code' => $productDetails['group_code'],'status'=>1])->get()->toArray();
        }

        $getCurrencies = Currency::select('currency_code','exchange_rate')->where('status',1)->get()->toArray();

        //Get All rating of product
        $ratings = Rating::with('user')->where(['status' => 1, 'product_id'=>$id])->orderBy('id','Desc')->get()->toArray();

        //Get Average Rating of the Product
        $ratingsSum = Rating::with('user')->where(['status' => 1, 'product_id'=>$id])->sum('rating');
        $ratingsCount = Rating::with('user')->where(['status' => 1, 'product_id'=>$id])->count();

        if ($ratingsSum || $ratingsCount != 0) {
            $avgRating = round($ratingsSum/$ratingsCount,2);
            $avgStarRating = round($ratingsSum/$ratingsCount);
        }else{
            $avgRating = 0;
            $avgStarRating = 0;
        }
        
        $meta_title = $productDetails['product_name'];
        $meta_description = $productDetails['description'];
        $meta_keywords = $productDetails['product_name'];
        return view('front.products.detail')->with(compact('productDetails','total_stock','relatedProducts','groupProducts','meta_title','meta_description',
        'meta_keywords','getCurrencies','ratings','avgRating','avgStarRating'));
    }

    public function getProductPrice(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $getCurrencies = Currency::select('currency_code','exchange_rate')->where('status',1)->get()->toArray();
            $getDiscountedAttrPrice = Product::getDiscountedAttrPrice($data['product_id'],$data['size']);
            $getDiscountedAttrPrice['currency'] = "<span style='font-weight:normal; font-size:14px'>";
            foreach($getCurrencies as $currency){
                $getDiscountedAttrPrice['currency'] .= "<br>";
                $getDiscountedAttrPrice['currency'] .= $currency['currency_code'];
                $getDiscountedAttrPrice['currency'] .= " ";
                $getDiscountedAttrPrice['currency'] .= round($getDiscountedAttrPrice['final_price'] * $currency['exchange_rate'],2);
            }
            $getDiscountedAttrPrice['currency'] .= "</span>";
            return $getDiscountedAttrPrice;
        }
    }

    public function addToCart(Request $request)
    {
        if ($request->isMethod('post')) {
            $data =  $request->all();

            if($data['quantity']<=0 || $data['quantity']=""){
                $data['quantity'] = 1;
            }
            
            //check If the product selected is in the Stock
            $getProductStock = ProductsAttribute::where(['product_id' => $data['product_id'],'size'
            =>$data['size']])->first()->toArray();
            if ($getProductStock['stock'] < $data['quantity']) {
                $message = "Required Quantity is not available!";
                Session::flash('error_message',$message);
                return redirect()->back();
            }

            // Generate Session Id if not exists
            $session_id = Session::get('session_id');
            
            if (empty($session_id)) {
                $session_id = Session::getId();
                Session::put('session_id',$session_id);
            }

            // check if product already exist in User cart
            if (Auth::check()) {
                //User is Logged in
                $countProducts = Cart::where(['product_id'=> $data['product_id'], 'size' =>$data['size'], 
                'user_id'=>Auth::user()->id])->count();
            }else {
                //User is not logged In
                $countProducts = Cart::where(['product_id'=> $data['product_id'], 'size' =>$data['size'], 
                'session_id'=>Session::get('session_id')])->count();
            }
            $countProducts = Cart::where(['product_id' => $data['product_id'],'size'
            =>$data['size']])->count();
            if ($countProducts > 0) {
                $message = "Product already exists in Cart!";
                Session::flash('error_message',$message);
                return redirect()->back();
            }

            if (Auth::check()) {
                $user_id = Auth::user()->id;
                Session::forget('session_id');
            }else{
                $user_id = 0;
            }

            // Save Product in Cart
                $cart = new Cart;
                $cart -> session_id = $session_id;
                $cart -> user_id = $user_id;
                $cart -> product_id = $data['product_id'];
                $cart -> size = $data['size'];
                $cart -> quantity = $data['quantity'];
                $cart->save();
                $message = "Product has been added in Cart";
                Session::flash('success_message',$message);
                return redirect('cart');
        }
    }

    public function cart()
    {
        $userCartItems = Cart::userCartItems();
        // echo "<pre>"; print_r($userCartItems); die;
        $meta_title = "Shopping Cart - E-commerce Website";
        $meta_description = "View Shopping Cart of E-commerce Website";
        $meta_keywords = "shopping cart, e-commerce website";
        return view('front.products.cart')->with(compact('userCartItems','meta_title','meta_description','meta_keywords'));
    }

    public function updateCartItemQty(Request $request)
    { 
        if ($request->ajax()) {
            $data = $request->all();
            
            //Get Cart Details 
            $cartDetails = Cart::find($data['cartid']);

            //Get Available Product Stock
            $availableStock = ProductsAttribute::select('stock')->where(['product_id' => 
            $cartDetails['product_id'], 'size'=>$cartDetails['size']])->first()->toArray();

            //check if demanded stock is available
            if ($data['qty']>$availableStock['stock']) {
                $userCartItems = Cart::userCartItems();
                return response()->json([
                    'status' => false,
                    'view' =>(String)View::make('front.products.cart_items')->with(compact('
                    userCartItems'))
                ]);
            }
            Cart::where('id', $data['cartid'])->update(['quantity'=>$data['qty']]);
            $userCartItems = Cart::userCartItems();
            $totalCartItems = totalCartItems();
            return response()->json([
                'status' => true,
                'totalCartItems' => $totalCartItems,
                'view' => (String)View::make('front.products.cart_items')->
            with(compact('userCartItems'))]);
        }
    }

    public function deleteCartItem(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            Cart::where('id',$data['cartid'])->delete();
            $userCartItems = Cart::userCartItems();
            $totalCartItems = totalCartItems();
            return response()->json([
                'totalCartItems' => $totalCartItems,
                'view' => (String)View::make('front.products.cart_items')
                ->with(compact('userCartItems'))
            ]);
        }
    }

    public function applyCoupon(Request $request)
    {
        if($request->ajax()){
            $data = $request->all();  
            $userCartItems = Cart::userCartItems();
            $couponCount = Coupon::where('coupon_code',$data['code'])->count();
            $totalCartItems = totalCartItems();
            if($couponCount == 0){
                Session::forget('couponCode');
                Session::forget('couponAmount');
                return response()->json([
                    'status'=> false,
                    'message'=>'This Coupon is not valid!',
                    'totalCartItems'=>$totalCartItems,
                    'view'=>(String)View::make('front.products.cart_items')->with(compact('userCartItems'))
                ]);
            }else{
            //Check for other coupons

            //Get Coupon Details
            $couponDetails  = Coupon::where('coupon_code',$data['code'])->first();

            //Check if coupon is inactive
            if($couponDetails->status == 0){
                $message = 'This coupon is not active!';
            }

            //Check if coupon is expire
            $expiry_date = $couponDetails->expiry_date;
            $current_date = date('Y-m-d');
            if($expiry_date < $current_date){
                $message = 'This coupon is expired!';
            }

            //Check if coupon is single or multiple times
            if ($couponDetails->coupon_type == "Single Times") {
                //Check in Orders table if coupon already availed by the user
                $couponCount = Order::where(['coupon_code' => $data['code'], 'user_id'=>Auth::user()->id])->count();
                if ($couponCount >= 1) {
                    $message = "This coupon code s already availed by you!";
                }
            }

            // Check if coupon is from selected categories
            // Get all selected categories from coupon
            $catArr = explode(",",$couponDetails->categories);

            //Get Cart Items
            $userCartItems = Cart::userCartItems();

            //Check if coupon belong to login user
            //Get all selected users of coupon
            if (!empty($couponDetails->users)) {
                $usersArr = explode(",", $couponDetails->users);
                //Get User ID's of all selected users
                foreach ($usersArr as $key => $user) {
                    $getUserID = User::select('id')->where('email',$user)->first()->toArray();
                    $userID[] = $getUserID['id'];
                }
            }
            
            // Get the product total amount
            $total_amount = 0;

            //Check if any item belong to coupon category and user
            foreach ($userCartItems as $key => $item) {

                if(!in_array($item['product']['category_id'],$catArr)){
                    $message = 'This coupon code is not for one of the selected products!';
                }
                if(!empty($couponDetails->users)){
                    if (!in_array($item['user_id'], $userID)) {
                        $message = 'This coupon is not for you!';
                    }
                }

                $attrPrice = Product::getDiscountedAttrPrice($item['product_id'],$item['size']);
                $total_amount = $total_amount + ($attrPrice['final_price'] * $item['quantity']);
            }
            
            if(isset($message)){
                return response()->json([
                    'status'=> false,
                    'message'=>$message,                    
                    'totalCartItems'=>$totalCartItems,
                    'view'=>(String)View::make('front.products.cart_items')->with(compact('userCartItems'))
                ]);
            }else {
                //Check if amount type is Fixed or Percentage
                if($couponDetails->amount_type == "Fixed"){
                    $couponAmount = $couponDetails->amount;
                }else {
                    $couponAmount = $total_amount * ($couponDetails->amount/100);
                }
                $grand_total = $total_amount - $couponAmount;

                // Add Coupon Code & Amopunt in Session Variable
                Session::put('couponAmount',$couponAmount);
                Session::put('couponCode',$data['code']);

                $message = "Coupon code successfully applied. You are availing discount!";

                return response()->json([
                    'status'=> true,
                    'message'=>$message,                    
                    'totalCartItems'=>$totalCartItems,
                    'couponAmount'=>$couponAmount,
                    'grand_total'=>$grand_total,
                    'view'=>(String)View::make('front.products.cart_items')->with(compact('userCartItems'))
                ]);
            }
            
            }
        }
    }

    public function checkout(Request $request)
    {
        $userCartItems = Cart::userCartItems();

        if (count($userCartItems) == 0) {
            $message = "Shipping Cart is empty! Please add product to cart.";
            Session::put('error_message',$message);
            return redirect('cart');
        }

        $total_price = 0;
        $total_weight = 0;
        foreach($userCartItems as $item){
            $product_weight = $item['product']['product_weight'];
            $total_weight = $total_weight + ($product_weight * $item['quantity']);
            $attrPrice = Product::getDiscountedAttrPrice($item['product_id'],$item['size']);
            $total_price = $total_price + ($attrPrice['final_price'] * $item['quantity']);
        }

        $deliveryAddresses = DeliveryAddress::deliveryAddresses();

        foreach ($deliveryAddresses as $key => $value) {
            $shippingCharges = ShippingCharge::getShippingCharges($total_weight, $value['country']);
            $deliveryAddresses[$key]['shipping_charges'] = $shippingCharges;
        }

        if($request->isMethod('post')){
            $data = $request->all();
            //  dd($data); die;

            //website Security Check
            //Fetch User cart Items
            foreach($userCartItems as $key => $cart) {
                // prevent Disable product to order
                $product_status = Product::getProductStatus($cart['product_id']);             
                    if($product_status==0) {
                        // if neccessary to delete the product automatically
                    // Product::deleteCartProduct($cart['product_id']);
                    $message = $cart['product']['product_name']." is not available please remove from Cart!";
                    Session::flash('error_message',$message);
                    return redirect('/cart');
                }

                //prevent Out of Stock Products to Order
                $product_stock = Product::getProductStock($cart['product_id'],$cart['size']);
                if($product_stock==0) {
                        // if neccessary to delete the product automatically
                    // Product::deleteCartProduct($cart['product_id']);
                    $message = $cart['product']['product_name']." is not available in the Stock please remove from Cart!";
                    Session::flash('error_message',$message);
                    return redirect('/cart');
                }

                //Prevent Disable or Deleted attribute to Order
                $getAttributeCount = Product::getAttributeCount($cart['product_id'],$cart['size']);
                if($getAttributeCount==0) {
                        // if neccessary to delete the product automatically
                    // Product::deleteCartProduct($cart['product_id']);
                    $message = $cart['product']['product_name']." is not available in the Stock please remove from Cart!";
                    Session::flash('error_message',$message);
                    return redirect('/cart');
                }

                //Prevent Disable or deleted category
                $category_status = Product::getCategoryStatus($cart['product']['category_id']);
                if($category_status==0) {
                        // if neccessary to delete the product automatically
                    // Product::deleteCartProduct($cart['product_id']);
                    $message = $cart['product']['product_name']." Category is not available please remove from Cart!";
                    Session::flash('error_message',$message);
                    return redirect('/cart');
                }
            }

            if(empty($data['address_id'])){
                $message = "please select Delivery Address!";
                Session::flash('error_message',$message);
                return redirect()->back();
            }
            if(empty($data['payment_gateway'])){
                $message = "please select Payment Method!";
                Session::flash('error_message',$message);
                return redirect()->back();
            }

            if($data['payment_gateway'] == "COD"){
                $payment_method = "COD";
                $order_status = "New";
            }else{
                $payment_method = "Prepaid";
                $order_status = "Pending";
            }
            die;
            //Get Delivery address from address id
            $deliveryAddress = DeliveryAddress::where('id',$data['address_id'])->first()->toArray();
            // dd($deliveryAddress); die;

            // Get the Shipping Charges
            $shipping_charges = ShippingCharge::getShippingCharges($total_weight, $deliveryAddress['country']); 

            // Calculate Grand Total
            $grand_total = $total_price + $shipping_charges - Session::get('couponAmount');

            // Insert Grand Total in Session Variable
            Session::put('grand_total',$grand_total);

            DB::beginTransaction();
            //Insert Order details;
            $order = new Order;
            $order->user_id = Auth::user()->id;
            $order->name = $deliveryAddress['name'];
            $order->address = $deliveryAddress['address'];
            $order->city = $deliveryAddress['city'];
            $order->state = $deliveryAddress['state'];
            $order->country = $deliveryAddress['country'];
            $order->pincode = $deliveryAddress['pincode'];
            $order->mobile = $deliveryAddress['mobile'];
            $order->email = Auth::user()->email;
            $order->shipping_charges = $shipping_charges;
            $order->coupon_code = Session::get('couponCode');
            $order->coupon_amount = Session::get('couponAmount');
            $order->order_status = $order_status;
            $order->payment_method = $payment_method;
            $order->payment_gateway = $data['payment_gateway'];
            $order->grand_total = Session::get('grand_total');
            $order->save();

            //Getting the id of recent Added order
            $order_id = DB::getPdo()->lastInsertId(); 

            // Get User Cart Items
            $cartItems = Cart::where('user_id',Auth::user()->id)->get()->toArray();
            foreach ($cartItems as $key => $item) {
                $cartItem = new OrdersProduct;
                $cartItem->order_id = $order_id;
                $cartItem->user_id = Auth::user()->id;

                $getProductDetails = Product::select('product_code','product_name','product_color')->where('id',$item['product_id'])->first()->toArray();
                $cartItem->product_id = $item['product_id'];
                $cartItem->product_code = $getProductDetails['product_code'];
                $cartItem->product_name = $getProductDetails['product_name'];
                $cartItem->product_color = $getProductDetails['product_color'];
                $cartItem->product_size = $item['size'];
                $getDiscountedAttrPrice = Product::getDiscountedAttrPrice($item['product_id'],$item['size']);
                $cartItem->product_price = $getDiscountedAttrPrice['final_price'];
                $cartItem->product_qty = $item['quantity'];
                $cartItem->save();

                if($data['payment_gateway'] == "COD"){
                    // Reduce Stock Script Starts
                    $getProductStock = ProductsAttribute::where(['product_id' => $item['product_id'], 'size' => $item['size']])->first()->toArray();
                    $newStock = $getProductStock['stock'] - $item['quantity'];
                    ProductsAttribute::where(['product_id' => $item['product_id'], 'size' => $item['size']])->update(['stock' => $newStock]);
                    // Reduce Stock Script End.
                }
            }
            
            //Insert Order id in session variable
            Session::put('order_id',$order_id);

            DB::commit();

            if($data['payment_gateway'] == "COD"){

                //Send Order Sms
                // $message = "Dear Customer, your order ".$order_id."has been successfully placed with highfrezh.com. We will initimate ypou once your order is shipped.";
                // $mobile = Auth::user()->mobile;
                // Sms::sendSms($message,$mobile);

                // $orderDetails = Order::with('orders_products')->where('id',$order_id)->first()->toArray();
                // $userDetails = User::where('id',$orderDetails['user_id'])->first()->toArray();

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

                return redirect('thanks');
            }else if($data['payment_gateway'] = "Paypal"){
                //Paypal - Redirect user to Paypal page
                return redirect('paypal');
            }else{
                echo "Other Prepaid Method Coming Soon"; die;
            }

            echo "Order placed.."; die;

        }
        $meta_title = "Checkout Page - E-commerce Website";
        return view('front.products.checkout')->with(compact('userCartItems','deliveryAddresses','total_price','meta_title'));
    }

    public function thanks(){
        if(Session::has('order_id')){
            //Empty the User Cart
            Cart::where('user_id',Auth::user()->id)->delete();
            return view('front.products.thanks');
        }else{
            return redirect('/cart');
        }
    }

    public function addEditDeliveryAddress(Request $request, $id=null)
    {
        if ($id == "") {
            //Add Delivery Address
            $title = "Add Delivery Address";
            $address = new DeliveryAddress;
            $message = "Delivery Address added successfully!";
        }else{
            //Edit Delivery Address
            $title = "Edit Delivery Address";
            $address = DeliveryAddress::find($id);
            $message = "Delivery Address updated successfully!";
        }
        if ($request->isMethod('post')) {
            $data = $request->all();
            
            $rules = [
                'name' => 'required|regex:/^[\pL\s\-]+$/u',
                'address' => 'required',
                'city' => 'required|regex:/^[\pL\s\-]+$/u',
                'state' => 'required|regex:/^[\pL\s\-]+$/u',
                'country' => 'required',
                'pincode' => 'required|numeric|digits:6',
                'mobile' => 'required|numeric|digits:11',
            ];
            $customMessage = [
                'name.required' => 'Name is required',
                'name.regex' => 'Valid Name is required',
                'address.required' => 'Address is required',
                'city.required' => 'City is required',
                'city.regex' => 'Valid City is required',
                'state.required' => 'State is required',
                'state.regex' => 'Valid State is required',
                'country.required' => 'Country is required',
                'pincode.required' => 'Pincode is required',
                'pincode.numeric' => 'Valid Pincode is required',
                'pincode.digits' => 'Pincode must be of 6 digits',
                'mobile.required' => 'Mobile is required',
                'mobile.numeric' => 'Valid Mobile is required',
                'mobile.digits' => 'Mobile must be of 11 digits',
            ];
            $this->validate($request,$rules,$customMessage);

            $address->user_id = Auth::user()->id;
            $address->name = $data['name'];
            $address->address = $data['address'];
            $address->city = $data['city'];
            $address->state = $data['state'];
            $address->country = $data['country'];
            $address->pincode = $data['pincode'];
            $address->mobile = $data['mobile'];
            $address->save();
            Session::put('success_message',$message);
            return redirect('/checkout');
        }
        $countries = Country::where('status',1)->get()->toArray();
        return view('front.products.add_edit_delivery_address')->with(compact('countries','title','address'));
    }

    public function deleteDeliveryAddress($id)
    {
        DeliveryAddress::where('id',$id)->delete();
        $message = "Delivery Address deleted successfully!";
        Session::put('success_message',$message);
        return redirect()->back();
    }

    public function updateWishlist(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $countWishlist = Wishlist::countWishlist($data['product_id']);
            if ($countWishlist == 0) {
                # Add product in wishlist
                $wishlist = new Wishlist;
                $wishlist->user_id = Auth::user()->id;
                $wishlist->product_id = $data['product_id'];
                $wishlist->save();
                return response()->json(['status'=>true,'action'=>'add']);
            }else{
                # Remove Product from wishlist
                Wishlist::where(['user_id'=>Auth::user()->id, 'product_id'=>$data['product_id']])->delete();
                return response()->json(['status'=>true,'action'=>'remove']);
            }
        }
    }

    public function wishlist()
    {
        $userWishlistItems = Wishlist::userWishlistItems();
        $meta_title = "Wishlist - E-commerce Website";
        $meta_description = "View Wishlist of E-commerce Website";
        $meta_keywords = "wishlist, e-commerce website";
        return view('front.products.wishlist')->with(compact('userWishlistItems','meta_title','meta_description','meta_keywords'));
    }

    public function deleteWishlistItem(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            Wishlist::where('id',$data['wishlistid'])->delete();
            $userWishlistItems = Wishlist::userWishlistItems();
            $totalWishlistItems = totalWishlistItems();
            return response()->json(['totalWishlistItems'=>$totalWishlistItems,'view'=>(String)View::make('front.products.wishlist_items')->with(compact('userWishlistItems'))]);
        }
    }
}
