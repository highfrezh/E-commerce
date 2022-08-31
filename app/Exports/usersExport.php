<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class usersExport implements WithHeadings,FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // for export the complete table
        // return User::all();

        //for exporting selected columns
        $subscribersData = User::select('id','name','address','city',
        'state','country','pincode','mobile','email','created_at')->where('status', 1)
        ->orderBy('id','Desc')->get();
        return $subscribersData;
    }

    public function headings(): array{
        return ['Id', 'Name', 'Address', 'City',
        'State', 'Country', 'Pincode', 'Mobile', 'Email', 'Registered on'];
    }
}
