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

        $department=DepartmentLoan::with(['transactions.balanceFrom','transactions.balanceTo','transactions.transferby'])->findOrFail($id);

        $network = DepartmentLoanNetwork::query()->where('department_from',$id)->get();
        $data = [];
        foreach($network as $key=>$loan){
            // paid
            $paid = DepartmentLoanTransaction::query()->where('department_loan_id',$id)
                                            ->where('department_loan_receiver_id',$loan->department_to)
                                            ->sum('amount');

            // Receive
            $receive = DepartmentLoanTransaction::query()->where('department_loan_id',$loan->department_to)
                                                ->where('department_loan_receiver_id',$id)
                                                ->sum('amount');

            $data[$key]['department'] = DepartmentLoan::query()->findOrFail($loan->department_to)->name;
            $data[$key]['paid'] = $paid;
            $data[$key]['receive'] = $receive;
        }

        return response()->json([
             'records'=>$data,
             'status' => 'OK',
             'department' => $department,
             'network'=>$network,
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
            $given = (new DepartmentLoanTransferService())->departmentLoanGiven($request);
            //saved taken amount
            $taken = (new DepartmentLoanTransferService())->departmentLoanTaken($request);
           // store transactin
            $department_loan=DepartmentLoan::where('name',$request->department_from)->first();
            $department_loan_receive=DepartmentLoan::where('name',$request->department_to)->first();
            $transaction= new DepartmentLoanTransaction();
            $transaction->department_loan_id=$department_loan->id ;
            $transaction->department_loan_receiver_id = $department_loan_receive->id;
            $transaction->balance_from=$balance_from->id ;
            $transaction->balance_to=$balance_to->id ;
            $transaction->amount=$request->amount ;
            $transaction->transfer_by=session()->get('admin')['id'] ;
            $transaction->save();
            //storing transaction networks
            $network = (new DepartmentLoanTransferService())->isNetworkExist($department_loan->id,$department_loan_receive->id);

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
            $debit->comment = "Fund Transfer ".$balance_from->name.'-'.$balance_to->name.'. Amount '. $request->amount;
            $debit->date = date('Y-m-d');
            $debit->insert_admin_id=session()->get('admin')['id'];
            $debit->save();
            //create a credit
            $credit = new Credit();
            $credit->department =$request->department;
            $credit->purpose = "Fund Transfer";
            $credit->amount =$request->transfer_amount;
            $credit->credit_in=$request->to;
            $credit->comment ="Fund In ". $balance_from->name.' from '.$balance_to->name.'. Amount '.($request->transfer_amount). '. And Transfer Cost '.($request->amount-$request->transfer_amount);
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
