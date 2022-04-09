<?php

namespace App\Http\Controllers\Front;

use App\Models\Sms;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{     
    public function loginRegister()
    {
        return view('front.users.login_register');
    }

    public function registerUser(Request $request)
    {
        if ($request->isMethod('post')) {
            Session::forget('error_message');
            Session::forget('success_message');
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            //Check if User already exists 
            $userCount = User::where('email', $data['email'])->count();
            if ($userCount > 0) {
                $message = "Email already exists!";
                Session::flash('error_message', $message);
                return redirect()->back();
            }else{
                //Register The User
                $user = new User;
                $user->name = $data['name'];
                $user->mobile = $data['mobile'];
                $user->email = $data['email'];
                $user->password = bcrypt($data['password']);
                $user->status = 0;
                $user->save();

                //send corfirmation Email to user
                $email = $data['email'];
                $messageData = [
                    'email' => $data['email'],
                    'name' => $data['name'],
                    'code' => base64_encode($data['email'])
                ];
                Mail::send('emails.confirmation',$messageData,function($message) use($email){
                    $message->to($email)->subject('Confirm your E-commerce Account');
                });

                //Redirect Back with success message
                $message = "Please confirm your email to activate your account!";
                Session::put('success_message',$message);
                return redirect()->back();

                // if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
                //      //update User cart with user id
                // if (!empty(Session::get('session_id'))) {
                //     $user_id = Auth::user()->id;
                //     $session_id = Session::get('session_id');
                //     Cart::where('session_id', $session_id)->update(['user_id'=>$user_id]);
                // }

                // //send register sms 

                // // $message = "Dear customer, you have been successfully registered with frezhwebsite. Login to your account to access available offers.";
                // // $mobile = $data['mobile'];
                // // Sms::sendSms($message, $mobile);

                // //Send Register Email
                // $email = $data['email'];
                // $messageData = ['name'=> $data['name'],'mobile'=>$data['mobile'],'email'=>$data['email']];
                // Mail::send('emails.register',$messageData, function($message) use($email){
                //     $message->to($email)->subject('Welcome to Frezhwebsite.');
                // });

                //     return redirect('/');
                // }
            }
        }
    }
    
    public function checkEmail(Request $request)
    {
        // check if email already exist
        $data = $request->all();
        $emailCount = User::where('email', $data['email'])->count();
        if ($emailCount > 0) {
            return "false";
        }else{
            return "true";
        }
    }

    public function loginUser(Request $request)
    {
        if ($request->isMethod('post')) {
            Session::forget('error_message');
            Session::forget('success_message');
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if (Auth::attempt(['email' => $data['email'],'password' => $data['password']])) {
                // Check Email is activated or not
                $userStatus = User::where('email',$data['email'])->first();
                if($userStatus->status == 0){
                    Auth::logout();
                    $message = "Your account is not activated yet! Please confirm your email to activate!";
                    Session::put('error_message',$message);
                    return redirect()->back();
                }

                //update User cart with user id
                if (!empty(Session::get('session_id'))) {
                    $user_id = Auth::user()->id;
                    $session_id = Session::get('session_id');
                    Cart::where('session_id', $session_id)->update(['user_id'=>$user_id]);
                }
                return redirect('/cart');
            }else{
                $message = "Invalid Email or Password";
                Session::flash('error_message',$message);
                return redirect()->back();
            }
        }
    }

    public function logoutUser()
    {
        Auth::logout();
        return redirect('/');
    }
}
