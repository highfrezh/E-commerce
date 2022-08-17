<?php

namespace App\Http\Controllers\Front;

use App\Models\CmsPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class FrontCmsController extends Controller
{
    public function cmsPage()
    {
        $currentRoute = Route::getFacadeRoot()->current()->uri(); // getting the current url.
        $cmsRoutes = CmsPage::where('status',1)->get()->pluck('url')->toArray();
        if(in_array($currentRoute,$cmsRoutes)){
            $cmsPageDetails = CmsPage::where('url',$currentRoute)->first()->toArray();
            $meta_title = $cmsPageDetails['meta_title'];
            $meta_description = $cmsPageDetails['meta_description'];
            $meta_keywords = $cmsPageDetails['meta_keyword'];
            return view('front.pages.cms_page')->with(compact('cmsPageDetails','meta_title','meta_description','meta_keywords'));
        }else{
            abort(404);
        }
        
    }

    public function contact(Request $request)
    {
        if($request->isMethod('post')){
            $data = $request->all();
            // dd($data);die;

            //Product Validation
            $rules = [
                'name' => 'required',
                'email' => 'required|email',
                'subject' => 'required',
                'message' => 'required'
            ];

            $customMessages = [ 
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.email' => 'Valid Email is required',
                'subject.required' => 'Subject is required',
                'message.required' => 'Message is required'
            ];

            $validator = Validator::make($data,$rules,$customMessages);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            //Send User Enquire to the admin Email
            // $email = "admin@yopmail.com"; //Admin email where the message is delivered to.
            // $messageData = [
            //     'name' => $data['name'],
            //     'email' => $data['email'],
            //     'subject' => $data['subject'],
            //     'comment' => $data['message'],
            // ];
            // Mail::send('emails.enquiry',$messageData,function($message) use($email){
            //     $message->to($email)->subject("Enquiry from E-Commerce Website");
            // });

            $message = "Thanks for your enquiry. We will get back to you soon.";
            Session::flash('success_message',$message);
            return redirect()->back();
        }
        return view('front.pages.contact');
    }
}
