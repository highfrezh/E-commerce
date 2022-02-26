<?php

namespace App\Http\Controllers\Front;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {

        // Getting featured Items
        $featuredItemsCount = Product::where('is_featured', 'Yes')->where('status',1)->count();
        $featuredItems = Product::where('is_featured', 'Yes')->where('status',1)->get()->toArray();
        $featuredItemsChunk = array_chunk($featuredItems, 4); // array_chunk is use to merge multiple ARRAY TOGETHER
        
        // Getting New Products
        $newProducts = Product::orderBy('id','Desc')->where('status',1)->limit(6)->get()->toArray();
        // dd($newProducts); die;
        $page_name = "index";
        return view('front.index')->with(compact('page_name','featuredItemsChunk','featuredItemsCount','newProducts'));
    }
}
