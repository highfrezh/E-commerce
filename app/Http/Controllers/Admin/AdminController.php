<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\AdminsRole;
use App\Models\OrderSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function dashboard()
    {
        Session::put('page', 'dashboard');
        return view('admin.admin_dashboard');
    }

    public function settings()
    {
        Session::put('page', 'settings');
        // Auth::guard('admin')->user();
        $adminDetails = Admin::where('email', Auth::guard('admin')->user()->email)->first();
        return view('admin.admin_settings')->with(compact('adminDetails'));
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            $rules = [
                'email' => 'required|email|max:255',
                'password' => 'required',
            ];

            $customMessages = [
                'email.required' => 'Email address is required',
                'email.email' => 'Valid email is required',
                'password' => 'Password is required',
            ];

            $this->validate($request, $rules,$customMessages);

            if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password'],'status'=>1])) {
                return redirect('admin/dashboard');
            } else {
                Session::flash('error_message', 'Invalid Email or Password');
                return redirect()->back();
            }
        }

        return view('admin.admin_login');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin');
    }
    
    public function chkCurrentPassword(Request $request)
    {
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
            echo "true";
        }else{
            echo "false";
        }
    }

    public function updateCurrentPassword(Request $request)
    {
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            //check if current password is correct
            if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
                // check if new and confirm password is match
                if ($data['new_pwd'] == $data['confirm_pwd']) {
                    Admin::where('id',Auth::guard('admin')->user()->id)->update(['password' => Hash::make($data['new_pwd'])]);
                    Session::flash('success_message','Password has been update successfully!.');
                }else{
                    Session::flash('error_message','New password and Comfirm password not match');
                }
            }else{
                Session::flash('error_message','Your current password is incorrect');
            }

            return redirect()->back();
        }
    }

    public function  updateAdminDetails(Request $request)
    {
        Session::put('page', 'update-admin-details');
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die; //printing the user input
            $rules = [
                'admin_name' => 'required|regex:/^[\pL\s\-]+$/u ',
                'admin_mobile' => 'required|numeric',
                'admin_image' => 'image'
            ];

            $customMessages = [ 
                'admin_name.required' => 'Name is required',
                'admin_name.alpha' => 'Valid Name is required',
                'admin_mobile.required' => 'Mobile is required',
                'admin_mobile.numeric' => 'Valid Mobile is required',
                'admin_image.image' => 'Valid image is required',
            ];

            $this->validate($request,$rules,$customMessages);

            //uploadnImage
            if($request->hasFile('admin_image')){
                $image_temp = $request->file('admin_image');
                if ($image_temp->isValid()) {
                    //Get Image extension
                    $extension = $image_temp->getClientOriginalExtension();
                    //Generate New Image name
                    $imageName = rand(111,99999).'.'.$extension;
                    $imagePath = 'images/admin_images/admin_photos/'.$imageName;
                    //upload the Image
                    Image::make($image_temp)->save($imagePath);
                }elseif (!empty($data['current_admin_image'])) {
                    $imageName = $data['current_admin_image'];
                }else {
                    $imageName = "";
                }
            }
            //update admin details
            Admin::where('email',Auth::guard('admin')->user()->email)
            ->update(['name' => $data['admin_name'], 'mobile' => $data['admin_mobile'], 'image' => $imageName]);
            Session::flash('success_message', 'Admin details updated successfully!');
            return redirect()->back();

        }
        return view('admin.update_admin_details');
    }

    public function adminsSubadmins()
    {
        if(Auth::guard('admin')->user()->type == "subadmin"){
            return redirect('admin/dashboard');
        }
        Session::put('page','admins_subadmins');
        $admins_subadmins = Admin::get();
        return view('admin.admins_subadmins.admins_subadmins')->with(compact('admins_subadmins'));
    }

    public function updateAdminStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status'] == "Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            Admin::where('id', $data['admin_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'admin_id' => $data['admin_id']]);
        }
    }

    public function deleteAdmin($id){
        //delete attribute
        Admin::where('id',$id)->delete();

        $message = "Admin has been deleted successfully!";
        session::flash('success_message', $message);
        return redirect()->back();
    }

    public function addEditAdminSubadmin(Request $request, $id=null)
    {
        if($id==""){
            //Add Admin/Sub-Admin
            $title   = "Add Admin/Sub-Admin";
            $admindata = new Admin;
            $message = "Admin/Sub-Admin added successfully!";
        }else{
            // Edit Admin/Sub-Admin
            $title = "Edit Admin/Sub-Admin";
            $admindata = Admin::find($id);
            $message = "Admin/Sub-Admin updated successfully!";
        }

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die; //printing the user input
            if($id == ""){
                $adminCount = Admin::where('email',$data['admin_email'])->count();
                if($adminCount > 0){
                    Session::flash('error_message','Admin/Sub-Admin already exists!');
                    return redirect('admin/admins-subadmins');
                }
            }
            $rules = [
                'admin_name' => 'required|regex:/^[\pL\s\-]+$/u ',
                'admin_mobile' => 'required|numeric',
                'admin_image' => 'image'
            ];

            $customMessages = [ 
                'admin_name.required' => 'Name is required',
                'admin_name.alpha' => 'Valid Name is required',
                'admin_mobile.required' => 'Mobile is required',
                'admin_mobile.numeric' => 'Valid Mobile is required',
                'admin_image.image' => 'Valid image is required',
            ];

            $this->validate($request,$rules,$customMessages);

            //uploadnImage
            if($request->hasFile('admin_image')){
                $image_temp = $request->file('admin_image');
                if ($image_temp->isValid()) {
                    //Get Image extension
                    $extension = $image_temp->getClientOriginalExtension();
                    //Generate New Image name
                    $imageName = rand(111,99999).'.'.$extension;
                    $imagePath = 'images/admin_images/admin_photos/'.$imageName;
                    //upload the Image
                    Image::make($image_temp)->save($imagePath);
                }
                // elseif (!empty($data['current_admin_image'])) {
                //     $imageName = $data['current_admin_image'];
                // }else {
                //     $imageName = "no-admin-image.jpg";
                // }
            }else if(!empty($data['current_admin_image'])){
                $imageName = $data['current_admin_image'];
            }else{
                $imageName = "no-admin-image.jpg";
            }
            
            $admindata->image = $imageName;
            $admindata->name = $data['admin_name'];
            $admindata->mobile = $data['admin_mobile'];
            if($id==""){
                $admindata->email = $data['admin_email'];
                $admindata->type = $data['admin_type'];
            }
            if($data['admin_password'] != ""){
                $admindata->password = bcrypt($data['admin_password']);
            }
            $admindata->save();
            Session::flash('success_message', $message);
            return redirect('admin/admins-subadmins');

        }
        return view('admin.admins_subadmins.add_edit_admin_subadmin')->with(compact('title','admindata'));
    }
    
    public function updateRole(Request $request, $id=null)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            unset($data['_token']);
            
            AdminsRole::where('admin_id',$id)->delete();
            
            foreach ($data as $key => $value) {
                if(isset($value['view'])){
                    $view = $value['view'];
                }else{
                    $view = 0;
                }
                if(isset($value['edit'])){
                    $edit = $value['edit'];
                }else{
                    $edit = 0;
                }
                if(isset($value['full'])){
                    $full = $value['full'];
                }else{
                    $full = 0;
                }

                AdminsRole::insert(['admin_id' => $id, 'module'=>$key,
                'view_access'=>$view,'edit_access'=>$edit,'full_access'=>$full]);
            }

            $message = "Roles Updated successfully!";
            Session::flash('success_message',$message);
            return redirect()->back();
        }
        $adminDetails = Admin::where('id',$id)->first()->toArray();
        $adminRoles = AdminsRole::where('admin_id',$id)->get()->toArray();
        $title = "Update ".$adminDetails['name']."(".$adminDetails['type'].")"." Roles/Permissions";
        return view('admin.admins_subadmins.update_roles')->with(compact('title','adminDetails','adminRoles'));
    }

    public function orderSettings(Request $request)
    {
        Session::put('page', 'order settings');

        $orderSettings = OrderSetting::where('id',1)->first()->toArray();
        // dd($orderSettings);die;
        $title = "Other Settings";
        if ($request->isMethod('post')) {
            OrderSetting::where('id',1)->update(['min_cart_value' => $request['min_cart_value'],
            'max_cart_value' => $request['max_cart_value']]);
            $message = "Min/Max Cart Value updated successfully!";
            Session::flash('success_message',$message);
            return redirect()->back();
        }
        return view('admin.order_settings')->with(compact('title','orderSettings'));
    }
}
