<?php

namespace App\Http\Controllers\Front;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductsAttribute;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
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
                return view('front.products.ajax_products_listing')->with(compact('categoryDetails','categoryProducts','url'));
            }else{
                abort(404);
            }
        }else {
            $url = Route::getFacadeRoot()->current()->uri();
            $categoryCount = Category::where(['url' => $url, 'status'=>1])->count();
            if ($categoryCount > 0) {
                $categoryDetails = Category::catDetails($url);
                // echo "<pre>"; print_r($categoryDetails['catIds']);
                $categoryProducts = Product::with('brand')->whereIn('category_id',$categoryDetails['catIds'])->
                where('status',1); //WhereIn is use the pass second argurment of as Array
                $categoryProducts = $categoryProducts->paginate(1);
                // echo "<pre>"; print_r($categoryProducts);die;

                //product filters
                $productFilters = Product::productFilters();
                $fabricArray = $productFilters['fabricArray'];
                $sleeveArray = $productFilters['sleeveArray'];
                $patternArray = $productFilters['patternArray'];
                $fitArray = $productFilters['fitArray'];
                $occasionArray = $productFilters['occasionArray'];

                $page_name = "listing";
                return view('front.products.listing')->with(compact('categoryDetails','categoryProducts','url','fabricArray',
                'sleeveArray','patternArray','fitArray','occasionArray','page_name'));
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
        return view('front.products.detail')->with(compact('productDetails','total_stock','relatedProducts'));
    }

    public function getProductPrice(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $getProductPrice = ProductsAttribute::where(['product_id' => $data['product_id'],'size'
            => $data['size']])->first();
            return $getProductPrice->price;
        }
    }

    public function addToCart(Request $request)
    {
        if ($request->isMethod('post')) {
            $data =  $request->all();
            
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

            // Save Product in Cart
                $cart = new Cart;
                $cart -> session_id = $session_id;
                $cart -> product_id = $data['product_id'];
                $cart -> size = $data['size'];
                $cart -> quantity = $data['quantity'];
                $cart->save();
                $message = "Product has been added in Cart";
                Session::flash('success_message',$message);
                return redirect()->back();
        }
    }

    public function cart()
    {
        $userCartItems = Cart::userCartItems();
        // echo "<pre>"; print_r($userCartItems); die;
        return view('front.products.cart')->with(compact('userCartItems'));
    }
}
