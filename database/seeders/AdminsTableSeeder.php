<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->delete();
        $adminRecords = [       //Added by me for Seeding input data. 
            [
                'id' => 1, 'name' => 'admin', 'type' => 'admin', 'mobile' => '98000000', 'email' => 'admin@admin.com',
                'password' => '$2y$10$ypRVASljDi9ayAQFNTIxY.xhvaaj1wadEJOoLABpELcGTUaUs5zcC', 'image' => '', 'status' => 1
            ],
            [
                'id' => 2, 'name' => 'jone', 'type' => 'subadmin', 'mobile' => '98000000', 'email' => 'jone@admin.com',
                'password' => '$2y$10$ypRVASljDi9ayAQFNTIxY.xhvaaj1wadEJOoLABpELcGTUaUs5zcC', 'image' => '', 'status' => 1
            ],
            [
                'id' => 3, 'name' => 'steve', 'type' => 'admin', 'mobile' => '98000000', 'email' => 'steve@admin.com',
                'password' => '$2y$10$ypRVASljDi9ayAQFNTIxY.xhvaaj1wadEJOoLABpELcGTUaUs5zcC', 'image' => '', 'status' => 1
            ],
            [
                'id' => 4, 'name' => 'smith', 'type' => 'subadmin', 'mobile' => '98000000', 'email' => 'smith@admin.com',
                'password' => '$2y$10$ypRVASljDi9ayAQFNTIxY.xhvaaj1wadEJOoLABpELcGTUaUs5zcC', 'image' => '', 'status' => 1
            ],
        ];

        DB::table('admins')->insert($adminRecords);
    }
}
