<?php

namespace Database\Seeders;

use App\Models\DeliveryAddress;
use Illuminate\Database\Seeder;

class DeliveryAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deliveryRecords = [
            ['id' =>1, 'user_id'=>1,'name'=>'Amit Gupta','address'=>'Test 123','city'=>'Igboho','state'=>'Oyo',
            'country'=>'Nigeria','pincode'=>123456,'mobile'=>07065541614,'status'=>1],
            ['id' =>2, 'user_id'=>1,'name'=>'Amit Gupta','address'=>'ABC- 3333 Road','city'=>'Igboho','state'=>'Oyo',
            'country'=>'Nigeria','pincode'=>123456,'mobile'=>07065541614,'status'=>1]
        ];
        DeliveryAddress::insert($deliveryRecords);
    }
}
