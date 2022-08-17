<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CurrencyController extends Controller
{
    public function currencies()
    {
        Session::put('page','currencies');
        $currencies = Currency::get();
        return view('admin.currencies.currencies')->with(compact('currencies'));
    }

    public function updateCurrencyStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status'] == "Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            Currency::where('id', $data['currency_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'currency_id' => $data['currency_id']]);
        }
    }

    public function addEditCurrency(Request $request, $id=null)
    {
        if ($id=="") {
            $title = "Add Currency";
            $currency = new Currency;
            $message = "Currency added successfully!";
        }else{
            $title = "Edit Currency";
            $currency = Currency::find($id);
            $message = "Currency updated successfully!";
        }

        if ($request->isMethod('post')) {
            $data = $request->all();
            // dd($data);die;
             //Product Validation
            $rules = [
                'currency_code' => 'required|regex:/^[\pL\s\-]+$/u',
                'exchange_rate' => 'required|integer'
            ];

            $customMessages = [ 
                'currency_code.required' => 'Currency Code is required',
                'currency_code.regex' => 'Valid Currency Code is required',
                'exchange_rate.required' => 'Exchange Rate is required',
                'exchange_rate.integer' => 'Valid Exchange Rate is required',                
            ];

            $this->validate($request,$rules,$customMessages);
            
            $currency->currency_code = $data['currency_code'];
            $currency->exchange_rate = $data['exchange_rate'];
            $currency->save();

            Session::flash('success_message',$message);
            return redirect('admin/currencies');
        }
        return view('admin.currencies.add_edit_currency')->with(compact('title','currency'));
    }

    public function deleteCurrency($id){
        //delete attribute
        Currency::where('id',$id)->delete();

        $message = "Currency has been deleted successfully!";
        session::flash('success_message', $message);
        return redirect()->back();
    }
}
