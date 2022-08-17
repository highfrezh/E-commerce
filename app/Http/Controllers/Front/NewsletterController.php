<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;

class NewsletterController extends Controller
{
    public function addSubscriber(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // dd($data);die;
            $subscriberCount = NewsletterSubscriber::where('email',$data['subscriber_email'])->count();
            if ($subscriberCount > 0) {
                return "exists";
            }else{
                //Add Newsletter Subscriber email in newsletter_subscriber table
                $newsletter = new NewsletterSubscriber;
                $newsletter->email = $data['subscriber_email'];
                $newsletter->status = 1;
                $newsletter->save();
                return "inserted"; 
            }
        }
    }
}
