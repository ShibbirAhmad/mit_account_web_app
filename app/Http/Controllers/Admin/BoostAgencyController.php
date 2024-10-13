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
use Illuminate\Support\Facades\Hash;
use Throwable;

class BoostAgencyController extends Controller
{


  public function agency_list()
  {
    $agency = BoostAgency::get();
    $total_ad_accounts = BoostAgencyResellerAdAccount::count();
    return response()->json([
      'success' => 'OK',
      'agency' => $agency,
      'total_ad_accounts' => $total_ad_accounts,
    ]);
  }


  public function agency_reseller_list(Request $request)
  {


    if (!empty($request->search)) {

      $agency_resellers = BoostAgencyReseller::where('status', $request->status)->where('company_name', 'like', '%' . $request->search . '%')->with('accounts')->get()->each(function ($user) {
        $user->{'total_dollar'} = BoostAgencyResellerAdAccount::where('boost_agency_reseller_id', $user->id)->sum('total_dollar');
        $user->{'total_amount'} = BoostAgencyResellerAdAccount::where('boost_agency_reseller_id', $user->id)->sum('total_amount');
        $user->{'total_paid'} = BoostAgencyResellerPayment::where('boost_agency_reseller_id', $user->id)->sum('amount');
      });
    } else {

      $agency_resellers = BoostAgencyReseller::where('status', $request->status)->with('accounts')->get()->each(function ($user) {
        $user->{'total_dollar'} = BoostAgencyResellerAdAccount::where('boost_agency_reseller_id', $user->id)->sum('total_dollar');
        $user->{'total_amount'} = BoostAgencyResellerAdAccount::where('boost_agency_reseller_id', $user->id)->sum('total_amount');
        $user->{'total_paid'} = BoostAgencyResellerPayment::where('boost_agency_reseller_id', $user->id)->sum('amount');
      });
    }


    $total_dollar = BoostAgencyResellerAdAccount::sum('total_dollar');
    $total_amount = BoostAgencyResellerAdAccount::sum('total_amount');
    $total_paid = BoostAgencyResellerPayment::sum('amount');


    return response()->json([
      'success' => 'OK',
      'agency_resellers' => $agency_resellers,
      'total_dollar' => $total_dollar,
      'total_amount' => $total_amount,
      'total_paid' => $total_paid,
    ]);
  }


  public function ResellersList()
  {

    $resellers = BoostAgencyReseller::where('status', 1)->get();
    return response()->json([
      'status' => 'OK',
      'resellers' => $resellers
    ]);
  }


  public function store(Request $request)
  {

    $data = $request->validate([
      'name' => 'required',
      'rate' => 'required',
      'phone' => 'required|digits:11|unique:boost_agencies',
      'email' => "required|email|unique:boost_agencies",
    ]);

    BoostAgency::query()->create($data);
    return response()->json([
      'status' => 'OK',
      'message' => 'Added successfully'
    ]);
  }



  public function getAgency($id)
  {

    $agency = BoostAgency::findOrFail($id);
    return response()->json([
      'status' => 'OK',
      'agency' => $agency,
      'message' => 'updated successfully'
    ]);
  }




  public function update(Request $request, $id)
  {

    $data = $request->validate([
      'name' => 'required',
      'rate' => 'required',
      'phone' => 'required|digits:11|unique:boost_agencies,phone,' . $id,
    ]);

    $agency = BoostAgency::findOrFail($id);
    $agency->update($data);
    return response()->json([
      'status' => 'OK',
      'message' => 'updated successfully'
    ]);
  }




  public function storeAgencyPayment(Request $request)
  {

    $data = $request->validate([
      'boost_agency_id' => 'required',
      'amount' => 'required',
      'balance_id' => 'required',
    ]);

    DB::transaction(function () use ($request) {
      $balance = Balance::findOrFail($request->balance_id);
      $transaction = new BoostAgencyPayment();
      $transaction->boost_agency_id = $request->boost_agency_id;
      $transaction->paid_by = $balance->id;
      $transaction->comment = $request->comment ?? null;
      $transaction->amount = $request->amount;
      $transaction->save();
      //insert debit
      $debit = new Debit();
      $debit->purpose = 23;
      $debit->department = 'boost';
      $debit->debit_from = $balance->id;
      $debit->amount = $request->amount;
      $debit->comment = $request->comment ?? null;
      $debit->date = date('Y-m-d');
      $debit->insert_admin_id = session()->get('admin')['id'];
      $debit->signature = null;
      $debit->save();
    });

    return response()->json([
      'status' => 'OK',
      'message' => 'paid  successfully'
    ]);
  }




