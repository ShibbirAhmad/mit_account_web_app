<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Models\Debit;
use App\Models\Account_purpose;
use App\Models\BillStatement;
use App\Models\Service;

class ReportController extends Controller
{

     public function __construct()
    {
        $this->middleware('admin');
    }


    

  
  public function  profitReport(Request  $request){
        

    if (!empty($request->start_date)  &&  !empty($request->end_date )) {
          //expense records
          $expense_list = Account_purpose::where('id','!=',27)->select('id','text','is_expense')->get() ;
          foreach ($expense_list as $key => $item) {
              $item->{'amount'} = DB::table('debits')->where('department','mit')->whereDate('created_at','>=',$request->start_date)->whereDate('created_at','<=',$request->end_date)->where('purpose',$item->id)->sum('amount');
          }
          //bill statement details 
          $bills = BillStatement::where('status',1)->select('id','name')->get();
          foreach($bills as $bill){
              $bill->{'amount'} = DB::table('debits')->where('department','mit')->whereDate('created_at','>=',$request->start_date)->whereDate('created_at','<=',$request->end_date)->where('comment','like','%'.$bill->name.'%')->sum('amount');
          };
          //income records
          $income_list = Service::select('id','name','type')->get() ;
          foreach ($income_list as $key => $item) {
              $item->{'amount'} = DB::table('credits')->where('department','mit')->where('service_id',$item->id)->whereDate('created_at','>=',$request->start_date)->whereDate('created_at','<=',$request->end_date)->sum('amount');
          }
         
          $total_income = Credit::where('department','mit')->where('is_fund_transfer',0)->whereDate('created_at','>=',$request->start_date)->whereDate('created_at','<=',$request->end_date)->sum('amount') ;
          $other_expense = Debit::where('department','mit')->where('is_fund_transfer',0)->where('is_expense',0)->whereDate('created_at','>=',$request->start_date)->whereDate('created_at','<=',$request->end_date)->sum('amount') ;
          $actual_expense = Debit::where('department','mit')->where('is_fund_transfer',0)->where('is_expense',1)->whereDate('created_at','>=',$request->start_date)->whereDate('created_at','<=',$request->end_date)->sum('amount') ;


          
      return response()->json([
            'success' => true,
            'expense_list' => $expense_list,
            'bills' => $bills,
            'income_list' => $income_list,
            'total_income' => $total_income,
            'actual_expense' => $actual_expense,
            'other_expense' => $other_expense,
      ]);

           
    }



        
  }





}
