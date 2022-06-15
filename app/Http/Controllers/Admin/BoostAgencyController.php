<?php

namespace App\Http\Controllers\Admin;

use App\Models\Debit;
use App\Models\Credit;
use App\Models\Balance;
use App\Models\BoostAgency;
use Illuminate\Http\Request;
use App\Models\BoostAgencyPayment;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Models\BoostAgencyReseller;
use App\Http\Controllers\Controller;
use App\Models\BoostAgencyResellerPayment;
use App\Models\BoostAgencyResellerAdAccount;
use App\Models\BoostAgencyResellerDollarTransaction;
use App\Service\SmsService;

class BoostAgencyController extends Controller
{


   public function agency_list(){
         $agency=BoostAgency::with('resellers.transactions','payments')->get();
         $total_ad_accounts= BoostAgencyResellerAdAccount::count();
            return response()->json([
                   'success' => 'OK',
                   'agency' => $agency,
                   'total_ad_accounts' => $total_ad_accounts,
                ]);
    }


   public function agency_reseller_list(Request $request){

       if (!empty($request->search)) {
         $agency_resellers=BoostAgencyReseller::where('boost_agency_id',$request->id)
                           ->where('status',1)
                           ->where('company_name','like','%'.$request->search.'%')
                           ->with('transactions','payments','accounts')->paginate(30);
            return response()->json([
                   'success' => 'OK',
                   'agency_resellers' => $agency_resellers
                ]);
       }else{

           $agency_resellers=BoostAgencyReseller:: where('boost_agency_id',$request->id)
                                                  ->where('status',$request->status)
                                                  ->with('transactions','payments','accounts')->paginate(30);
            return response()->json([
                   'success' => 'OK',
                   'agency_resellers' => $agency_resellers
                ]);
       }


    }


    public function ResellersList(){


            // $accounts = BoostAgencyResellerAdAccount::get();
            // foreach ($accounts as $item) {
            //       $total_amount = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_ad_account_id',$item->id)->sum('amount');
            //       $total_dollar = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_ad_account_id',$item->id)->sum('dollar');
            //       $item->total_amount= $total_amount;
            //       $item->total_dollar= $total_dollar ;
            //       $item->save();
            // }

           $resellers=BoostAgencyReseller::where('status',1)->get();
            return response()->json([
                   'status' => 'OK',
                   'resellers' => $resellers
                ]);

    }


    public function store(Request $request){

            $validatedData = $request->validate([
            'name' => 'required',
            'rate' => 'required',
            'phone' => 'required|digits:11|unique:boost_agencies',
            'email'=>"required|email|unique:boost_agencies",
          ]);

            $agency= new BoostAgency();
            $agency->name=$request->name;
            $agency->rate=$request->rate;
            $agency->phone=$request->phone;
            $agency->email=$request->email;

            $agency->save();
            return response()->json([
                'status'=>'OK',
                'message'=>'Added successfully'
            ]);
       }