  public function store_agency_per_month(Request $request)
  {

    $data = $request->validate([
      'amount' => 'required',
      'agency_statement_id' => 'required',
      'month' => 'required',
      'date' => 'required',
      'comment' => 'nullable',
    ]);

    BoostAgencyPayment::query()->create($data);
    return response()->json([
      'status' => 'OK',
      'message' => 'Added successfully'
    ]);
  }





  public function storeBoostReseller(Request $request)
  {

    $data = $request->validate([

      'name' => 'required',
      'address' => 'required',
      'company_name' => 'required',
      'password' => 'required|min:6',
      'phone' => 'required|digits:11|unique:boost_agency_resellers',

    ]);

    try {

      $data['password'] = Hash::make($data['password']);
      BoostAgencyReseller::create($data);
      return response()->json([
        'success' => true,
        'message' => 'Added successfully'
      ]);
    } catch (Throwable $th) {
      return response()->json([
        'success' => false,
        'message' => $th->getMessage(),
      ]);
    }
  }





  public function updateBoostReseller(Request $request, $id)
  {

    $data = $request->validate([
      'name' => 'required',
      'address' => 'required',
      'status' => 'required',
      'company_name' => 'required|unique:boost_agency_resellers,company_name,' . $id,
      'phone' => 'required|digits:11|unique:boost_agency_resellers,phone,' . $id,
    ]);

    $client = BoostAgencyReseller::findOrFail($id);
    if (!empty($request->password)) {
      $data['password'] = Hash::make($request->password);
    }
    $client->update($data);

    return response()->json([
      'status' => 'OK',
      'message' => 'updated successfully'
    ]);
  }



  public function storeBoostResellerDollar(Request $request)
  {

    $data = $request->validate([
      'boost_agency_reseller_id' => 'required',
      'boost_agency_reseller_ad_account_id' => 'required',
      'amount' => 'required',
      'rate' => 'required',
      'dollar' => 'required',
      'paid' => 'nullable|integer',
      'credit_in' => 'nullable|integer',
    ]);

    DB::beginTransaction();
    try {

      $client = BoostAgencyReseller::findOrFail($data['boost_agency_reseller_id']);
      $ad_account = BoostAgencyResellerAdAccount::findOrFail($data['boost_agency_reseller_ad_account_id']);
      $agency = BoostAgency::findOrFail($ad_account->boost_agency_id);
      $data['boost_agency_id'] = $agency->id;
      $data['supplier_rate'] = $agency->rate;
      $transaction = BoostAgencyResellerDollarTransaction::create($data);
      //save advertise account record
      $ad_account->total_dollar = intval($ad_account->total_dollar) + intval($transaction->dollar);
      $ad_account->total_amount = intval($ad_account->total_amount) +  (intval($transaction->dollar) * intval($transaction->rate));
      $ad_account->save();
      //insert paid transaction
      if (!empty($data['paid'] && $data['paid'] > 0)) {
        $balance = Balance::findOrFail($data['credit_in']);
        $payment = new BoostAgencyResellerPayment();
        $payment->boost_agency_reseller_id = $client->id;
        $payment->paid_by = $balance->name;
        $payment->comment = 'paid at  ' . date('Y-m-d');;
        $payment->amount = $data['paid'];
        $payment->save();
        //insert credit
        $credit = new Credit();
        $credit->purpose = "Boost payment from " . '-' . $client->company_name;
        $credit->department = 'boost';
        $credit->amount = $data['paid'];
        $credit->comment = 'boost payment paid from ' . $client->company_name;
        $credit->date = date('Y-m-d');
        $credit->credit_in = $balance->id;
        $credit->insert_admin_id = session()->get('admin')['id'];
        $credit->save();
      }

      DB::commit();
      return response()->json([
        'success' => true,
        'message' => 'Added successfully'
      ]);
    } catch (Throwable $th) {
      DB::rollBack();
      return response()->json([
        'success' => false,
        'message' => $th->getMessage(),
      ]);
    }
  }





