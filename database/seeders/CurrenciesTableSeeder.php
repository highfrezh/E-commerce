<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencyRecords  = [
            ['id' => 1, 'currency_code' => 'USD', 'exchange_rate'=> 75, 'status'=>1],
            ['id' => 2, 'currency_code' => 'GBP', 'exchange_rate'=> 103, 'status'=>1],
            ['id' => 3, 'currency_code' => 'EUR', 'exchange_rate'=> 88, 'status'=>1],
            ['id' => 4, 'currency_code' => 'AUD', 'exchange_rate'=> 55, 'status'=>1],
            ['id' => 5, 'currency_code' => 'CAD', 'exchange_rate'=> 60, 'status'=>1],
            ['id' => 6, 'currency_code' => 'NGN', 'exchange_rate'=> 150, 'status'=>1]
        ];

        Currency::insert($currencyRecords);
    }
}
