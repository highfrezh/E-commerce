<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Coupon;
use App\Models\Section;
use App\Models\AdminsRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CouponsController extends Controller
{
    public function coupons()
    {
        Session::put('page','coupons');
        $coupons = Coupon::get()->toArray();
        // dd($coupons);die;

         // Set Admin/Subadmin for Coupons
        $couponModuleCount = AdminsRole::where(['admin_id'=>Auth::guard('admin')->user()->id,
        'module'=>'coupons'])->count();
        if (Auth::guard('admin')->user()->type == "superadmin") {
            $couponModule['view_access'] = 1;
            $couponModule['edit_access'] = 1;
            $couponModule['full_access'] = 1;
        }else if($couponModuleCount == 0 ){
            $message = "The feature is restricted for you!";
            Session::flash('error_message',$message);
            return redirect('admin/dashboard');
        }else{
            $couponModule = AdminsRole::where(['admin_id'=>Auth::guard('admin')->user()->id,
        'module'=>'coupons'])->first()->toArray();
        }

        return view('admin.coupons.coupons')->with(compact('coupons','couponModule'));
    }

    public function updateCouponStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == "Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            Coupon::where('id',$data['coupon_id'])->update(['status'=>$status]);
            return response()->json(['status'=> $status, 'coupon_id'=>$data['coupon_id']]);
        }
    }

    public function addEditCoupon(Request $request, $id=null)
    {
        Session::forget('success_message');
        if ($id=="") {
            //Add coupon
            $coupon = new Coupon;
            $selCats = array();
            $selUsers = array();
            $title = "Add Coupon";
            $message = "Coupon added successfully!";
        }else{
            //Update Coupon
            $coupon = Coupon::find($id);
            $selCats = explode(',',$coupon['categories']);
            $selUsers = explode(',',$coupon['users']);
            // dd($selUsers); die;
            $title = "Edit Coupon";
            $message = "Coupon updated successfully!";
        }
        if($request->isMethod('post')){
            $data = $request->all();

            //Coupon Validation
            $rules = [
                'categories' => 'required',
                'coupon_option' => 'required',
                'coupon_type' => 'required',
                'amount_type' => 'required',
                'amount' => 'required|numeric',
                'expiry_date' => 'required'
            ];

            $customMessages = [ 
                'categories.required' => 'Select Categories',
                'coupon_option.required' => 'Select Coupon Option',
                'coupon_type.required' => 'Select Coupon Type',
                'amount_type.required' => 'Select Amount Type',
                'amount.required' => 'Enter Amount',
                'amount.numeric' => 'Enter Valid Amount',
                'expiry_date.required' => 'Enter Expiry date'
            ];
            $this->validate($request,$rules,$customMessages);

            if(isset($data['users'])){
                 //convert Array to string
                $users = implode(',',$data['users']);
            }else{
                $users = "";
            }
            if(isset($data['categories'])){
                //convert Array to string
                $categories = implode(',',$data['categories']);
            }
            if($data['coupon_option'] == "Automatic"){
                $coupon_code = str_random(8);
            }else{
                $coupon_code = $data['coupon_code'];
            }

            $coupon->coupon_option = $data['coupon_option'];
            $coupon->coupon_code = $coupon_code;
            $coupon->categories = $categories;
            $coupon->users = $users;
            $coupon->coupon_type = $data['coupon_type'];
            $coupon->amount_type = $data['amount_type'];
            $coupon->amount = $data['amount'];
            $coupon->expiry_date = $data['expiry_date'];
            $coupon->status = 1;
            $coupon->save();

            Session::flash('success_message',$message);
            return redirect('admin/coupons');
        }

        // Sections with Categories and sub Categories
        $categories = Section::with('categories')->get()->toArray();

        // Users
        $users = User::select('email')->where('status',1)->get()->toArray();
        // dd($users);die;

        return view('admin.coupons.add_edit_coupon')->with(compact('title','coupon','categories','users','selCats','selUsers'));
    }

    public function deleteCoupon($id){
        //delete Banner
        Coupon::where('id',$id)->delete();

        $message = "Coupon has been deleted successfully!";
        Session::flash('success_message', $message);
        return redirect()->back();
    }
}