  public function storeBoostResellerPayment(Request $request)
  {

    $data = $request->validate([
      'boost_agency_reseller_id' => 'required',
      'amount' => 'required',
      'credit_in' => 'required',
    ]);
    DB::transaction(function () use ($request) {
      $balance = Balance::findOrFail($request->credit_in);
      $transaction = new BoostAgencyResellerPayment();
      $transaction->boost_agency_reseller_id = $request->boost_agency_reseller_id;
      $transaction->paid_by = $balance->name;
      $transaction->comment = $request->comment ?? null;
      $transaction->amount = $request->amount;
      $transaction->save();
      //insert credit
      $reseller = BoostAgencyReseller::where('id', $request->boost_agency_reseller_id)->first();
      $credit = new Credit();
      $credit->purpose = "Boost payment from " . '-' . $reseller->company_name;
      $credit->department = 'boost';
      $credit->amount = $request->amount;
      $credit->comment = $request->comment ??  $credit->purpose;
      $credit->date = date('Y-m-d');
      $credit->credit_in = $balance->id;
      $credit->insert_admin_id = session()->get('admin')['id'];
      $credit->save();
      //send message to reseller
      SmsService::sendPaymentMessage($reseller, $request->boost_agency_reseller_id, $request->amount);
    });

    return response()->json([
      'status' => 'OK',
      'message' => 'Inserted  successfully'
    ]);
  }




  public function  changeStatus($id,$status)
  {

    $transaction = BoostAgencyResellerDollarTransaction::orderBy('id', 'desc')->findOrFail($id);
    $transaction->status = $status ;
    $transaction->save();

    return response()->json([
      'status' => true,
      'message' => 'changed',
    ]);


  }






  public function  resellerDollarTransaction(Request $request)
  {
    
    if(!empty($request->status)) {
      $dollar_transactions = BoostAgencyResellerDollarTransaction::where('status',$request->status)->with(['account:id,name'])->orderBy('id', 'desc')->paginate(100);
    }else{
      $dollar_transactions = BoostAgencyResellerDollarTransaction::with(['account:id,name','balance:id,name'])->orderBy('id', 'desc')->paginate(100);
    }
    $total_pending = BoostAgencyResellerDollarTransaction::where('status',0)->count();
    $total_processing = BoostAgencyResellerDollarTransaction::where('status',1)->count();
    $total_completed = BoostAgencyResellerDollarTransaction::where('status',2)->count();
    $total_rejected = BoostAgencyResellerDollarTransaction::where('status',3)->count();

    return response()->json([
      'success' => true,
      'dollar_transactions' => $dollar_transactions,
      'total_pending' => $total_pending,
      'total_processing' => $total_processing,
      'total_completed' => $total_completed,
      'total_rejected' => $total_rejected,
    ]);


  }


  public function boostAgencyDollarAndPaymentDetails(Request $request)
  {

    $id = $request->agency_id;
    $boost_agency = BoostAgency::findOrFail($id);
    if (!empty($request->start_date)  &&  !empty($request->end_date)) {

      $payments = BoostAgencyPayment::whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->where('boost_agency_id', $id)->with('balance:id,name')->orderBy('id', 'desc')->paginate(100);
      $payment_total = BoostAgencyPayment::where('boost_agency_id', $id)->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->sum('amount');
      $dollar_transactions = BoostAgencyResellerDollarTransaction::whereIn('status', [1,2])->where('boost_agency_id', $id)->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->with(['account:id,name'])->orderBy('id', 'desc')->paginate(100);
      $total_dollars = BoostAgencyResellerDollarTransaction::whereIn('status', [1,2])->where('boost_agency_id', $id)->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->sum('dollar');
      $total_dollar_amount = BoostAgencyResellerDollarTransaction::whereIn('status', [1,2])->where('boost_agency_id', $id)->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->sum('amount');
      $supplier_dollar_amount_total = 0;
      $all_dollars = BoostAgencyResellerDollarTransaction::whereIn('status', [1,2])->where('boost_agency_id', $id)->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->get();
      foreach ($all_dollars as $key => $item) {
        $supplier_dollar_amount_total += $item->supplier_rate * $item->dollar;
      }
    } else {

      $payments = BoostAgencyPayment::where('boost_agency_id', $id)->with('balance:id,name')->orderBy('id', 'desc')->paginate(100);
      $payment_total = BoostAgencyPayment::where('boost_agency_id', $id)->sum('amount');
      $dollar_transactions = BoostAgencyResellerDollarTransaction::whereIn('status', [1,2])->where('boost_agency_id', $id)->with(['account:id,name'])->orderBy('id', 'desc')->paginate(100);
      $total_dollars = BoostAgencyResellerDollarTransaction::whereIn('status', [1,2])->where('boost_agency_id', $id)->sum('dollar');
      $total_dollar_amount = BoostAgencyResellerDollarTransaction::whereIn('status', [1,2])->where('boost_agency_id', $id)->sum('amount');
      $supplier_dollar_amount_total = 0;
      $all_dollars = BoostAgencyResellerDollarTransaction::whereIn('status', [1,2])->where('boost_agency_id', $id)->get();
      foreach ($all_dollars as $key => $item) {
        $supplier_dollar_amount_total += $item->supplier_rate * $item->dollar;
      }
    }

    return response()->json([
      'boost_agency' => $boost_agency,
      'payments' => $payments,
      'payment_total' => $payment_total,
      'total_dollars' => $total_dollars,
      'total_dollar_amount' => $total_dollar_amount,
      'supplier_dollar_amount_total' => $supplier_dollar_amount_total,
      'dollar_transactions' => $dollar_transactions,
    ]);
  }


