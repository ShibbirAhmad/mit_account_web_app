<?php

namespace App\Http\Controllers\Admin;

use App\Models\Debit;
use App\Models\Credit;
use App\Models\Balance;
use App\Models\FundTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\DepartmentLoan;
use App\Models\DepartmentLoanNetwork;
use App\Models\DepartmentLoanTransaction;
use App\Service\DepartmentLoanTransferService;

class FundTransferController extends Controller
{


  public function   depatmentLoanList(){

        $departments=DepartmentLoan::all();
        return response()->json([
             'status' => 'OK',
             'departments' => $departments,
        ]);

  }


  public function   depatmentLoanTransactionDetails($id){

        $department=DepartmentLoan::with(['transactions.balanceFrom',
        'transactions.balanceTo',
        'transactions.transferby'])
        ->findOrFail($id);
        return response()->json([
             'status' => 'OK',
             'department' => $department,
        ]);

  }




  public function depatmentLoanTransfer(Request $request){

      //  return $request->all();
        $validatedData = $request->validate([
            'department_from' => 'required ',
            'balance_from' => 'required',
            'department_to' => 'required ',
            'balance_to' => 'required',
            'amount' => 'required',
         ]);
         DB::transaction(function() use($request){

            $balance_from=Balance::where('department',$request->department_from)->where('id',$request->balance_from)->first();
            $balance_to=Balance::where('department',$request->department_to)->where('id',$request->balance_to)->first();

            //create a  debit
            $debit = (new DepartmentLoanTransferService())->debitAmount($request,$balance_from);

            //create a credit
            $credit = (new DepartmentLoanTransferService())->creditAmount($request,$balance_to);

            //store given amount records
            $department_loan = (new DepartmentLoanTransferService())->departmentLoanGiven($request);

            //saved taken amount
            $department_loan_receive = (new DepartmentLoanTransferService())->departmentLoanTaken($request);

            //store transactin
            $transaction= new DepartmentLoanTransaction();
            $transaction->department_loan_id=$department_loan->id ;
            $transaction->balance_from=$balance_from->id ;
            $transaction->balance_to=$balance_to->id ;
            $transaction->amount=$request->amount ;
            $transaction->transfer_by=session()->get('admin')['id'] ;
            $transaction->save();

            // Network create sender to receiver also receiver to sender at a time.
            // isNetworkExist method require two parameter. First is Department from id and another is department to id create a Network
            $network = (new DepartmentLoanTransferService())->isNetworkExist($department_loan->id,$department_loan_receive->id);
            //storing transaction networks

        });

        return response()->json([
            'status'=>"OK",
            'message'=>'Transaction  Successfull'
        ]);

    }






    public function BalanceTransfer(Request $request){
        $item=$request->items ?? 10;
        $transfer_amount_list=FundTransfer::where('department',$request->department)->orderBy('id','DESC')->paginate($item);
        return response()->json([
             'status'=>'OK',
             'transfer_amount_list'=>$transfer_amount_list
        ]);
    }



    public function BalaceTransferStore(Request $request){

      //  return $request->all();
        $validatedData = $request->validate([
            'from' => 'required ',
            'to' => 'required',
            'amount' => 'required',
            'department' => 'required',
         ]);
         DB::transaction(function() use($request){

            $balance_from=Balance::where('department',$request->department)->where('id',$request->from)->first();
            $balance_to=Balance::where('department',$request->department)->where('id',$request->to)->first();
            $found_transfer=new FundTransfer();
            $found_transfer->department=$request->department;
            $found_transfer->from=$balance_from->name;
            $found_transfer->to=$balance_to->name;
            $found_transfer->amount=$request->amount;
            $found_transfer->cost=$request->cost;
            $found_transfer->comment=$request->comment ?? null;
            $found_transfer->creator_admin=session()->get('admin')['name'];
            $found_transfer->save();
            //create a  debit
            $debit = new Debit();
            $debit->department =$request->department;
            $debit->purpose =27;
            $debit->debit_from=$request->from;
            $debit->amount = $request->amount;
            $debit->comment = "Fund Transfer ". $request->from.'-'.$request->to.'. Amount '. $request->amount;
            $debit->date = date('Y-m-d');
            $debit->insert_admin_id=session()->get('admin')['id'];
            $debit->save();
            //create a credit
            $credit = new Credit();
            $credit->department =$request->department;
            $credit->purpose = "Fund Transfer";
            $credit->amount =$request->transfer_amount;
            $credit->credit_in=$request->to;
            $credit->comment ="Fund In ". $request->from.' from '.$request->to.'. Amount '.($request->transfer_amount). '. And Transfer Cost '.($request->amount-$request->transfer_amount);
            $credit->date = date('Y-m-d');
            $credit->insert_admin_id=session()->get('admin')['id'];
            $credit->save();

        });

        return response()->json([
            'status'=>"OK",
            'message'=>'Transfered Successfully'
        ]);

    }





}
