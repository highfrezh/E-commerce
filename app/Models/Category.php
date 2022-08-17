<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'section_id',
        'category_name',
        'category_image',
        'category_discount',
        'discription',
        'url',
        'meta_title',
        'meta_discription',
        'meta_keyword',
        'status',
    ];

    public function subcategories(){
        return $this->hasMany(Category::class,'parent_id')->where('status', 1);
    }
    
    public function section(){
        return $this->belongsTo(Section::class,'section_id')->select('id','name');
    }

    public function parentcategory(){
        return $this->belongsTo(Category::class,'parent_id')->select('id','category_name');
    }

    public static function catDetails($url)
    {
        $catDetails = Category::select('id','category_name','url','description','parent_id','meta_title','meta_description','meta_keywords')->with(['subcategories' =>
        function($query){
            $query->select('id','parent_id','category_name','url','description')->where('status',1);
        }])->where('url',$url)->first()->toArray();
        // dd($catDetails); die;
        if ($catDetails['parent_id'] == 0) {
            // Only Show main category in the Breadcrumb
            $breadcrumbs = '<a href="'.url($catDetails['url']).'">'.$catDetails['category_name'].'</a>';
        }else {
            // show the main and subcategory
            $parentCategory = Category::select('category_name','url')->where('id',$catDetails['parent_id'])
            ->first()->toArray();
            $breadcrumbs = '<a href="'.url($parentCategory['url']).'">'.$parentCategory['category_name'].'</a>&nbsp;<span class="divider">/</span>
            <a href="'.url($catDetails['url']).'">'.$catDetails['category_name'].'</a>';
        }
        $catIds = array();
        $catIds [] = $catDetails['id'];
        foreach ($catDetails['subcategories'] as $key => $subcat) {
            $catIds [] = $subcat['id'];
        }
        // dd($catIds); die;
        return array('catIds' => $catIds, 'catDetails' => $catDetails, 'breadcrumbs' => $breadcrumbs);
    }
}
