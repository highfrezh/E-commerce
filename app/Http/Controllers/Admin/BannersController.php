<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;

class BannersController extends Controller
{
    public function banners()
    {
        Session::put('page', 'banners');
        $banners = Banner::get()->toArray();
        // dd($banners); die;
        return view('admin.banners.banners')->with(compact('banners'));
    }

    public function updateBannerStatus(Request $request )
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status'] == "Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            Banner::where('id', $data['banner_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'banner_id' => $data['banner_id']]);
        }
    }

    public function addEditBanner(Request $request, $id = null){
        Session::put('page','banners');
        if ($id == "") {
            // Add banners
            $title = "Add Banner Image";
            $banner = new Banner;
            $message = "Banner Added successfully!";
        }else {
            // Edit banner
            $title = "Edit Banner Image";
            $banner = Banner::find($id);
            $message = "Banner  updated Successfully!";
        }
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $banner->link = $data['link'];
            $banner->title = $data['title'];
            $banner->alt = $data['alt'];

            //upload banner image
            if ($request->hasFile('image')) {
                $image_tmp = $request->file('image');
                if ($image_tmp->isValid()) {
                    // Upload Images after Resize
                    $image_name = $image_tmp->getClientOriginalName();
                    $extension = $image_tmp->getClientOriginalExtension();
                    $imageName = $image_name.'-'.rand(111,99999).'.'.$extension;
                    // echo "<pre>"; print_r($imageName);
                    $banner_image_path = 'images/banner_images/'.$imageName;
                    // Resize the images
                    Image::make($image_tmp)->resize(1170,480)->save($banner_image_path);
                    // Save Banner image in the Table
                    $banner->image = $imageName;
                }
            }

            $banner->save();

            Session::flash('success_message', $message);
            return redirect('admin/banner');
        }

        return view('admin.banners.add_edit_banner')->with(compact('title','banner'));
    }

    public function deleteBanner($id){
        // getting banners images
        $bannerImage = Banner::where('id',$id)->first();

        // getting banners path
        $banner_image_path = 'images/banner_images/';

        //Delete Banner Images if exists in the banner folder
        if (file_exists($banner_image_path.$bannerImage->image)) {
            unlink($banner_image_path.$bannerImage->image);
        }
        //delete Banner
        Banner::where('id',$id)->delete();

        $message = "Banner has been deleted successfully!";
        Session::flash('success_message', $message);
        return redirect()->back();
    }
}
