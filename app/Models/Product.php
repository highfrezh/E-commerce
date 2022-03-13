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
}