    public function storeBoostReseller(Request $request){

            $validatedData = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'company_name' => 'required',
            'dollar_rate' => 'required',
            'phone' => 'required|digits:11|unique:boost_agency_resellers',
          ]);
            $agency= new BoostAgencyReseller();
            $agency->boost_agency_id=$request->boost_agency_id;
            $agency->name=$request->name;
            $agency->company_name=$request->company_name;
            $agency->phone=$request->phone;
            $agency->address=$request->address;
            $agency->dollar_rate=$request->dollar_rate;
            $agency->save();
            return response()->json([
                'status'=>'OK',
                'message'=>'Added successfully'
            ]);
    }

    public function storeBoostResellerDollar(Request $request){

          $validatedData = $request->validate([
            'boost_agency_reseller_id' => 'required',
            'boost_agency_reseller_ad_account_id' => 'required',
            'amount' => 'required',
            'rate' => 'required',
            'dollar' => 'required',
          ]);
            $transaction= new BoostAgencyResellerDollarTransaction();
            $transaction->boost_agency_reseller_id=$request->boost_agency_reseller_id;
            $transaction->boost_agency_reseller_ad_account_id=$request->boost_agency_reseller_ad_account_id;
            $transaction->dollar=$request->dollar;
            $transaction->rate=$request->rate;
            $transaction->amount=$request->amount;
            if($transaction->save()) {
              $ad_account= BoostAgencyResellerAdAccount::findOrFail($transaction->boost_agency_reseller_ad_account_id);
              $ad_account->total_dollar = intval($ad_account->total_dollar) + intval($request->dollar ) ;
              $ad_account->total_amount = intval($ad_account->total_amount) +  ( intval($request->dollar) * intval($transaction->rate) )   ;
              $ad_account->save();
              //send message to reseller
              SmsService::sendDollarConfirmationMessage($transaction);
               return response()->json([
                'status'=>'OK',
                'message'=>'Added successfully'
                ]);
            }else {
                return response()->json([
                'status'=>'Failed',
                'message'=>'Transaction Failed'
                ]);
            }
    }



    public function storeBoostResellerPayment(Request $request){

              $validatedData = $request->validate([
                'boost_agency_reseller_id' => 'required',
                'amount' => 'required',
                'credit_in' => 'required',
              ]);
              DB::transaction(function() use($request){
                  $balance=Balance::findOrFail($request->credit_in);
                  $transaction= new BoostAgencyResellerPayment();
                  $transaction->boost_agency_reseller_id=$request->boost_agency_reseller_id;
                  $transaction->paid_by=$balance->name;
                  $transaction->comment=$request->comment ?? null;
                  $transaction->amount=$request->amount;
                  $transaction->save();
                  //insert credit
                  $reseller = BoostAgencyReseller::where('id',$request->boost_agency_reseller_id)->first();
                  $credit = new Credit();
                  $credit->purpose = "Boost payment from " .'-'. $reseller->company_name  ;
                  $credit->department = 'boost' ;
                  $credit->amount = $request->amount ;
                  $credit->comment = $request->comment ??  $credit->purpose;
                  $credit->date = date('Y-m-d');
                  $credit->credit_in=$balance->id;
                  $credit->insert_admin_id=session()->get('admin')['id'];
                  $credit->save();
                  //send message to reseller
                  SmsService::sendPaymentMessage($reseller,$request->boost_agency_reseller_id,$request->amount);
              });

                return response()->json([
                    'status'=>'OK',
                    'message'=>'Inserted  successfully'
                ]);
        }




    public function storeAgencyPayment(Request $request){

              $validatedData = $request->validate([
                'boost_agency_id' => 'required',
                'amount' => 'required',
                'paid_by' => 'required',
              ]);
              DB::transaction(function() use($request){
                  $balance=Balance::findOrFail($request->paid_by);
                  $transaction= new BoostAgencyPayment();
                  $transaction->boost_agency_id=$request->boost_agency_id;
                  $transaction->paid_by=$balance->id;
                  $transaction->comment=$request->comment ?? null;
                  $transaction->amount=$request->amount;
                  $transaction->save();
                  //insert debit
                  $debit = new Debit();
                  $debit->purpose = 23;
                  $debit->department = 'boost';
                  $debit->debit_from=$balance->id;
                  $debit->amount = $request->amount;
                  $debit->comment = $request->comment ?? null;
                  $debit->date = date('Y-m-d');
                  $debit->insert_admin_id=session()->get('admin')['id'];
                  $debit->signature=null;
                  $debit->save();

              });

                return response()->json([
                    'status'=>'OK',
                    'message'=>'paid  successfully'
                ]);
   }


    public function boostAgencyPayments($id){
        $boost_agency=BoostAgency::findOrFail($id);
        //payment history
        $payments=BoostAgencyPayment::where('boost_agency_id',$id)->paginate(10);
        $payment_total=BoostAgencyPayment::where('boost_agency_id',$id)->sum('amount');

         return response()->json([
                'boost_agency'=>$boost_agency,
                'payments'=>$payments,
                'payment_total'=>$payment_total,

           ]);

    }


    public function resellerTransactions($id){
        $boost_agency_reseller=BoostAgencyReseller::with('accounts')->findOrFail($id);
        //payment history
        $payments=BoostAgencyResellerPayment::where('boost_agency_reseller_id',$id)->orderBy('id','desc')->paginate(10);
        $payment_total=BoostAgencyResellerPayment::where('boost_agency_reseller_id',$id)->sum('amount');
        //dollar history
        $accounts=BoostAgencyResellerAdAccount::where('boost_agency_reseller_id',$id)->with('transactions')->get();
         return response()->json([
                'boost_agency_reseller'=>$boost_agency_reseller,
                'payments'=>$payments,
                'payment_total'=>$payment_total,
                'accounts'=>$accounts,
           ]);

    }




    public function store_agency_per_month(Request $request){
           // return $request->all();
            $validatedData = $request->validate([
            'amount' => 'required',
            'agency_statement_id' => 'required',
            'month' => 'required',
            'date' => 'required',
          ]);

            $agency=new BoostAgencyPayment();
            $agency->agency_statement_id=$request->agency_statement_id;
            $agency->date=$request->date;
            $agency->month=$request->month;
            $agency->comment=$request->comment ;
            $agency->amount=$request->amount;
            if ($agency->save()) {
               return response()->json([
                'status'=>'OK',
                'message'=>'Added successfully'
                ]);
            }else {
                return response()->json([
                'status'=>'Failed',
                'message'=>'Transaction Failed'
                ]);
            }

       }


        public function updateBoostReseller(Request $request,$id){
            $validatedData = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'dollar_rate' => 'required',
            'company_name' => 'required|unique:boost_agency_resellers,company_name,'.$id,
            'phone' => 'required|digits:11|unique:boost_agency_resellers,phone,'.$id,
          ]);
            $agency= BoostAgencyReseller::findOrFail($id);
            $agency->name=$request->name;
            $agency->company_name=$request->company_name;
            $agency->phone=$request->phone;
            $agency->address=$request->address;
            $agency->dollar_rate=$request->dollar_rate;
            $agency->status=$request->status;
            $agency->save();
            return response()->json([
                'status'=>'OK',
                'message'=>'updated successfully'
            ]);
      }


   public function getBoostReseller($id){
            $reseller = BoostAgencyReseller::findOrFail($id);
            return response()->json([
                'status'=>'OK',
                'reseller'=> $reseller ,
            ]);
    }


    public function boostResellerAccountAdd(Request $request){
            $validatedData = $request->validate([
            'boost_agency_reseller_id' => 'required',
            'name' => 'required|unique:boost_agency_reseller_ad_accounts',
            'page_name' => 'required',
          ]);

            $ad_account= new BoostAgencyResellerAdAccount();
            $ad_account->boost_agency_reseller_id=$request->boost_agency_reseller_id;;
            $ad_account->name=$request->name;
            $ad_account->page_name=$request->page_name;
            if ($ad_account->save()) {
               return response()->json([
                'status'=>'OK',
                'message'=>'Added successfully'
                ]);
            }else {
                return response()->json([
                'status'=>'Failed',
                'message'=>'Failed'
                ]);
            }

       }








    public function DollarTransfer(Request $request){

           $validatedData = $request->validate([
              'boost_agency_reseller_id' => 'required',
              'transfer_account_id' => 'required',
              'name' => 'required|unique:boost_agency_reseller_ad_accounts',
              'page_name' => 'required',
              'dollar' => 'required',
            ]);
           DB::transaction(function() use($request){
            $boost_reseller=BoostAgencyReseller::findOrFail($request->boost_agency_reseller_id);
            //firstly crated an account
            $ad_account= new BoostAgencyResellerAdAccount();
            $ad_account->boost_agency_reseller_id=$boost_reseller->id;
            $ad_account->name=$request->name;
            $ad_account->page_name=$request->page_name;
            $ad_account->save();
            //secondly inserted dollar to created account
            $transaction= new BoostAgencyResellerDollarTransaction();
            $transaction->boost_agency_reseller_id=$boost_reseller->id;
            $transaction->boost_agency_reseller_ad_account_id=$ad_account->id;
            $transaction->dollar=$request->dollar;
            $transaction->rate=$boost_reseller->dollar_rate;
            $transaction->amount= intval($request->dollar) * intval($boost_reseller->dollar_rate) ;
            $transaction->save();
            //send message to reseller
            SmsService::sendDollarConfirmationMessage($transaction);
            $ad_account->total_dollar = intval($ad_account->total_dollar) + intval($request->dollar )  ;
            $ad_account->total_amount = intval($ad_account->total_amount) + (intval($request->dollar ) * intval( $transaction->rate))  ;
            $ad_account->save();
            //reduced transfer dollar
            $transfer_account= BoostAgencyResellerAdAccount::findOrFail($request->transfer_account_id);
            $transfer_account->total_dollar = intval($transfer_account->total_dollar) - intval($request->dollar )  ;
            $transfer_account->total_amount = intval($transfer_account->total_amount) - (intval($request->dollar ) * intval( $transaction->rate))  ;
            $transfer_account->save();



          });
               return response()->json([
                'status'=>'OK',
                'message'=>'Added successfully'
                ]);


       }



      public function downloadAccountPdf($id){

            $ad_account= BoostAgencyResellerAdAccount::findOrFail($id);
            $reseller= BoostAgencyReseller::findOrFail($ad_account->id);
            $transactions=BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_ad_account_id',$ad_account->id)->orderBy('created_at','desc')->get();

            $pdf=PDF::loadView('admin.pdf.boost_account',compact(['reseller','ad_account','transactions']));
            return $pdf->download($ad_account->name.'_boost_records.pdf',$pdf);


      }



      // download client wise pdf with account details
      // public function downloadPdfAllReseller($start_date,$end_date,$id){

      //       if (!empty($start_date)  && !empty($end_date) ) {
      //             //firstly finding the boost agency.Then searching it's clients and collecting their account,dollar and payment transaction
      //             $agency=BoostAgency::findOrFail($id);
      //             $resellers=BoostAgencyReseller::where('boost_agency_id',$id)->select('id','boost_agency_id','name','company_name','phone')->get();
      //             foreach ($resellers as $key => $reseller) {

      //                 $reseller->{'payments'}=BoostAgencyResellerPayment::where('boost_agency_reseller_id',$reseller->id)
      //                                                  ->whereDate('created_at','>=',$start_date)
      //                                                  ->whereDate('created_at','<=',$end_date)
      //                                                  ->orderBy('created_at','desc')->select('amount','paid_by','comment')->get();

      //                 $reseller->{'total_payments'}=BoostAgencyResellerPayment::where('boost_agency_reseller_id',$reseller->id)->whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->sum('amount');
      //                 $reseller->{'total_dollar'} = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id',$reseller->id)->whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->sum('dollar');
      //                 $reseller->{'total_amount'} = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id',$reseller->id)->whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->sum('amount');

      //             $accounts=BoostAgencyResellerAdAccount::where('boost_agency_reseller_id',$reseller->id)->select('id','name','page_name')->get();
      //             foreach ($accounts as $account) {
      //                   $account->{'transactions'}=BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_ad_account_id',$account->id)
      //                                                  ->whereDate('created_at','>=',$start_date)
      //                                                  ->whereDate('created_at','<=',$end_date)
      //                                                  ->orderBy('created_at','desc')->select('dollar','rate','amount','created_at')->get();
      //             }
      //             //assigning as object after collecting transaction
      //             $reseller->{'accounts'} = $accounts ;

      //           }
      //             // return $resellers ;
      //             $pdf=PDF::loadView('admin.pdf.boost_agency_all_reseller_transaction',compact(['resellers','agency','end_date','start_date']));
      //             return $pdf->download($agency->name.'_boost_records.pdf',$pdf);

      //       }

      // }



      // download client wise only total report
      public function downloadPdfAllReseller($start_date,$end_date,$id){

            if (!empty($start_date)  && !empty($end_date) ) {
                  //firstly finding the boost agency.Then searching it's clients and collecting their account,dollar and payment transaction
                  $agency=BoostAgency::findOrFail($id);
                  $resellers=BoostAgencyReseller::where('boost_agency_id',$id)->select('id','boost_agency_id','name','company_name','phone')->get();
                  foreach ($resellers as $key => $reseller) {

                      $reseller->{'payments'}=BoostAgencyResellerPayment::where('boost_agency_reseller_id',$reseller->id)
                                                                        ->whereDate('created_at','>=',$start_date)
                                                                        ->whereDate('created_at','<=',$end_date)
                                                                        ->sum('amount');

                      $reseller->{'total_payment'}=BoostAgencyResellerPayment::where('boost_agency_reseller_id',$reseller->id)->whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->sum('amount');
                      $reseller->{'total_dollar'} = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id',$reseller->id)->whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->sum('dollar');
                      $reseller->{'total_amount'} = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id',$reseller->id)->whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->sum('amount');
                      $accounts=BoostAgencyResellerAdAccount::where('boost_agency_reseller_id',$reseller->id)->select('id','name','page_name')->get();

                      foreach ($accounts as $account) {
                          $account->{'transactions'}=BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_ad_account_id',$account->id)
                                                        ->whereDate('created_at','>=',$start_date)
                                                        ->whereDate('created_at','<=',$end_date)
                                                        ->orderBy('created_at','desc')->select('dollar','rate','amount','created_at')->get();
                      }
                      //assigning as object after collecting transaction
                      $reseller->{'accounts'} = $accounts ;

                }
                  $agency->{'total_dollar'} = array_sum(array_column(json_decode(json_encode($resellers),true),'total_dollar'));
                  $agency->{'total_amount'} = array_sum(array_column(json_decode(json_encode($resellers),true),'total_amount'));
                  $agency->{'total_payment'} = array_sum(array_column(json_decode(json_encode($resellers),true),'total_payment'));
                  $pdf=PDF::loadView('admin.pdf.boost_agency_all_reseller_transaction',compact(['resellers','agency','end_date','start_date']));
                  return $pdf->download($agency->name.'_boost_records.pdf',$pdf);

            }

      }


      // download client wise
      public function downloadAccountPdfWithFilter($start_date,$end_date,$id){

            if (!empty($start_date)  && !empty($end_date)  ) {

                  $reseller=BoostAgencyReseller::findOrFail($id);
                  $payments=BoostAgencyResellerPayment::where('boost_agency_reseller_id',$id)
                                                       ->whereDate('created_at','>=',$start_date)
                                                       ->whereDate('created_at','<=',$end_date)
                                                       ->orderBy('created_at','desc')->get();

                  $total_payments=BoostAgencyResellerPayment::where('boost_agency_reseller_id',$id)->whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->sum('amount');
                  $total_dollar = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id',$reseller->id)->whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->sum('dollar');
                  $total_amount = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id',$reseller->id)->whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->sum('amount');

                  $accounts=BoostAgencyResellerAdAccount::where('boost_agency_reseller_id',$id)->get();
                  foreach ($accounts as $account) {
                        $account->{'transactions'}=BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_ad_account_id',$account->id)
                                                       ->whereDate('created_at','>=',$start_date)
                                                       ->whereDate('created_at','<=',$end_date)
                                                       ->orderBy('created_at','desc')->get();
                  }
                  $pdf=PDF::loadView('admin.pdf.all_boost_account',compact(['reseller','accounts','payments','total_payments','total_dollar','total_amount','end_date','start_date']));
                  return $pdf->download($reseller->company_name.'_boost_records.pdf',$pdf);

            }

      }


      //download account wise
      public function downloadAllAccountPdf($id){

                  $reseller=BoostAgencyReseller::findOrFail($id);
                  $payments=BoostAgencyResellerPayment::where('boost_agency_reseller_id',$id)->orderBy('created_at','desc')->get();
                  $total_payments=BoostAgencyResellerPayment::where('boost_agency_reseller_id',$id)->sum('amount');
                  $total_dollar = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id',$reseller->id)->sum('dollar');
                  $total_amount = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id',$reseller->id)->sum('amount');
                  $accounts=BoostAgencyResellerAdAccount::where('boost_agency_reseller_id',$id)->get();
                  foreach ($accounts as $account) {
                        $account->{'transactions'}=BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_ad_account_id',$account->id)->orderBy('created_at','desc')->get();
                  }
                  $pdf=PDF::loadView('admin.pdf.all_boost_account',compact(['reseller','accounts','payments','total_payments','total_dollar','total_amount']));
                  return $pdf->download($reseller->company_name.'_boost_records.pdf',$pdf);
      }





}
