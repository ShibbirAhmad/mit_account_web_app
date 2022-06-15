<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;

class DueCustomerExport implements FromCollection, WithHeadings , ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $collections = DB::table('customer_dues')
                        ->join('sales', 'customer_dues.sale_id', '=','sales.id')
                        ->where('customer_dues.status',0)
                        ->where('sales.company_id',null)
                        ->where('customer_dues.amount','>',0)
                        ->select('customer_dues.*')
                        ->get();

        foreach ($collections as  $item) {
                unset($item->id,$item->sale_id , $item->status, $item->memo_no,$item->created_at,$item->updated_at );
                $collections[]=$item;
        }
        return $collections ;
    }


   public function headings():  array
    {
        return self::columnWidths( 'Mobile Number','Customer Name','Due Amount');
    }


    public static function  columnWidths($phone, $name, $amount): array {

         return [

                 $phone, $name, $amount
         ];
    }



}
