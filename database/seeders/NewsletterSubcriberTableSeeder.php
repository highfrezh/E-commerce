<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsletterSubscriber;

class NewsletterSubcriberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subcribersRecords = [
            ['id'=>1, 'email'=>'frezh@yopmail.com','status'=>1],
            ['id'=>2, 'email'=>'top@yopmail.com','status'=>1],
        ];
        NewsletterSubscriber::insert($subcribersRecords );
    }
}
