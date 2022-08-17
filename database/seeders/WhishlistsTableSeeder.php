<?php

namespace Database\Seeders;

use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WhishlistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wishlistRecords = [
            ['id'=>1,'user_id'=>1,'product_id'=>1],
            ['id'=>2,'user_id'=>2,'product_id'=>3]
        ];

        Wishlist::insert($wishlistRecords);
    }
}
