<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use App\Models\Debit;
use App\Models\Credit;
use App\Models\Loaner;
use App\Models\Balance;
use App\Models\Investor;
use App\Models\LoanPaid;
use App\Models\Supplier;
use App\Models\PrintHouse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\DebitExport ;
use App\Models\BillStatement;
use App\Exports\CreditExport ;
use App\Models\EmployeeSalary;
use App\Models\Account_purpose;
use App\Models\SupplierPayment;
use App\Models\InvestmentReturn;
use App\Models\BillPaidStatement;
use App\Models\PrintHousePayment;
use App\Models\InvestorProfitPaid;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Service\SmsService;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel ;

class AccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }


    public function AccountConverter(){

         $all_balances = Balance::all();
         foreach($all_balances as $balance){
            //credit balance name change convert with balance id
             $credits= Credit::where('credit_in',$balance->name)->get();
             foreach($credits as $credit){
                  $credit->credit_in=$balance->id;
                  $credit->save();
             }
             //debit balance name change convert with balance id
             $debits= Debit::where('debit_from',$balance->name)->get();
             foreach($debits as $debit){
                  $debit->debit_from=$balance->id;
                  $debit->save();
             }
         }

    }




    public function GetCredit(Request $request){

            $paginate=$request->item??20;
              if($request->status=='all'){
                    $credits=Credit::where('department',$request->department)->orderBy('id','DESC')->with(['admin:id,name','balance'])->paginate($paginate);
                    return response()->json($credits);
             }
             if($request->status=='search'){
                $credits=Credit::where('department',$request->department)->where('purpose', 'like', '%' . $request->search . '%')
                                ->orWhere('comment', 'like', '%' . $request->search . '%')
                                ->orWhere('amount', 'like', '%' . $request->search . '%')
                                ->orWhere('date', 'like', '%' . $request->search . '%')
                                ->orderBy('id','DESC')->with(['admin:id,name','balance'])
                                ->paginate($paginate);
                return response()->json($credits);
             }
             if($request->status=='filter'){

                  if (!empty($request->start_date) && !empty($request->end_date)){
                        $credits=Credit::where('department',$request->department)->whereDate('date','>=',$request->start_date)
                                        ->whereDate('date','<=',$request->end_date)
                                        ->orderBy('id','DESC')->with(['admin:id,name','balance'])
                                        ->paginate($paginate);

                    if(!empty($request->credit_in)){
                        $credits=Credit::where('department',$request->department)->whereDate('date','>=',$request->start_date)
                                    ->whereDate('date','<=',$request->end_date)
                                    ->where('credit_in',$request->credit_in)
                                    ->orderBy('id','DESC')->with(['admin:id,name','balance'])
                                    ->paginate($paginate);
                        }
                    }else{
                        if(!empty($request->credit_in)){
                            $credits=Credit::where('department',$request->department)
                                        ->where('credit_in',$request->credit_in)
                                        ->orderBy('id','DESC')->with(['admin:id,name','balance'])
                                        ->paginate($paginate);
                            }

                    }

                    

                    return response()->json($credits);
                }

    }




    public function StoreCredit(Request $request){

        //  return $request->all();
        $validatedData = $request->validate([
            'date'=>'required|before:tomorrow',
            'purpose' => 'required',
            'amount' => 'required|numeric',
            'credit_in' => 'required',
            'department' => 'required'
        ]);
        $credit = new Credit();
        $credit->department = $request->department;
        $credit->purpose = $request->purpose;
        $credit->amount = $request->amount;
        $credit->comment = $request->comment ?? null;
        $credit->date = $request->date;
        $credit->credit_in=$request->credit_in;
        $credit->insert_admin_id=session()->get('admin')['id'];
        $credit->save();
        return response()->json([
            'status' => 'SUCCESS',
            'message' => "credit  successfully created",
        ]);
    }


    public function edit_credit($id){

           $credit=Credit::findOrFail($id);
            return response()->json([
                'status' => 'SUCCESS',
                'credit' => $credit ,
            ]);
    }


    public function update_credit(Request $request,$id){

        $validatedData = $request->validate([
            'date'=>'required|before:tomorrow',
            'purpose' => 'required',
            'amount' => 'required',
        ]);

        $credit =Credit::find($id);
        $credit->purpose = $request->purpose;
        $credit->amount = $request->amount;
        $credit->comment = $request->comment ?? null;
        $credit->date = $request->date;
        $credit->save();
        return response()->json([
            'status' => 'SUCCESS',
            'message' => "credit  successfully updated",
        ]);

    }


    public function destroy_credit($id)
    {
            $credit=Credit::find($id);
            $credit->delete();
            return response()->json([
                'status' => 'SUCCESS',
                'message' => "credit  successfully deleted",
            ]);
    }





    public function GetDebits(Request $request){

            $paginate=$request->item??20;
             if(!empty($request->accuount_purpose)){
                 $debits=Debit::where('department',$request->department)->where('purpose',$request->accuount_purpose)
                                ->orderBy('id','DESC')->with(['admin:id,name','purpose'])
                                ->paginate($paginate);
                 return response()->json($debits);
             }
            if($request->status=='all'){
                    $debits=Debit::where('department',$request->department)->orderBy('id','DESC')
                           ->with(['purpose','admin:id,name','balance'])->paginate($paginate);
                    return response()->json($debits);
             }
            if($request->status=='search'){
                $debits=Debit::where('department',$request->department)->where('purpose', 'like', '%' . $request->search . '%')
                                ->orWhere('comment', 'like', '%' . $request->search . '%')
                                ->orWhere('amount', 'like', '%' . $request->search . '%')
                                ->orWhere('date', 'like', '%' . $request->search . '%')
                                ->orderBy('id','DESC')->with(['purpose','admin:id,name','balance'])
                                ->paginate($paginate);
                return response()->json($debits);
         }
         if($request->status=='filter'){
            if(!empty($request->start_date) && !empty($request->end_date)){
                
                $debits=Debit::where('department',$request->department)->whereDate('date','>=',$request->start_date)
                                ->whereDate('date','<=',$request->end_date)
                                ->orderBy('id','DESC')->with(['purpose','admin:id,name','balance'])
                                ->paginate($paginate);
            }
            if(!empty($request->debit_from)){
                $debits=Debit::where('department',$request->department)
                            ->where('debit_from',$request->debit_from)
                            ->orderBy('id','DESC')->with(['purpose','admin:id,name','balance'])
                            ->paginate($paginate);
            }
            return response()->json($debits);
         }

    }






    public function StoreDebit(Request $request){

        // return $request->all();
        $data = $request->validate([
          'date'=>'required|before:tomorrow',
          'purpose' => 'required',
          'amount' => 'required',
          'debit_from'=>'required',
          'department'=>'required',
         // 'signature'=>'required'
        ]);

        DB::transaction(function() use($request){
            $balance=Balance::findOrFail($request->debit_from);
            $debit = new Debit();
            $debit->department = $request->department;
            $debit->purpose = $request->purpose;
            $debit->debit_from=$request->debit_from;
            $debit->amount = $request->amount;
            $debit->comment = $request->comment ?? null;
            $debit->date = $request->date;
            $debit->insert_admin_id=session()->get('admin')['id'];
            //make signature image
            if(!empty($request->signature)){
                $name='debit-signature-'.time().'.png';
                Image::make(file_get_contents($request->signature))->save(public_path('storage/images/debitSignature/').$name);
                $debit->signature='images/debitSignature/'.$name;
            }
            if(!empty($request->old_signature)){
            $debit->signature=$request->old_signature;
            }
            $debit->save();
           //employee salary paid
           if(!empty($request->employee_id)){
                $employee=Team::where('id',$request->employee_id)->first();
                $employee_salary=new EmployeeSalary();
                $employee_salary->employee_id=$request->employee_id;
                $employee_salary->amount=$request->amount;
                $employee_salary->comment=$debit->comment;
                $employee_salary->date=$request->date;
                $employee_salary->save();
                //update debit comment
                 $debit->comment = $debit->comment.'('. $employee->name .')';
                 $debit->save();
                 SmsService::sendMessageToEmployee($employee,$employee_salary->amount);
            }
           //loan paid
           if(!empty($request->loaner_id)){
               $loaner=Loaner::where('id',$request->loaner_id)->firstOrFail();
               $loan_paid=new LoanPaid();
               $loan_paid->loaner_id=$loaner->id;
               $loan_paid->amount=  $debit->amount;
               $loan_paid->date= $debit->date;
               $loan_paid->comment=$debit->comment;
               $loan_paid->paid_by=$balance->name;
               $loan_paid->save();
               //debit comment update
               $debit->comment = 'loan paid to '.$loaner->name;
               $debit->save();
               SmsService::SendMessageToLoaner($loaner,$loan_paid->amount);
            }
            //save a supplier paid amount
            if(!empty($request->supplier_id)){
                $supplier=Supplier::where('id',$request->supplier_id)->first();
                $supplier_payment=new SupplierPayment();
                $supplier_payment->supplier_id=$request->supplier_id;
                $supplier_payment->amount=$request->amount;
                $supplier_payment->date=$request->date;
                $supplier_payment->paid_by=$balance->name . '('. $debit->comment.')';
                $supplier_payment->save();
                //update debit comment
                $debit->comment = $debit->comment.'('. $supplier->name .')';
                $debit->save();
                SmsService::SendMessageToSupplier($supplier,$supplier_payment->amount);
            }

            if(!empty($request->investor_id)){
               $investor=Investor::where('id',$request->investor_id)->first();
               $investor_profit_paid=new InvestorProfitPaid();
               $investor_profit_paid->investor_id=$investor->id;
               $investor_profit_paid->amount=  $debit->amount;
               $investor_profit_paid->profit_month= $request->profit_month;
               $investor_profit_paid->date= $debit->date;
               $investor_profit_paid->comment=$debit->comment;
               $investor_profit_paid->paid_by=$balance->name ;
               $investor_profit_paid->save();
               //debit comment update
               $debit->comment = $debit->comment.'('. $investor->name .')';
               $debit->save();
               SmsService::SendMessageToInvestor($investor, $investor_profit_paid->amount, $investor_profit_paid->profit_month);
            }

            //investor payment return
           if(!empty($request->investor_return_id)){
               $investor=Investor::where('id',$request->investor_return_id)->first();
               $invest_return=new InvestmentReturn();
               $invest_return->investor_id=$investor->id;
               $invest_return->amount=  $debit->amount;
               $invest_return->date= $debit->date;
               $invest_return->comment=$debit->comment;
               $invest_return->paid_by=$balance->name ;
               $invest_return->save();
               $debit->comment = $debit->comment.'('. $investor->name .')';
               $debit->save();
           }
            //print house payment
            if(!empty($request->print_house_id)){
               $print_house=PrintHouse::where('id',$request->print_house_id)->first();
               $print_house_paid=new PrintHousePayment();
               $print_house_paid->print_house_id=$print_house->id;
               $print_house_paid->amount=  $debit->amount;
               $print_house_paid->date= $debit->date;
               $print_house_paid->comment=$debit->comment;
               $print_house_paid->paid_by=$balance->name ;
               $print_house_paid->save();
               $debit->comment = $debit->comment.'('.$print_house->name.')';
               $debit->save();
               SmsService::SendMessageToPrintHouse($print_house,$print_house_paid->amount);
          }
           //bill statement paid
           if(!empty($request->bill_statement_id)){
                $bill=BillStatement::where('id',$request->bill_statement_id)->first();
                $bill_paid=new BillPaidStatement();
                $bill_paid->bill_statement_id=$bill->id;
                $bill_paid->amount=  $debit->amount;
                $bill_paid->date= $debit->date;
                $bill_paid->comment=$debit->comment;
                $bill_paid->paid_by=$balance->name ;
                $bill_paid->save();
                //debit comment update
                $debit->comment = $debit->comment.'('.$bill->name.')';
                $debit->save();
            }

        });

            return response()->json([
                'status' => 'SUCCESS',
                'message' => "debit successfully created",
            ]);

    }


    public function edit_debit($id){

            $debit=Debit::findOrFail($id);
             return response()->json([
                 'status' => 'SUCCESS',
                 'debit' => $debit ,
             ]);
    }


    public function update_debit(Request $request,$id){

            $validatedData = $request->validate([
                'date'=>'required|before:tomorrow',
                'purpose' => 'required',
                'amount' => 'required',
            ]);

            $debit =Debit::findOrFail($id);
            $debit->purpose = $request->purpose;
            $debit->amount = $request->amount;
            $debit->comment = $request->comment ?? null;
            $debit->date = $request->date;
            $debit->save();
            return response()->json([
                'status' => 'SUCCESS',
                'message' => "debit  successfully updated",
            ]);
    }


    public function destroy_debit($id)
    {
            $debit=Debit::findOrFail($id);
            $debit->delete();
            return response()->json([
                'status' => 'SUCCESS',
                'message' => "debit  successfully deleted",
            ]);

    }






    public  function get_purpose_list(){

            $purposes = Account_purpose::orderBy('id','DESC')->paginate(10) ;
            return response()->json([
                    "status" => "OK",
                    "purposes" => $purposes ,
            ]);
     }



    public  function add_purpose(Request $request){

        $validatedData = $request->validate([
            'text' => 'required|unique:account_purposes',
        ]);
        $purpose = new Account_purpose() ;
        $purpose->text=$request->text ;
        $purpose->save();
        return response()->json([
                "status" => "OK",
                "message" => "new purpose added" ,
        ]);

    }


    public  function get_edit_purpose($id){

        $purpose = Account_purpose::findOrFail($id);
        return response()->json([
                "status" => "OK",
                "purpose" => $purpose ,
        ]);
    }



    public function update_purpose(Request $request, $id){

        $validatedData = $request->validate([
                'text' => 'required|unique:account_purposes,text,'.$id,
            ]);
                $purpose = Account_purpose::find($id) ;
                $purpose->text=$request->text ;
                $purpose->save();
                return response()->json([
                        "status" => "OK",
                        "message" => "purpose edited " ,
                ]);

    }

    public function accountPurpose(){

            $purpose=Account_purpose::all();
            return response()->json($purpose);
    }

    public function employeeList(){
        $employeies=Team::where('status',1)->orderBy('position','ASC')->get();
        return response()->json($employeies);
    }



    public   function export_credit(){

        return   Excel::download(new CreditExport(),'credit.xlsx') ;
    }

    public   function export_debit(){

        return Excel::download(new DebitExport(),'debit.xlsx') ;
    }






  public function monthlyReport(Request $request){

        $credits = Credit::where('purpose', '!=', 'Fund Transfer')
            ->whereDate('date', '<=', $request->end_date)
            ->whereDate('date', '>=', $request->start_date)
            ->get();

        $credit_data = [
            'Order' => 0,
            'Website' => 0,
            'Boost' => 0,
            'Office Sale' => 0,
            'Others' => 0,
        ];
        foreach ($credits as $k => $credit) {

            if (Str::contains($credit->purpose, ['Delievred Order'])) {
                $credit_data['Order'] += $credit->amount;
            } else if (Str::contains($credit->purpose, ['payment of web application'])) {
                $credit_data['Website'] += $credit->amount;
            } else if (Str::contains($credit->purpose, ['Boost payment from'])) {
                $credit_data['Boost'] += $credit->amount;
            } else if (Str::contains($credit->purpose, ['Boost payment from'])) {
                $credit_data['boost'] += $credit->amount;
            } else if (Str::contains($credit->purpose, ['Office sale', 'Office sell'])) {
                $credit_data['Office Sale'] += $credit->amount;
            } else {
                $credit_data['Others'] += $credit->amount;
            }


        }

        $debits = Debit::whereDate('date', '<=', $request->end_date)
            ->whereDate('date', '>=', $request->start_date)
            ->get()
            ->groupBy('purpose');
        $debit_data = [];
        foreach ($debits as $k => $debit) {
            // return $k;
            $purpose = Account_purpose::where('id', $k)->first();
            $debit_data[$purpose->text ?? "others"] = $debit->sum('amount');
        }

        return response()->json(
            [
                'success' => true,
                'debits' => $debit_data,
                'credits' => $credit_data,
            ]
        );
    }




   public function BalanceAllDepartment(){
          $balance=Balance::orderBy('id','desc')->get();
          return response()->json([
                  'success' => 'OK',
                  'balance' => $balance
              ]);
    }


   public function DepartmentWiseBalance($department){
          $balance=Balance::where('department',$department)->where('status',1)->get();
          return response()->json([
                  'success' => 'OK',
                  'balance' => $balance
              ]);
    }



   //balance list of mohasagor.com department
   public function BalanceMohasagor(){
        $balance=Balance::where('status',1)->where('department','mohasagor.com')->get();
        return response()->json([
            'success' => 'OK',
            'balance' => $balance
        ]);
    }


    public function BoostBalance(){
        $balance=Balance::where('status',1)->where('department','boost')->get();
          return response()->json([
                  'success' => 'OK',
                  'balance' => $balance
              ]);
    }

    public function MitBalance(){
        $balance=Balance::where('status',1)->where('department','mit')->get();
          return response()->json([
                  'success' => 'OK',
                  'balance' => $balance
              ]);
    }

    public function TourismBalance(){
        $balance=Balance::where('status',1)->where('department','tourism')->get();
          return response()->json([
                  'success' => 'OK',
                  'balance' => $balance
              ]);
    }

    public function  PropertiesBalance(){
        $balance=Balance::where('status',1)->where('department','properties')->get();
          return response()->json([
                  'success' => 'OK',
                  'balance' => $balance
              ]);
    }


    public function  BalanceStatusChange($id){

            $balance=Balance::findOrFail($id);
            if ($balance->status==1) {
                $balance->status=0;
                $balance->save();
            }else{
                $balance->status=1;
                $balance->save();
            }

            return response()->json([
                'success' => 'OK',
                'message' => 'status changed'
            ]);
    }



    public function StoreBalanceAccount(Request $request){

            $check_balance=Balance::where('department',$request->department)->where('name',$request->name)->first();
            if ($check_balance) {
                return response()->json([
                    'status' => 'taken',
                    'message' => $check_balance->name.' is already taken under '.$check_balance->department.' department',
                ]);
            }else {
                $balance=new Balance();
                $balance->name=$request->name;
                $balance->department=$request->department;
                $balance->save();
                return response()->json([
                    'status'=>'OK',
                    'message'=>'Added successfully'
                ]);
            }

       }







}
