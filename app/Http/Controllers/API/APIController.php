<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class APIController extends Controller
{
    //
    public function get_user()
    {
        $data = User::all();
        return response()->json([
            'status'=> 200,
            'data'=> $data,
        ]);
    }
}
