<?php

namespace Database\Seeders;

use App\Models\ExchangeRequest;
use Illuminate\Database\Seeder;

class ExchangeRequestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exchangeRequestRecords = [
            ['id' => 1, 'order_id'=>1,'user_id'=>1,'product_size'=>'Small',
            'required_size'=>'Medium','product_code'=>'BT001',
            'exchange_reason'=>'Require Larger Size','exchange_status'=>
            'Pending','comment'=>'']
        ];

        ExchangeRequest::insert($exchangeRequestRecords);
    }
}
