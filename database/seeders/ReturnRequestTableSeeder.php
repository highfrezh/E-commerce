<?php

namespace Database\Seeders;

use App\Models\ReturnRequest;
use Illuminate\Database\Seeder;

class ReturnRequestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $returnRequestRecords = [
            ['id'=>1,'order_id'=>1,'user_id'=>1,'product_size'=>'Small','product_code'=>'BT001',
            'return_reason'=>'Item arrived too late','return_status'=>'Pending','comment'=>'']
        ];
        ReturnRequest::insert($returnRequestRecords);
    }
}