  public function resellerTransactions($id)
  {

    $boost_agency_reseller = BoostAgencyReseller::with('accounts')->findOrFail($id);
    //payment history
    $payments = BoostAgencyResellerPayment::where('boost_agency_reseller_id', $id)->orderBy('id', 'desc')->paginate(100);
    $payment_total = BoostAgencyResellerPayment::where('boost_agency_reseller_id', $id)->sum('amount');
    //dollar history
    $accounts = BoostAgencyResellerAdAccount::where('boost_agency_reseller_id', $id)->with('transactions')->get();
    return response()->json([
      'boost_agency_reseller' => $boost_agency_reseller,
      'payments' => $payments,
      'payment_total' => $payment_total,
      'accounts' => $accounts,
    ]);
  }





  public function getBoostReseller($id)
  {

    $reseller = BoostAgencyReseller::findOrFail($id);
    return response()->json([
      'status' => 'OK',
      'reseller' => $reseller,
    ]);
  }


  public function boostResellerAccountAdd(Request $request)
  {

    $data = $request->validate([
      'boost_agency_id' => 'required',
      'boost_agency_reseller_id' => 'required',
      'name' => 'required|unique:boost_agency_reseller_ad_accounts',
      'page_name' => 'nullable',
      'dollar_rate' => 'required|integer',
      'previous_dollar' => 'nullable|integer',
    ]);
    BoostAgencyResellerAdAccount::create($data);
    return response()->json([
      'success' => true,
      'message' => 'Added successfully'
    ]);
  }








  public function DollarTransfer(Request $request)
  {

    $validatedData = $request->validate([
      'boost_agency_reseller_id' => 'required',
      'transfer_account_id' => 'required',
      'name' => 'required|unique:boost_agency_reseller_ad_accounts',
      'page_name' => 'required',
      'dollar' => 'required',
    ]);
    DB::transaction(function () use ($request) {
      $boost_reseller = BoostAgencyReseller::findOrFail($request->boost_agency_reseller_id);
      //firstly crated an account
      $ad_account = new BoostAgencyResellerAdAccount();
      $ad_account->boost_agency_reseller_id = $boost_reseller->id;
      $ad_account->name = $request->name;
      $ad_account->page_name = $request->page_name;
      $ad_account->save();
      //secondly inserted dollar to created account
      $transaction = new BoostAgencyResellerDollarTransaction();
      $transaction->boost_agency_reseller_id = $boost_reseller->id;
      $transaction->boost_agency_reseller_ad_account_id = $ad_account->id;
      $transaction->dollar = $request->dollar;
      $transaction->rate = $boost_reseller->dollar_rate;
      $transaction->amount = intval($request->dollar) * intval($boost_reseller->dollar_rate);
      $transaction->save();
      //send message to reseller
      SmsService::sendDollarConfirmationMessage($transaction);
      $ad_account->total_dollar = intval($ad_account->total_dollar) + intval($request->dollar);
      $ad_account->total_amount = intval($ad_account->total_amount) + (intval($request->dollar) * intval($transaction->rate));
      $ad_account->save();
      //reduced transfer dollar
      $transfer_account = BoostAgencyResellerAdAccount::findOrFail($request->transfer_account_id);
      $transfer_account->total_dollar = intval($transfer_account->total_dollar) - intval($request->dollar);
      $transfer_account->total_amount = intval($transfer_account->total_amount) - (intval($request->dollar) * intval($transaction->rate));
      $transfer_account->save();
    });
    return response()->json([
      'status' => 'OK',
      'message' => 'Added successfully'
    ]);
  }



  public function downloadAccountPdf($id)
  {

    $ad_account = BoostAgencyResellerAdAccount::findOrFail($id);
    $reseller = BoostAgencyReseller::findOrFail($ad_account->id);
    $transactions = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_ad_account_id', $ad_account->id)->orderBy('created_at', 'desc')->get();

    $pdf = PDF::loadView('admin.pdf.boost_account', compact(['reseller', 'ad_account', 'transactions']));
    return $pdf->download($ad_account->name . '_boost_records.pdf', $pdf);
  }



