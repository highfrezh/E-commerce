<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\ProductsImage;
use App\Models\ProductsAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'section_id',
        'product_name',
        'product_code',
        'product_color',
        'product_price',
        'product_discount',
        'product_weight',
        'main_image',
        'description',
        'wash_care',
        'fabric',
        'pattern',
        'sleeve',
        'fit',
        'occasion',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_featured',
        'status',
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id')->select('id', 'category_name','url');
    }

    public function section(){
        return $this->belongsTo(Section::class, 'section_id')->select('id', 'name');
    }
    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id')->select('id', 'name');
    }

    public function attributes(){
        return $this->hasMany(ProductsAttribute::class);
    }

    public function images(){
        return $this->hasMany(ProductsImage::class);
    }

    public static function productFilters()
    {
        //product filters
        $productFilters['fabricArray'] = array('Cotton','Polyester','Wool');
        $productFilters['sleeveArray'] = array('Full sleeve','Half sleeve','Short sleeve', 'Sleeveless');
        $productFilters['patternArray'] = array('checked','Plain','Printed', 'Slef','Solid');
        $productFilters['fitArray'] = array('Regular','Slim');
        $productFilters['occasionArray'] = array('Casual','Formal');
        return $productFilters;
    }

    public static function getDiscountedPrice($product_id)
    {
        $proDetails = Product::select('product_price','product_discount','category_id')
        ->where('id',$product_id)->first()->toArray();
        $catDetails = Category::select('category_discount')->where('id',$proDetails['category_id'])
        ->first()->toArray();
        if ($proDetails['product_discount'] >0) {
            // If product discount is added 
            $discount_price = $proDetails['product_price'] - ($proDetails['product_price'] * $proDetails['product_discount'] / 100);
        }elseif ($catDetails['category_discount']>0) {
            // if product discount is not added and the category discount is added from the admin panel
            $discount_price = $proDetails['product_price'] - ($proDetails['product_price'] * $catDetails['category_discount']/100);
        }else{
            $discount_price = 0;
        }
        return $discount_price;
    }

    public static function getDiscountedAttrPrice($product_id, $size)
    {
        // product price from the attribute price
        $proAttrPrice = ProductsAttribute::where(['product_id' => $product_id, 'size'=>$size])->first()
        ->toArray();
        $proDetails = Product::select('product_discount','category_id')
        ->where('id',$product_id)->first()->toArray();
        $catDetails = Category::select('category_discount')->where('id',$proDetails['category_id'])
        ->first()->toArray();
        if ($proDetails['product_discount'] >0) {
            // If product discount is added 
            $final_price = $proAttrPrice['price'] - ($proAttrPrice['price'] * $proDetails['product_discount'] / 100);
            $discount = $proAttrPrice['price'] - $final_price;
        }elseif ($catDetails['category_discount']>0) {
            // if product discount is not added and the category discount is added from the admin panel
            $final_price = $proAttrPrice['price'] - ($proAttrPrice['price'] * $catDetails['category_discount']/100);
            $discount = $proAttrPrice['price'] - $final_price;
        }else{
            $final_price = $proAttrPrice['price'];
            $discount = 0;
        }
        // echo "<pre>"; print_r($final_price); die;
        return array('product_price' => $proAttrPrice['price'], 'final_price' => $final_price, 'discount' => $discount);
    }
}
