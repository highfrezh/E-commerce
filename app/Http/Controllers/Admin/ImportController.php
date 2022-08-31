<?php

namespace App\Http\Controllers\Admin;

use App\Models\CodPincode;
use Illuminate\Http\Request;
use App\Models\PrepaidPincode;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class ImportController extends Controller
{
    //
    public function updateCODPincodes(Request $request)
    {
        Session::put('page','pincodes');
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data);die;

            //Upload Pincodes Excel CSV to pincodes folder
            if($request->hasFile('file')){
                if($request->file('file')->isValid()){
                    $file = $request->file('file');
                    $destination = public_path('imports/pincodes');
                    $ext = $file->getClientOriginalExtension();
                    $filename = "pincodes-".rand().".".$ext;
                    $file->move($destination,$filename);

                }
            }

            $file = public_path('/imports/pincodes/'.$filename);
            $pincodes = $this->csvToArray($file); // convert data to array
            // echo "<pre>"; print_r($pincodes);die;
            $latestPincodes = array();
            foreach ($pincodes as $key => $pincode) {
                $latestPincodes[$key]['pincodes'] = $pincode['Mobile']; 
                $latestPincodes[$key]['created_at'] = date('Y-m-d H:i:s'); 
                $latestPincodes[$key]['updated_at'] = date('Y-m-d H:i:s');
            }

            DB::table('cod_pincodes')->delete();
            DB::update("Alter Table cod_pincodes AUTO_INCREMENT = 1;");
            CodPincode::insert($latestPincodes);
            $message = "COD Pincodes have been updated Successfully!";
            Session::flash('success_message',$message);
            return redirect()->back();
            
        }
        return view('admin.pincodes.update_cod_pincodes');
    }

    public function updatePrepaidPincodes(Request $request)
    {
        Session::put('page','prepaid');
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data);die;

            //Upload Pincodes Excel CSV to pincodes folder
            if($request->hasFile('file')){
                if($request->file('file')->isValid()){
                    $file = $request->file('file');
                    $destination = public_path('imports/pincodes');
                    $ext = $file->getClientOriginalExtension();
                    $filename = "pincodes-".rand().".".$ext;
                    $file->move($destination,$filename);

                }
            }

            $file = public_path('/imports/pincodes/'.$filename);
            $pincodes = $this->csvToArray($file); // convert data to array
            // echo "<pre>"; print_r($pincodes);die;
            $latestPincodes = array();
            foreach ($pincodes as $key => $pincode) {
                $latestPincodes[$key]['pincodes'] = $pincode['Mobile']; 
                $latestPincodes[$key]['created_at'] = date('Y-m-d H:i:s'); 
                $latestPincodes[$key]['updated_at'] = date('Y-m-d H:i:s');
            }

            DB::table('prepaid_pincodes')->delete();
            DB::update("Alter Table prepaid_pincodes AUTO_INCREMENT = 1;");
            PrepaidPincode::insert($latestPincodes);
            $message = "Prepaid Pincodes have been updated Successfully!";
            Session::flash('success_message',$message);
            return redirect()->back();
            
        }
        return view('admin.pincodes.update_prepaid_pincodes');
    }

    public function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) 
            return false;
            $header = null;
            $data = array();
            if (($handle = fopen($filename, 'r')) !== false){
                while(($row = fgetcsv($handle, 1000, $delimiter)) !== false){
                    if (!$header)
                        $header = $row;
                    else
                    $data[] = array_combine($header, $row);
                }
            fclose($handle);
            }
            return $data;        
    }
}
