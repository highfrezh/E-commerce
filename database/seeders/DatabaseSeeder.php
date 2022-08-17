<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
    //   $this->call(AdminsTableSeeder::class);        //Added by me for calling seeding
      // $this->call(SectionsTableSeeder::class);        //Added by me for calling seeding
      // $this->call(CategoryTableSeeder::class);        //Added by me for calling seeding
      // $this->call(productsTableSeeder::class);        //Added by me for calling seeding
      // $this->call(ProductsAttributesTableSeeder::class);        //Added by me for calling seeding
      // $this->call(ProductsImagesTableSeeder::class);        //Added by me for calling seeding
      // $this->call(BrandTableSeeder::class);        //Added by me for calling seeding
      // $this->call(BannersTableSeeder::class);        //Added by me for calling seeding
      // $this->call(CouponsTableSeeder::class);        //Added by me for calling seeding
      // $this->call(DeliveryAddressTableSeeder::class);        //Added by me for calling seeding
      // $this->call(OrderStatusTableSeeder::class);        //Added by me for calling seeding
      // $this->call(CmsPagesTableSeeder::class);        //Added by me for calling seeding
      // $this->call(CurrenciesTableSeeder::class);        //Added by me for calling seeding
      // $this->call(RatingsTableSeeder::class);        //Added by me for calling seeding
      // $this->call(WhishlistsTableSeeder::class);        //Added by me for calling seeding
      // $this->call(ReturnRequestTableSeeder::class);        //Added by me for calling seeding
      // $this->call(ExchangeRequestTableSeeder::class);        //Added by me for calling seeding
      $this->call(NewsletterSubcriberTableSeeder::class);        //Added by me for calling seeding
    }
}
