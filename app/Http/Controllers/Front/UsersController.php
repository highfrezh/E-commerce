<?php

namespace App\Http\Controllers\Front;

use App\Models\Sms;
use App\Models\Cart;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
    
    public function confirmAccount($email)
    {
        Session::forget('error_message');
        Session::forget('success_message');
        // Decode User Email
        $email = base64_decode($email);
        
        // Check User Email exists
        $userCount = User::where('email',$email)->count();
        if ($userCount > 0) {
            //user Email is already activated or not
            $userDetails = User::where('email',$email)->first();
            if ($userDetails->status == 1) {
                $message = "Your Email account is already activated. You can login now.";
                Session::put('error_message',$message);
                return redirect('login-register');
            }else {
                //Update User Status to 1 to activate account
                User::where('email',$email)->update(['status' => 1 ]);

                //send register sms 

                // $message = "Dear customer, you have been successfully registered with frezhwebsite. Login to your account to access available offers.";
                // $mobile = $userDetails['mobile'];
                // Sms::sendSms($message, $mobile);

                //Send Register Email
                $messageData = ['name'=> $userDetails['name'],'mobile'=>$userDetails['mobile'],'email'=>$email];
                Mail::send('emails.register',$messageData, function($message) use($email){
                    $message->to($email)->subject('Welcome to Frezhwebsite.');
                });
                // Redirect Login/Register page with success message
                $message = "Your Email account is activated. You can login now.";
                Session::put('success_message',$message);
            }
        }else {
            abort(404);
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
        Session::flush();
        return redirect('/');
    }

    public function forgotPassword(Request $request)
    {
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $emailCount = User::where('email', $data['email'])->count();
            if ($emailCount == 0) {
                $message = "Email does not exists!";
                Session::put('error_message', $message);
                Session::forget('success_message');
                return redirect()->back();
            }
            // laravel help is needed install via composer to generate random password!....

            // Generate Random Password
            // echo $random_password = str_random(8); die;
            $random_password = str_random(8);

            //Encode/Secure Password
            $new_password = bcrypt($random_password);

            //Update the Password
            User::where('email',$data['email'])->update(['password' => $new_password]);

            //Get User Name
            $userName = User::select('name')->where('email',$data['email'])->first();

            //send Forget Password Email
            $email = $data['email'];
            $name = $userName->name;
            $messageData = [
                'email' => $email,
                'name' => $name,
                'password' => $random_password
            ];
            Mail::send('emails.forget_password',$messageData,function($message)use($email){
                $message->to($email)->subject('New Password - frezhwebsite');
            });

            //Redirect to login/Register page with Success message
            $message = "Please check your email for new password!";
            Session::put('success_message');
            Session::forget('error_message');
            return redirect('login-register');
        }
        return view('front.users.forgot_password');
    }

    public function account(Request $request)
    {
        $user_id = Auth::user()->id;
        $userDetails = User::find($user_id)->toArray();
        
        $countries = Country::where('status',1)->get()->toArray();
        if ($request->isMethod('post')) {
            $data = $request->all();
            
            $rules = [
                'name' => 'required|regex:/^[\pL\s\-]+$/u',
                'mobile' => 'required|numeric',
            ];
            $customMessage = [
                'name.required' => 'Name is required',
                'name.regex' => 'Valid Name is required',
                'mobile.required' => 'Mobile is required',
            ];
            $this->validate($request,$rules,$customMessage);

            $user = User::find($user_id);
            $user->name = $data['name'];
            $user->address = $data['address'];
            $user->city = $data['city'];
            $user->state = $data['state'];
            $user->country = $data['country'];
            $user->pincode = $data['pincode'];
            $user->mobile = $data['mobile'];
            $user->save();
            $message = "Your account details has been updated successfully!";
            Session::put('success_message',$message);
            return redirect()->back();
        }else{

            return view('front.users.account')->with(compact('userDetails','countries'));
        }
    }

    public function chkUserPassword(Request $request)
    {
        //check user passwword
        if ($request->isMethod('post')) {
            $data = $request->all();
            $user_id = Auth::User()->id;
            $chkPassword = User::select('password')->where('id',$user_id)->first();
            if (Hash::check($data['current_pwd'],$chkPassword->password)) {
                return "true";
            }else{
                return "false";
            }
        }
    }

    public function updateUserPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $user_id = Auth::User()->id;
            $chkPassword = User::select('password')->where('id',$user_id)->first();
            if (Hash::check($data['current_pwd'],$chkPassword->password)) {
                //update Current password
                $new_pwd = bcrypt($data['new_pwd']);
                User::where('id',$user_id)->update(['password' => $new_pwd]);
                $message = "Password updated successfully!";
                Session::put('success_message',$message);
                Session::forget('error_message');
                return redirect()->back();
            }else{
                $message = "Current Password is Incorrect!";
                Session::put('error_message',$message);
                Session::forget('success_message');
                return redirect()->back();
            }
        }
    }
}