  // download client wise only total report
  public function downloadPdfAllReseller($start_date, $end_date, $id)
  {

    if (!empty($start_date)  && !empty($end_date)) {
      //firstly finding the boost agency.Then searching it's clients and collecting their account,dollar and payment transaction
      $agency = BoostAgency::findOrFail($id);
      $resellers = BoostAgencyReseller::where('boost_agency_id', $id)->select('id', 'boost_agency_id', 'name', 'company_name', 'phone')->get();
      foreach ($resellers as $key => $reseller) {

        $reseller->{'payments'} = BoostAgencyResellerPayment::where('boost_agency_reseller_id', $reseller->id)
          ->whereDate('created_at', '>=', $start_date)
          ->whereDate('created_at', '<=', $end_date)
          ->sum('amount');

        $reseller->{'total_payment'} = BoostAgencyResellerPayment::where('boost_agency_reseller_id', $reseller->id)->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('amount');
        $reseller->{'total_dollar'} = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id', $reseller->id)->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('dollar');
        $reseller->{'total_amount'} = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id', $reseller->id)->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('amount');
        $accounts = BoostAgencyResellerAdAccount::where('boost_agency_reseller_id', $reseller->id)->select('id', 'name', 'page_name')->get();

        foreach ($accounts as $account) {
          $account->{'transactions'} = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_ad_account_id', $account->id)
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->orderBy('created_at', 'desc')->select('dollar', 'rate', 'amount', 'created_at')->get();
        }
        //assigning as object after collecting transaction
        $reseller->{'accounts'} = $accounts;
      }
      $agency->{'total_dollar'} = array_sum(array_column(json_decode(json_encode($resellers), true), 'total_dollar'));
      $agency->{'total_amount'} = array_sum(array_column(json_decode(json_encode($resellers), true), 'total_amount'));
      $agency->{'total_payment'} = array_sum(array_column(json_decode(json_encode($resellers), true), 'total_payment'));
      $pdf = PDF::loadView('admin.pdf.boost_agency_all_reseller_transaction', compact(['resellers', 'agency', 'end_date', 'start_date']));
      return $pdf->download($agency->name . '_boost_records.pdf', $pdf);
    }
  }


  // download client wise
  public function downloadAccountPdfWithFilter($start_date, $end_date, $id)
  {

    if (!empty($start_date)  && !empty($end_date)) {

      $reseller = BoostAgencyReseller::findOrFail($id);
      $payments = BoostAgencyResellerPayment::where('boost_agency_reseller_id', $id)
        ->whereDate('created_at', '>=', $start_date)
        ->whereDate('created_at', '<=', $end_date)
        ->orderBy('created_at', 'desc')->get();

      $total_payments = BoostAgencyResellerPayment::where('boost_agency_reseller_id', $id)->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('amount');
      $total_dollar = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id', $reseller->id)->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('dollar');
      $total_amount = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id', $reseller->id)->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('amount');

      $accounts = BoostAgencyResellerAdAccount::where('boost_agency_reseller_id', $id)->get();
      foreach ($accounts as $account) {
        $account->{'transactions'} = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_ad_account_id', $account->id)
          ->whereDate('created_at', '>=', $start_date)
          ->whereDate('created_at', '<=', $end_date)
          ->orderBy('created_at', 'desc')->get();
      }
      $pdf = PDF::loadView('admin.pdf.all_boost_account', compact(['reseller', 'accounts', 'payments', 'total_payments', 'total_dollar', 'total_amount', 'end_date', 'start_date']));
      return $pdf->download($reseller->company_name . '_boost_records.pdf', $pdf);
    }
  }


  //download account wise
  public function downloadAllAccountPdf($id)
  {

    $reseller = BoostAgencyReseller::findOrFail($id);
    $payments = BoostAgencyResellerPayment::where('boost_agency_reseller_id', $id)->orderBy('created_at', 'desc')->get();
    $total_payments = BoostAgencyResellerPayment::where('boost_agency_reseller_id', $id)->sum('amount');
    $total_dollar = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id', $reseller->id)->sum('dollar');
    $total_amount = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id', $reseller->id)->sum('amount');
    $accounts = BoostAgencyResellerAdAccount::where('boost_agency_reseller_id', $id)->get();
    foreach ($accounts as $account) {
      $account->{'transactions'} = BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_ad_account_id', $account->id)->orderBy('created_at', 'desc')->get();
    }
    $pdf = PDF::loadView('admin.pdf.all_boost_account', compact(['reseller', 'accounts', 'payments', 'total_payments', 'total_dollar', 'total_amount']));
    return $pdf->download($reseller->company_name . '_boost_records.pdf', $pdf);
  }
}
