<?php

namespace App\Http\Controllers\Front;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrontProductsController extends Controller
{
    public function listing($url, Request $request)
    {
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
            $categoryCount = Category::where(['url' => $url, 'status'=>1])->count();
            if ($categoryCount > 0) {
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
                return view('front.products.listing')->with(compact('categoryDetails','categoryProducts','url','fabricArray',
                'sleeveArray','patternArray','fitArray','occasionArray','page_name'));
            }else{
                abort(404);
            }
        }
    }
}
