<?php

namespace App\Http\Controllers\Front;

use App\Models\Rating;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FrontRatingController extends Controller
{
    public function addRating(Request $request)
    {
        if($request->isMethod('post')){
            $data = $request->all();
            if (!Auth::check()) {
                $message = "Login to rate this product";
                Session::flash('error_message',$message);
                return redirect()->back();
            }

            if(empty($data['rate'])){
                $message = "Add atleast one star for the product";
                Session::flash('error_message',$message);
                return redirect()->back();
            }

            $ratingCount = Rating::where(['user_id'=>Auth::user()->id,'product_id'=>$data['product_id']])->count();
            if($ratingCount > 0){
                $message = "Your rating exist for this product";
                Session::flash('error_message',$message);
                return redirect()->back();
            }else{
                $rating = new Rating;
                $rating->user_id = Auth::user()->id;
                $rating->product_id = $data['product_id'];
                $rating->review = $data['review'];
                $rating->rating = $data['rate'];
                $rating->status = 0;
                $rating->save();
                $message = "Thanks for rating this product! It will be shown once approved.";
                Session::flash('success_message',$message);
                return redirect()->back();
            }
        }
    }
}
