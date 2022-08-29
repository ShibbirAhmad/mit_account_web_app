<?php
namespace App\Service ;
use App\Models\Debit;
use App\Models\Credit;

class AccountService
{


    public static  function  creditStore($purpose,$amount,$balance_id,$comment=null){

            $credit = new Credit();
            $credit->department = 'mit';
            $credit->purpose = $purpose;
            $credit->amount = $amount;
            $credit->comment = $comment ;
            $credit->date = $date ?? date('Y-m-d') ;
            $credit->credit_in=$balance_id;
            $credit->insert_admin_id=session()->get('admin')['id'];
            $credit->save();
            return ;

     }



      public static  function  storeDebit($purpose,$amount,$balance_id,$date=null,$comment=null){

            $debit = new Debit();
            $debit->purpose = $purpose;
            $debit->balance_id=$balance_id;
            $debit->amount = $amount;
            $debit->comment = $comment;
            $debit->date = $date;
            $debit->insert_admin_id=session()->get('admin')['id'];
            $debit->signature=null;
            $debit->save();
            return ;
      }





}
