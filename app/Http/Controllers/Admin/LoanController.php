<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Models\Loan;
use App\Models\Loaner;
use App\Models\LoanPaid;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request)
    {
        $items=$request->item ?? 30;
        $loan=Loaner::orderBy('id','DESC')->paginate($items);
        $total_loan= DB::table('loans')->sum('amount');
        $total_loan_paid= DB::table('loan_paids')->sum('amount');
        $due_amount=$total_loan-$total_loan_paid;

        foreach($loan as $item){

            $item->{'taken_amount'}=Loan::where('loaner_id',$item->id)->sum('amount');
            $item->{'paid_amount'}=LoanPaid::where('loaner_id',$item->id)->sum('amount');

        }
     return \response()->json([

            'success'=>'OK',
            'loan'=>$loan,
            'total_loan' => number_format($total_loan),
            'total_loan_paid' => number_format($total_loan_paid),
            'due_amount' =>number_format($due_amount)

        ]);

    }


    public function storeLoaner(Request $request)
    {

            $data = $request->validate([
                'name' => 'required',
                'mobile_no' => 'required|digits:11|unique:loaners',
                'address' => 'required',
            ]);
  
            $loaner=new Loaner();
            $loaner->name=$request->name;
            $loaner->mobile_no=$request->mobile_no;
            $loaner->address=$request->address;
            $loaner->save();
          
            return response()->json([
                 'success'=> true,
                 'message'=>'added successfully'
            ]);

    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function loaners(){
        $loaners=Loaner::all();
        return \response()->json($loaners);
    }

    public function loanersdetails($id){

        $loans=Loan::where('loaner_id',$id)->orderBy('id','desc')->get();
        $paid=LoanPaid::where('loaner_id',$id)->orderBy('id','desc')->get();
        return \response()->json([
            'loans'=>$loans,
            'paid'=>$paid
        ]);
    }



    
    public function storeLoan(Request $request){
        
        $data= $request->validate([
            'balance_id' => 'required',
            'loaner_id' => 'required',
            'purpose' => 'required',
            'amount' => 'required|min:1'
        ]);
        $loaner=Loaner::findOrFail($data['loaner_id']);

        DB::beginTransaction();
        try {

            $loan=new Loan();
            $loan->loaner_id=$loaner->id;
            $loan->purpose=$data['purpose'];
            $loan->amount=$data['amount'];
            $loan->date=date('Y-m-d');
            $loan->save();
            //store credit
            $credit = new Credit();
            $credit->purpose = "Loan from ". $loaner->name;
            $credit->amount = $data['amount'];
            $credit->comment =$data['purpose'] ?? null;
            $credit->date = date('Y-m-d');
            $credit->credit_in=$data['balance_id'];
            $credit->insert_admin_id=session()->get('admin')['id'];
            $credit->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'added ',
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }   

        
    }








}
