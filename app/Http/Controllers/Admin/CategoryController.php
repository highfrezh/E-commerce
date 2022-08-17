<?php

namespace App\Http\Controllers\Admin;

use App\Models\Section;
use App\Models\Category;
use App\Models\AdminsRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function categories(){
        Session::put('page', 'categories');
        $categories = Category::with('section', 'parentcategory')->get();
        // $categories = json_decode(json_encode($categories));
        // echo "<pre>"; print_r($categories); die;

        // Set Admin/Subadmin for Categories
        $categoryModuleCount = AdminsRole::where(['admin_id'=>Auth::guard('admin')->user()->id,
        'module'=>'categories'])->count();
        if (Auth::guard('admin')->user()->type == "superadmin") {
            $categoryModule['view_access'] = 1;
            $categoryModule['edit_access'] = 1;
            $categoryModule['full_access'] = 1;
        }else if($categoryModuleCount == 0 ){
            $message = "The feature is restricted for you!";
            Session::flash('error_message',$message);
            return redirect('admin/dashboard');
        }else{
            $categoryModule = AdminsRole::where(['admin_id'=>Auth::guard('admin')->user()->id,
        'module'=>'categories'])->first()->toArray();
        }

        return view('admin.categories.categories')->with(compact('categories','categoryModule'));
    }

    public function updateCategoryStatus(Request $request )
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status'] == "Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            Category::where('id', $data['category_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'category_id' => $data['category_id']]);
        }
    }

    public function addEditCategory(Request $request, $id=null)
    {
        if ($id=="") {
            //Add Category functionality
            $title = "Add Category";
            $category = new Category;
            $categorydata = array();
            $getCategories = array();
            $message = "Category added successfully!";
        }else {
            //Edit Category functionality
            $title = "Edit Category";
            $categorydata = Category::where('id', $id)->first();
            $categorydata = json_decode(json_encode($categorydata), true);
            // echo "<pre>"; print_r($categorydata); die;
            $getCategories = Category::with('subcategories')->where(['parent_id' =>0,
            'section_id' => $categorydata['section_id']])->get();
            $getCategories = json_decode(json_encode($getCategories),true);
            $category = Category::find($id);
            $message = "Category updated successfully!";
        }

        if ($request->isMethod('post')) {
            $data = $request->all();

            $rules = [
                'category_name' => 'required|regex:/^[\pL\s\-]+$/u ',
                'section_id' => 'required',
                'url' => 'required',
                'category_image' => 'image'
            ];

            $customMessages = [ 
                'category_name.required' => 'Name is required',
                'category_name.regex' => 'Valid Name is required',
                'section_id.required' => 'Section is required',
                'url.required' => 'Category URL is required',
                'category_image.image' => 'Valid Category Image is required',
            ];

            $this->validate($request,$rules,$customMessages);


             //uploadnImage
            if($request->hasFile('category_image')){
                $image_temp = $request->file('category_image');
                if ($image_temp->isValid()) {
                    //Get Image extension
                    $extension = $image_temp->getClientOriginalExtension();
                    //Generate New Image name
                    $imageName = rand(111,99999).'.'.$extension;
                    $imagePath = 'images/category_images/'.$imageName;
                    //upload the Image
                    Image::make($image_temp)->save($imagePath);
                    $category->category_image = $imageName;
                    
            }
        }
            if(empty($data['description'])){
                $data['description'] = "";
            }
            if(empty($data['meta_title'])){
                $data['meta_title'] = "";
            }
            if(empty($data['meta_description'])){
                $data['meta_description'] = "";
            }
            if(empty($data['meta_keywords'])){
                $data['meta_keywords'] = "";
            }

            // echo "<pre>"; print_r($data);
            $category->parent_id = $data['parent_id'];
            $category->section_id = $data['section_id'];
            $category->category_name = $data['category_name'];
            $category->category_discount = $data['category_discount'];
            $category->description = $data['description'];
            $category->url = $data['url'];
            $category->meta_title = $data['meta_title'];
            $category->meta_description = $data['meta_description'];
            $category->meta_keywords = $data['meta_keywords'];
            $category->status = 1;
            $category->save(); 

            session::flash('success_message', $message);
            return redirect('admin/categories');
        }

        // Get All Section
        $getSections = Section::get();

        return view('admin.categories.add_edit_category')->with(compact('title', 'getSections', 'categorydata', 'getCategories'));
    }

    public function appendCategoryLevel(Request $request){
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $getCategories = Category::with('subcategories')->where(['section_id' => $data['section_id'],
            'parent_id' => 0, 'status'=>1])->get();
            $getCategories = json_decode(json_encode($getCategories), true);
            //  echo "<pre>"; print_r($getCategories);
            return view('admin.categories.append_categories_level')->with(compact('getCategories'));
        }

    }

    public function deleteCategoryImage($id){
        // Get Category Image
        $categoryImage = Category::select('category_image')->where('id', $id)->first();

        //Get Category Image Path
        $category_image_path = 'images/category_images';

        //Deleting Category Image from category_image folder if exist
        if (file_exists($category_image_path.$categoryImage->category_image)) {
            unlink($category_image_path.$categoryImage->category_image);
        } 

        //Delete Category Image from categories table
        Category::where('id', $id)->update(['category_image'=>'']);

        $message = "Category Image has been deleted successfully!";
        session::flash('success_message', $message);
        return redirect()->back();
        // return redirect()->back()->with('flash_message_success','Category Image has been deleted successfully!');
    } 

    public function deleteCategory($id){
        //delete category
        Category::where('id',$id)->delete();

        $message = "Category has been deleted successfully!";
        session::flash('success_message', $message);
        return redirect()->back();
    }
}
