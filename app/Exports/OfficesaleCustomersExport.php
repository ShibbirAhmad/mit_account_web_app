<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OfficesaleCustomersExport implements FromCollection  , WithHeadings , ShouldAutoSize 
{
    /**
    * @return \Illuminate\Support\Collection
    */
   
    public function collection()
    {
         $collections = Customer::where('custome_type',3)->orderBy('id','desc')->get();

        foreach($collections as  $item){
             unset($item->id,$item->custome_type , $item->city_id, $item->status,$item->created_at,$item->updated_at );
             $collections[]=$item;
         }

        return $collections;

    }
     
    public function headings():  array
    {
        return self::columnWidths( 'Name','Phone','Address');
    }
 

    public static function  columnWidths($name, $phone, $address): array {

         return [

                 $name,  $phone, $address
         ];
    }


}
