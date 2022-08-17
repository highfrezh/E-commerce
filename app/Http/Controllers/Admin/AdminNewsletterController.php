<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Session;

class AdminNewsletterController extends Controller
{
    public function newsletterSubscribers()
    {
        Session::put('page','newsletter_subscribers');
        $newsletter_subscribers  = NewsletterSubscriber::get();
        return view('admin.subscribers.newsletter_subscribers')->with(compact('newsletter_subscribers'));
    }

    public function updateSubscriberStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status'] == "Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            NewsletterSubscriber::where('id', $data['subscriber_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'subscriber_id' => $data['subscriber_id']]);
        }
    }

    public function deleteSubscriber($id){
        //delete Subscriber
        NewsletterSubscriber::where('id',$id)->delete();

        $message = "Subscriber has been deleted successfully!";
        Session::flash('success_message', $message);
        return redirect()->back();
    }
}
