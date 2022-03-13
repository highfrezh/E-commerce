<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Section;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductsImage;
use App\Models\ProductsAttribute;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;

class ProductsController extends Controller
{
    public function products(){
        Session::put('page', 'products');
        $products = Product::with(['category','section'])->get();
        // $products = json_decode(json_encode($products));
        // echo "<pre>"; print_r($products);die;
        return view('admin.products.products')->with(compact('products'));
    }

    public function updateProductStatus(Request $request )
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status'] == "Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            Product::where('id', $data['product_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'product_id' => $data['product_id']]);
        }
    }

    public function deleteProduct($id){
        //delete category
        Product::where('id',$id)->delete();

        $message = "Product has been deleted successfully!";
        session::flash('success_message', $message);
        return redirect()->back();
    }

    public function addEditProduct(Request $request, $id=null)
    {
        if ($id=="") {
            $title = "Add Product";
            $product = new Product;
            $productdata = array ();
            $message = "Product added successfully !!";
        }else{
            $title = "Edit Product";
            $productdata = Product::find($id);
            $productdata = json_decode(json_encode($productdata), true);
            $product = Product::find($id);
            $message = "Product updated successfully !!";
            // echo "<pre>"; print_r($productdata);die;
        };
        
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data);die;

            //Product Validation
            $rules = [
                'category_id' => 'required',
                'brand_id' => 'required',
                'product_name' => 'required|regex:/^[\pL\s\-]+$/u',
                'product_code' => 'required|regex:/^[\w-]*$/',
                'product_price' => 'required|numeric',
                'product_color' => 'required|regex:/^[\pL\s\-]+$/u',
            ];

            $customMessages = [ 
                'category_id.required' => 'Category is required',
                'brand_id.required' => 'Brand is required',
                'product_name.required' => 'Product Name is required',
                'product_name.regex' => 'Valid Product Name is required',
                'product_code.required' => 'Product Code is required',
                'product_code.regex' => 'Valid Product Code is required',
                'product_price.required' => 'Product Price is required',
                'product_price.numeric' => 'Valid Product Price is required',
                'product_color.required' => 'Product Color is required',
                'product_color.regex' => 'Valid Product Color is required',
            ];

            $this->validate($request,$rules,$customMessages);

            if (empty($data['is_featured'])) {
                $is_featured = "No";
            }else {
                $is_featured = "Yes";
            }
            
            // upload Product Image
            if ($request->hasFile('main_image')) {
                $image_tmp = $request->file('main_image');
                if ($image_tmp->isValid()) {
                    // Upload Images after Resize
                    $image_name = $image_tmp->getClientOriginalName();
                    $extension = $image_tmp->getClientOriginalExtension();
                    $imageName = $image_name.'-'.rand(111,99999).'.'.$extension;
                    // echo "<pre>"; print_r($imageName);
                    $large_image_path = 'images/product_images/large/'.$imageName;
                    $medium_image_path = 'images/product_images/medium/'.$imageName;
                    $small_image_path = 'images/product_images/small/'.$imageName;
                    Image::make($image_tmp)->save($large_image_path); //1000 x 1000px
                    Image::make($image_tmp)->resize(500,500)->save($medium_image_path);
                    Image::make($image_tmp)->resize(250,250)->save($small_image_path);
                    $product->main_image = $imageName;
                }
            }

            // upload Product video
            if ($request->hasFile('product_video')) {
                $video_tmp = $request->file('product_video');
                if ($video_tmp->isValid()) {
                    // Upload video  after Resize
                    $video_name = $video_tmp->getClientOriginalName();
                    $extension = $video_tmp->getClientOriginalExtension();
                    $videoName = $video_name.'-'.rand(111,99999).'.'.$extension;
                    echo "<pre>"; print_r($videoName);
                    $video_path = 'videos/product_videos/';
                    $video_tmp->move($video_path,$videoName);
                    //Save Video in product table
                    $product->product_video = $videoName;
                }
            }
            
            //save Product details in product table
            $categoryDetails = Category::find($data['category_id']);
            $product->section_id = $categoryDetails['section_id'];
            $product->brand_id = $data['brand_id'];
            $product->category_id = $data['category_id'];
            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            $product->product_color = $data['product_color'];
            $product->product_price = $data['product_price'];
            $product->product_discount = $data['product_discount'];
            $product->product_weight = $data['product_weight'];
            $product->description = $data['description'];
            $product->wash_care = $data['wash_care'];
            $product->fabric = $data['fabric'];
            $product->pattern = $data['pattern'];
            $product->sleeve = $data['sleeve'];
            $product->fit = $data['fit'];
            $product->occasion = $data['occasion'];
            $product->meta_title = $data['meta_title'];
            $product->meta_keywords = $data['meta_keywords'];
            $product->meta_description = $data['meta_description'];
            $product->status = 1;
            $product->is_featured = $is_featured;
            $product->save();
            Session::flash('success_message', $message);
            return redirect('admin/products');

        }

        //product filters
        $productFilters = Product::productFilters();
        $fabricArray = $productFilters['fabricArray'];
        $sleeveArray = $productFilters['sleeveArray'];
        $patternArray = $productFilters['patternArray'];
        $fitArray = $productFilters['fitArray'];
        $occasionArray = $productFilters['occasionArray'];
        
        //Sections with Categories and sub Category
        $categories = Section::with('categories')->get();
        $categories = json_decode(json_encode($categories),true);
        // echo "<pre>"; print_r($categories); die;

        $brands = Brand::where('status',1)->get();
        $brands = json_decode(json_encode($brands),true);

        return view('admin.products.add_edit_product')->with(compact('title','fabricArray',
    'sleeveArray','patternArray','fitArray','occasionArray','categories','productdata','brands'));
    }

    public function deleteProductImage($id){
        // Get Product Image
        $productImage = Product::select('main_image')->where('id', $id)->first();

        //Get Product Image Path
        $small_image_path = 'images/product_images/small/';
        $medium_image_path = 'images/product_images/medium/';
        $large_image_path = 'images/product_images/large/';

        //Deleting Product Image from product small folder if exist
        if (file_exists($small_image_path.$productImage->main_image)) {
            unlink($small_image_path.$productImage->main_image);
        } 

        //Deleting Product Image from product medium folder if exist
        if (file_exists($medium_image_path.$productImage->main_image)) {
            unlink($medium_image_path.$productImage->main_image);
        } 

        //Deleting Product Image from product large folder if exist
        if (file_exists($large_image_path.$productImage->main_image)) {
            unlink($large_image_path.$productImage->main_image);
        } 

        //Delete Product Image from categories table
        Product::where('id', $id)->update(['main_image'=>'']);

        $message = "Product Image has been deleted successfully!";
        session::flash('success_message', $message);
        return redirect()->back();
        // return redirect()->back()->with('flash_message_success','Category Image has been deleted successfully!');
    } 

    public function deleteProductVideo($id){
        // Get Product Video
        $productVideo = Product::select('product_video')->where('id', $id)->first();

        //Get Product Video Path
        $product_video_path = 'videos/product_videos/';
        
        //Deleting Product Video from product video folder if exist
        if (file_exists($product_video_path.$productVideo->product_video)) {
            unlink($product_video_path.$productVideo->product_video);
        } 

        //Delete Product Video from categories table
        Product::where('id', $id)->update(['product_video'=>'']);

        $message = "Product Video has been deleted successfully!";
        session::flash('success_message', $message);
        return redirect()->back();
        // return redirect()->back()->with('flash_message_success','Category Image has been deleted successfully!');
    } 

    public function addAttributes(Request $request, $id){
        if ($request->isMethod('post')) {
            $data = $request->all();
            // $attribute = $data['size'][0];
            // echo "<pre>"; print_r($attribute ); die;
            foreach ($data['sku'] as $key => $value) {
                if (!empty($value)) {

                    // SKU already exist Check
                    $attrCountSKU = ProductsAttribute::where(['sku' => $value])->count();
                    if ($attrCountSKU > 0) {
                        $message = 'SKU already exists. Please add another SKU!';
                        Session::flash('error_message',$message);
                        return redirect()->back();
                    }

                    // Size already exist Check
                    $attrCountSize = ProductsAttribute::where(['product_id' => $id, 'size' => $data['size'][$key]])->count();
                    if ($attrCountSize > 0) {
                        $message = 'Size already exists. Please add another Size!';
                        Session::flash('error_message',$message);
                        return redirect()->back();
                    }


                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $value;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->status = 1;
                    $attribute->save();

                }
            }
            $message = 'product Attribute has been Added successfull.!';
                    Session::flash('success_message',$message);
                    return redirect()->back();
        }
        $productdata = Product::select('id', 'product_name', 'product_code', 'product_color','product_price','main_image' )->with('attributes')->find($id);
        $productdata = json_decode(json_encode($productdata), true);
        // echo "<pre>"; print_r($productdata);  die;
        $title = "Add  Attribute";
        return view('admin.products.add_attributes')->with(compact('productdata','title'));
    }

    public function editAttributes(Request $request, $id){
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            foreach ($data['attrId'] as $key => $attr) {
                if (!empty($attr)) {
                    ProductsAttribute::where(['id' => $data['attrId'][$key]])
                    ->update(['price' => $data['price'][$key], 'stock' =>$data['stock'][$key]]);
                }
            }
            $message = 'product Attribute has been Updated successfull.!';
                    Session::flash('success_message',$message);
                    return redirect()->back();
        }
    }

    public function updateAttributeStatus(Request $request )
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status'] == "Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            ProductsAttribute::where('id', $data['attribute_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'attribute_id' => $data['attribute_id']]);
        }
    }

    public function updateImageStatus(Request $request )
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status'] == "Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            ProductsImage::where('id', $data['image_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'image_id' => $data['image_id']]);
        }
    }

    public function deleteAttribute($id){
        //delete attribute
        ProductsAttribute::where('id',$id)->delete();

        $message = "Product Attribute has been deleted successfully!";
        session::flash('success_message', $message);
        return redirect()->back();
    }

    public function addImages(Request $request, $id){
        if ($request->isMethod('post')) {
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $key => $image) {
                    $productImage = new ProductsImage;
                    $image_tmp = Image::make($image);
                    $extension = $image->getClientOriginalExtension();
                    $imageName = rand(111,999999).time().".".$extension;

                    //Setting different path for images
                    $large_image_path = 'images/product_images/large/'.$imageName;
                    $medium_image_path = 'images/product_images/medium/'.$imageName;
                    $small_image_path = 'images/product_images/small/'.$imageName;
                    //Saving and resizing images
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(520,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(260,300)->save($small_image_path);
                    $productImage->image = $imageName;
                    $productImage->product_id = $id;
                    $productImage->status = 1;
                    $productImage->save();
                }

                $message = "Product images has been Added successfully!";
                session::flash('success_message', $message);
                return redirect()->back();
            }
        }
        $productdata = Product::with('images')
        ->select('id', 'product_name', 'product_code', 'product_color', 'main_image' )
        ->find($id);
        // echo "<pre>"; print_r($productdata); die;
        $productdata = json_decode(json_encode($productdata),true);
        $title = "Product Image";
        return view('admin.products.add_images')->with(compact('productdata',"title"));
    }

    public function deleteImage($id){
        // Get Product Image
        $productImage = ProductsImage::select('image')->where('id', $id)->first();

        //Get Product Image Path
        $small_image_path = 'images/product_images/small/';
        $medium_image_path = 'images/product_images/medium/';
        $large_image_path = 'images/product_images/large/';

        //Deleting Product Image from product small folder if exist
        if (file_exists($small_image_path.$productImage->image)) {
            unlink($small_image_path.$productImage->image);
        } 

        //Deleting Product Image from product medium folder if exist
        if (file_exists($medium_image_path.$productImage->image)) {
            unlink($medium_image_path.$productImage->image);
        } 

        //Deleting Product Image from product large folder if exist
        if (file_exists($large_image_path.$productImage->image)) {
            unlink($large_image_path.$productImage->image);
        } 

        //Delete Product Image from categories table
        ProductsImage::where('id', $id)->delete();

        $message = "Product Image has been deleted successfully!";
        session::flash('success_message', $message);
        return redirect()->back();
        // return redirect()->back()->with('flash_message_success','Category Image has been deleted successfully!');
    } 


}
