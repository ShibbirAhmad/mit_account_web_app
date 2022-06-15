<?php
namespace App\Service;
use App\Models\Debit;
use App\Models\Credit;
use App\Models\DepartmentLoan;
use App\Models\DepartmentLoanNetwork;

class DepartmentLoanTransferService{

    public function debitAmount($request,$balance_from){
        $debit = new Debit();
        $debit->department =$request['department_from'];
        $debit->purpose =29; //department loan purpose
        $debit->debit_from=$balance_from['id'];
        $debit->amount = $request['amount'];
        $debit->comment =  $request['comment'] ? $request['comment'] :  "Loan Given to ". $request['department_to'].'-'.' Department. Amount '. $request['amount'];
        $debit->date = date('Y-m-d');
        $debit->insert_admin_id=session()->get('admin')['id'];
        $debit->save();
    }

    public function creditAmount($request,$balance_to){
        $credit = new Credit();
        $credit->department =$request['department_to'];
        $credit->purpose = "Loan taken from ".$request['department_from']. ' department';
        $credit->amount =$request['amount'];
        $credit->credit_in=$balance_to['id'];
        $credit->comment = $request['comment'] ? $request['comment'] : "Loan taken from ".$request['department_from']. ' department BDT '.$request['amount'];
        $credit->date = date('Y-m-d');
        $credit->insert_admin_id=session()->get('admin')['id'];
        $credit->save();
    }

    public function isNetworkExist($department_from,$department_to){
        // Step 1 : First create a network with department_from with department_to
        // Step 2 : First create a network with department_to with department_from

        // Step 1
        $isFromToAlreadyExists =DepartmentLoanNetwork::query()
        ->where('department_from',$department_from)
        ->where('department_to',$department_to)
        ->first();

        if(empty($isFromToAlreadyExists)){
            DepartmentLoanNetwork::query()->create([
                'department_from'=>$department_from,
                'department_to'=>$department_to
            ]);
        }
        // Step 2
        $isToFromAlreadyExists =DepartmentLoanNetwork::query()
        ->where('department_from',$department_to)
        ->where('department_to',$department_from)
        ->first();

        if(empty($isToFromAlreadyExists)){
            DepartmentLoanNetwork::query()->create([
                'department_from'=>$department_to,
                'department_to'=>$department_from
            ]);
        }
        return true;
    }


    public function departmentLoanGiven($request){
        $department_loan=DepartmentLoan::where('name',$request['department_from'])->first();
        $department_loan->given_amount = intval($department_loan->given_amount) + intval($request['amount']) ;
        $department_loan->save();
        return $department_loan;
    }

    public function departmentLoanTaken($request){
        $department_loan_receive=DepartmentLoan::where('name',$request['department_to'])->first();
        $department_loan_receive->taken_amount = intval($department_loan_receive->taken_amount) + intval($request['amount']) ;
        $department_loan_receive->save();
        return $department_loan_receive;
    }

}