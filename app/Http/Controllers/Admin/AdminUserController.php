<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Exports\usersExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class AdminUserController extends Controller
{
    public function users()
    {
        Session::put('page','users');
        $users = User::get()->toArray();
        // dd($users);die;
        return view('admin.users.users')->with(compact('users'));
    }

    public function updateUserStatus(Request $request )
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status'] == "Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            User::where('id', $data['user_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'user_id' => $data['user_id']]);
        }
    }

    public function viewUsersCharts()
    {
        $current_month_users = User::whereYear('created_at',Carbon::now()->year)
        ->whereMonth('created_at',Carbon::now()->month)->count();
        $before_1_month_users = User::whereYear('created_at',Carbon::now()->year)
        ->whereMonth('created_at',Carbon::now()->subMonth(1))->count();
        $before_2_month_users = User::whereYear('created_at',Carbon::now()->year)
        ->whereMonth('created_at',Carbon::now()->subMonth(2))->count();
        $before_3_month_users = User::whereYear('created_at',Carbon::now()->year)
        ->whereMonth('created_at',Carbon::now()->subMonth(3))->count();
        $usersCount = array($before_1_month_users,$before_2_month_users,$before_1_month_users,$current_month_users);
        // dd($usersCount);die;
        return view('admin.users.view_users_charts')->with(compact('usersCount'));
    }

    public function exportUsers()
    {
        return Excel::download(new usersExport, 'users.csv'); //users.xlsx or user.csv
    }
}
