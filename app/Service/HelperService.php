<?php

namespace App\Service ;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Purchase;

class   HelperService{


   public static function uniqueInvoiceMaker($invoice_type){
        // 1 for order Invoice
        // 2 for sale Invoice
        // 3 for purchase Invoice

        $order_invoice_no = 41151 + Order::max('id') ;
        $sale_invoice_no = 4115 + Sale::max('id') ;
        $purchase_invoice_no = 4115 + Purchase::max('id') ;
        //checking in order  table
        if ($invoice_type==1) {
            $isExistInvoice = self::isExist(new Order(),'invoice_no',$order_invoice_no) ;
            return !empty($isExistInvoice) ? self::uniqueInvoiceMaker(1) : $order_invoice_no ;
        }
        //checking in sale  table
        else if ($invoice_type==2) {
            $isExistInvoice = self::isExist(new Sale(),'invoice_no',$sale_invoice_no) ;
            return !empty($isExistInvoice) ? self::uniqueInvoiceMaker(2) : $sale_invoice_no ;
        }
         //checking in sale  table
        else if ($invoice_type==3) {
            $isExistInvoice = self::isExist(new Purchase(),'invoice_no',$purchase_invoice_no) ;
            return !empty($isExistInvoice) ? self::uniqueInvoiceMaker(3) : $purchase_invoice_no ;
        }

        else{

        }

   }



   public static function isExist($model,$column,$invoice_no){

        return  $model->where($column,$invoice_no)->first() ?? null;
   }



 }









?>