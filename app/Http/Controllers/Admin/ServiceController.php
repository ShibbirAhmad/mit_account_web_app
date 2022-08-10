<?php

namespace App\Http\Controllers\Admin;


use App\Models\Credit;
use App\Models\Balance;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\ServiceClient;
use App\Models\MitMonthlyRecord;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ServicePackage;
use App\Models\ServicePackageBill;
use App\Models\ServicePackagePayment;
use App\Service\LogTracker;
use App\Service\SmsService;
use Prophecy\Promise\ThrowPromise;
use Throwable;


class ServiceController extends Controller
{




   public function services(){

         $services=Service::get();
            return response()->json([
                   'services' => $services
            ]);
    }


    public function sendMessageToContractualDueClients(){

           $clients= ServicePackage::where('is_monthly',2)->where('is_paid',0)->where('status',1)->with(['client:id,name,phone','service:id,name'])->get();
           foreach ($clients as  $item) {
               $due_amount = intval($item->amount) - intval($item->paid) ;
               SmsService::sendDueMessageToClient($item->client->phone,$item->client->name,$item->service->name,$due_amount);
           }
    }


    public function sendMessageToMonthlyDueClients(){

           $clients= ServicePackage::where('is_monthly',1)->where('is_paid',0)->where('status',1)->with(['client:id,name,phone','service:id,name'])->get();
           foreach ($clients as  $item) {
               //checking is there  due amount of current month and current year
               $bill= ServicePackageBill::where('service_package_id',$item->id)->where('month',intval(date('m')))->where('year',date('Y'))->where('status',0)->first();
               if (!empty($bill)) {
                   $due_amount = intval($bill->amount) - intval($bill->paid) ;
                   SmsService::sendDueMessageToClient($item->client->phone,$item->client->name,$item->service->name,$due_amount);
               }
            }

    }


    public function serviceAndClients($service_id){

         $service=Service::FindOrFail($service_id);
         $clients=ServicePackage::where('service_id',$service->id)
                                    ->select(DB::raw('SUM(amount) as total_amount,SUM(paid) as total_paid, client_id'))
                                    ->groupBy('client_id')->get()->each(function($item){
                                       $item->{'client_info'} = ServiceClient::where('id',$item->client_id)->select('id','name','company_name','phone')->first();
                  });

         return response()->json([
                'service' => $service,
                'clients' => $clients,
         ]);
    }




    public function addService(Request $request){

          $validatedData = $request->validate([
            'name' => 'required|unique:services',
          ]);

            $service= new Service();
            $service->details=$request->details ?? null;
            $service->name=$request->name;
            if ($request->hasFile('image')) {
                $path=$request->file('image')->store('images/service','public');
                $service->image=$path;
            }
            $service->save();
            return response()->json([
                'status'=>'OK',
                'message'=>'Added successfully'
            ]);
    }



    public function serviceItem($id){

        $service= Service::findOrFail($id);
            return response()->json([
                "service" => $service ,
            ]);

    }




    public function updateService(Request $request,$id){

          $validatedData = $request->validate([
            'name' => 'required|unique:services,name,'.$id,
          ]);

            $service=Service::findOrFail();
            $service->details=$request->details ?? null;
            $service->name=$request->name;
            if ($request->hasFile('image')) {
                $path=$request->file('image')->store('images/service','public');
                $service->image=$path;
            }
            $service->save();
            return response()->json([
                'status'=>'OK',
                'message'=>'updated successfully'
            ]);
    }


    public function activeService($id){

        $service= Service::find($id);
        $service->status=1 ;
        if ($service->save()) {

            return response()->json([
                "success" => "OK",
                "message" => 'This service activated ' ,
            ]);
        }
    }


    public function inactiveService($id){

        $service= Service::find($id);
        $service->status=0 ;
        if ($service->save()) {

            return response()->json([
                "success" => "OK",
                "message" => 'This service de-activated ' ,
            ]);
        }
    }





    public function addClient(Request $request){

        $validatedData = $request->validate([
            'name' => 'required',
            'company_name' => 'required|unique:service_clients',
            'phone' => 'required|digits:11|unique:service_clients',
            'email' => 'required|email|unique:service_clients',
            'address'=>"required",
          ]);

            $client= new ServiceClient();
            $client->name=$request->name;
            $client->company_name=$request->company_name;
            $client->phone=$request->phone;
            $client->email=$request->email;
            $client->address=$request->address;
            $client->save();
            return response()->json([
                'status'=>'OK',
                'message'=>'Added successfully'
            ]);
    }




    public function getClient($id){
            $client = ServiceClient::findOrFail($id);
            return response()->json([
                'status'=>'OK',
                'client'=> $client ,
            ]);
    }



    public function searchClient($phone){
            $client = ServiceClient::where('phone',$phone)->first();
            return response()->json([
                'status'=>1,
                'client'=> $client ,
            ]);
    }




    public function updateClient(Request $request,$id){

            $validatedData = $request->validate([
            'name' => 'required',
            'company_name' => 'required|unique:service_clients,company_name,'.$id,
            'phone' => 'required|digits:11|unique:service_clients,phone,'.$id,
            'email' => 'required|email|unique:service_clients,email,'.$id,
            'address'=>"required",
          ]);

            $client= ServiceClient::findOrFail($id);
            $client->name=$request->name;
            $client->company_name=$request->company_name;
            $client->phone=$request->phone;
            $client->email=$request->email;
            $client->address=$request->address;
            $client->save();
            return response()->json([
                'status'=>'OK',
                'message'=>'updated successfully'
            ]);
    }




   public function getMonthlyRecord($id){

          $monthly_records=MitMonthlyRecord::where('mit_id',$id)->orderBy('id','desc')->paginate(20);
          $monthly_total = MitMonthlyRecord::sum('cost_amount');
              return response()->json([
                    'success' => 'OK',
                    'monthly_records' => $monthly_records,
                    'monthly_total' => $monthly_total,
            ]);
   }



   public function serviceClients(Request $request){

        if (!empty($request->search)) {
              $service_clients=ServiceClient::where('name','like','%'.$request->search.'%')
                                    ->orWhere('phone','like','%'.$request->search.'%')
                                    ->orWhere('company_name','like','%'.$request->search.'%')
                                    ->orderBy('id','desc')
                                    ->paginate(60);
              return response()->json([
                    'success' => 'OK',
                    'service_clients' => $service_clients
                  ]);
        }else{
           $service_clients=ServiceClient::select('*',DB::raw('total_amount - total_paid_amount as due_amount'))->orderBy('due_amount' ,'desc')->paginate(60);
            return response()->json([
                   'success' => 'OK',
                   'service_clients' => $service_clients
                ]);
        }
    }





    public function addClientServicePackage(Request $request){

          $data = $request->validate([
                'client_id' => 'required',
                'service_id' => 'required',
                'credit_in' => 'nullable',
                'amount' => 'required',
                'paid' => 'nullable',
                'note' => 'nullable',
                'partials_paid_by' => 'nullable',
                'partials_payment_amount' => 'nullable',
                'is_monthly' => 'required',
                'start_date' => 'nullable',
                'end_date' => 'nullable',
                'payment_date' => 'nullable',
                'monthly_bill' => 'nullable',
          ]);
           DB::beginTransaction();
           try{
             //if service package is contractual then service will be add,update in just service_packages table and if the service is monthly then record will insert in service package And also insert in service package bill according to service package months.
             //firstly check if the same contractual service is taken.if taken by this client then just increase service amount otherwise, create a  new service record
             $client=ServiceClient::findOrFail($data['client_id']);
             if ($data['is_monthly']==2) {
                //is taken or not
                $service_package = ServicePackage::where('is_monthly',2)->where('service_id',$data['service_id'])->where('client_id',$client->id)->first();
                if (!empty($service_package)) {
                    $service_package->amount = round(floatval($service_package->amount),2) + round(floatval($data['amount']),2);
                    $service_package->paid =  round(floatval($service_package->paid),2) + round(floatval($data['paid']),2);
                    $service_package->save();
                }else{
                    $data['is_paid'] =  $data['amount'] == $data['paid'] ? 1 : 0  ;
                    $data['status'] = 1 ;
                    $data['created_by'] = session()->get('admin')['id'] ;
                    $service_package =  ServicePackage::query()->create($data);
                }

             }else{

                    $data['is_paid'] =  $data['amount'] == $data['paid'] ? 1 : 0  ;
                    $data['status'] = 1 ;
                    $data['created_by'] = session()->get('admin')['id'] ;
                    $service_package = ServicePackage::query()->create($data);
                    //service package bill create according to service months

                    for ($p=1; $p <= $this->dateDiffChecker($data['start_date'],$data['end_date']) ; $p++) {
                         $data['service_package_id']= $service_package->id ;
                         $data['month'] = date('m', strtotime($data['start_date'])) + $p - 1 ;
                         $data['year'] =date('Y') <  date('Y', strtotime($data['end_date']))  ? date('Y') : date('Y', strtotime($data['end_date']))   ;
                         $data['amount'] =  round($service_package->amount / $this->dateDiffChecker($data['start_date'],$data['end_date']),2)  ;
                         //here we are checking if the paid amount is >= of total paid amount then inserting monthly bill and changing it's status as paid.
                         // if the paid amount is less than of loop index then checking how much amount to be insert
                         $data['paid'] =  $data['amount'] * $p  >= $request->paid   ?  $this->paymentInsertChecker($request->paid,$service_package->id)  :  $data['amount'];
                         $data['status'] = $data['amount'] * $p  >= $request->paid   ? 0 : 1;
                         ServicePackageBill::query()->create($data);
                    }

             }


             //increasing client total_money
             $service= Service::findOrFail($data['service_id']);
             $client=ServiceClient::findOrFail($data['client_id']);
             $client->total_amount = round(floatval($client->total_amount),2) + round(floatval($request->amount),2) ;
             $client->total_paid_amount = round(floatval($client->total_paid_amount),2) + round(floatval($request->paid),2) ;
             $client->save();

             //inserting balance in credit
             if ($request->paid > 0) {
                    $balance=Balance::where('department','mit')->where('id',$data['credit_in'])->first();
                    $partial_balance=Balance::where('department','mit')->where('name',$data['partials_paid_by'])->first();
                    $credit = new Credit();
                    $credit->department = 'mit';
                    $credit->purpose = 'service package sale'.$service->name .'('. $client->company_name .')' ;
                    $credit->amount = $request->paid - $request->partials_payment_amount;
                    $credit->credit_in=$balance->id;
                    $credit->comment ='service package sale -'.$service->name  .'('. $client->company_name .')';
                    $credit->date = date('Y-m-d');
                    $credit->insert_admin_id=session()->get('admin')['id'];
                    $credit->save();
                    //checking if partial payment receive
                    if($request->partials_payment_amount > 0){
                        $credit = new Credit();
                        $credit->department = 'mit';
                        $credit->purpose = "service package sale ".$service->name .'('. $client->company_name .')' ;
                        $credit->amount = $request->partials_payment_amount;
                        $credit->credit_in=$partial_balance->id;
                        $credit->comment ="service package sale, Partials Payment -".$service->name .'('. $client->company_name .')' ;
                        $credit->date = date('Y-m-d');
                        $credit->insert_admin_id=session()->get('admin')['id'];
                        $credit->save();
                    }
                    //insert package payment.
                    $s_p_payment =  new ServicePackagePayment();
                    $s_p_payment->client_id =$client->id ;
                    $s_p_payment->service_package_id =$service_package->id ;
                    $s_p_payment->amount =$request->paid ;
                    $s_p_payment->is_regular = 0 ;
                    $s_p_payment->payment_method_id = $balance->id ;
                    $s_p_payment->trx_id = null ;
                    $s_p_payment->comment = "service package sale".$service->name .'('. $client->company_name .')'   ;
                    $s_p_payment->created_by = session()->get('admin')['id'] ;
                    $s_p_payment->save();
             }

             SmsService::sendNewServiceMessage($client,$service->name,$request->amount,$request->paid);
             DB::commit();
             return response()->json([
                'status'=>1,
                'message'=>'Added successfully'
             ]);

           }catch(\Throwable $e){
                 LogTracker::failLog($e,session()->get('admin')['id']) ;
                 DB::rollBack();
                 return response()->json([
                  'status'=>0,
                  'message'=> $e->getMessage(),
                 ]);
           }


    }

    public function paymentInsertChecker($total_paid,$service_package_id){

           $inserted_total = ServicePackageBill::where('service_package_id',$service_package_id)->sum('paid');
           return  floatval($total_paid) - floatval($inserted_total) ;

    }



    public function dateDiffChecker($start_date,$end_date){

            $start = strtotime($start_date);
            $end = strtotime($end_date);
            //difference of year
            $year1 = date('Y', $start);
            $year2 = date('Y', $end);
            //difference of month
            $month1 = date('m', $start);
            $month2 = date('m', $end);

            $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
            return $diff ;

    }





    public function storeClientPayment(Request $request){

              $data = $request->validate([
                'client_id' => 'required',
                'service_package_id' => 'required',
                'trx_id' => 'nullable',
                'comment' => 'nullable',
                'is_regular' => 'required',
                'is_monthly' => 'required',
                'amount' => 'required',
                'credit_in' => 'required',
              ]);
              DB::beginTransaction();
              try{

                  $client = ServiceClient::where('id',$request->client_id)->first();
                  //creating package payment ;
                  $data['created_by'] = session()->get('admin')['id'] ;
                  ServicePackagePayment::query()->create($data);
                  //updating package paid status
                  $package = ServicePackage::where('client_id',$client->id)->where('id',$request->service_package_id)->first();
                  //checking package type, if monthly then  updating  package and bills. Otherwise just updating contractual package
                  if ($package->is_monthly==1) {
                        $package->paid= floatval($package->paid) + floatval($request->amount) ;
                        $package->is_paid=$package->amount == $package->paid ? 1 : 0 ;
                        $package->save();
                        //updating package bills
                        $bills=ServicePackageBill::where('service_package_id',$package->id)->where('status',0)->orderBy('month')->get();
                        $paid_amount_inserted=0 ;
                        for($b=0; $b < count($bills); $b++) {
                            //if parallel due amount is greater than the requested amount then insert full due amount and if not then insert acceptable amount.
                             $due_amount = ( floatval($bills[$b]->amount) - floatval($bills[$b]->paid) ) ;
                             $is_true = (floatval($due_amount) * floatval( $b + 1 )) <= floatval($request->amount) ?  true : false ;
                            if ($is_true==1) {
                                 $paid_amount_inserted += intval($due_amount);
                                 $bills[$b]->paid = floatval($bills[$b]->paid) + floatval($due_amount) ;
                            } else {
                                //if the inserted amount is match/equal with requested amount then loop will exit
                                if ($paid_amount_inserted == $request->amount) {
                                    break;
                                }
                              $insert_able_amount=(floatval($bills[$b]->paid) + floatval($request->amount)) - floatval($paid_amount_inserted) ;
                              $bills[$b]->paid = $insert_able_amount ;
                              $paid_amount_inserted += intval($insert_able_amount) ;
                            }
                            $bills[$b]->status = floatval($bills[$b]->amount) == floatval($bills[$b]->paid) ? 1 : 0 ;
                            $bills[$b]->save();
                        }
                  }else{
                        $package->paid= floatval($package->paid) + floatval($request->amount) ;
                        $package->is_paid=$package->amount == $package->paid ? 1 : 0 ;
                        $package->save();
                  }
                  //updating client paid amount
                  $client->total_paid_amount = floatval($client->total_paid_amount) + floatval($request->amount) ;
                  $client->save();
                  //insert credit
                  $balance=Balance::findOrFail($request->credit_in);
                  $service=Service::findOrFail($package->service_id);
                  $credit = new Credit();
                  $credit->purpose = "payment of ".$service->name." service  from " .'-'. $client->company_name  ;
                  $credit->department = 'mit' ;
                  $credit->amount = $request->amount ;
                  $credit->comment = $request->comment ?? $service->name." service  from " .'-'. $client->company_name ;
                  $credit->date = date('Y-m-d');
                  $credit->credit_in=$balance->id;
                  $credit->insert_admin_id=session()->get('admin')['id'];
                  $credit->save();
                SmsService::servicePaymentConfirmationMessage($client,$package);
                DB::commit();
                return response()->json([
                    'status'=>'OK',
                    'message'=>'Inserted  successfully'
                ]);

              }catch(Throwable $e){

                DB::rollBack();
                LogTracker::failLog($e,session()->get('admin')['id']) ;
                return response()->json([
                    'status'=>0,
                    'message'=>$e->getMessage(),
                ]);

              }


    }





    public function clientServiceAndPayment($client_id){

        $client=ServiceClient::findOrFail($client_id);
        //payment history
        $payments=ServicePackagePayment::where('client_id',$client->id)->orderBy('id','desc')->with('created_by:id,name')->get();
        //dollar history
        $contractual_packages=ServicePackage::where('client_id',$client->id)->where('is_monthly',2)->with('created_by:id,name','service:id,name')->get();
        $monthly_packages=ServicePackage::where('client_id',$client->id)->where('is_monthly',1)->with(['created_by:id,name','service:id,name','bills'])->get();
        $package_amount_total=ServicePackage::where('client_id',$client->id)->sum('amount');
        $payment_total=ServicePackage::where('client_id',$client->id)->sum('paid');

        return response()->json([
            'client'=>$client,
            'payments'=>$payments,
            'payment_total'=>$payment_total,
            'contractual_packages'=>$contractual_packages,
            'monthly_packages'=>$monthly_packages,
            'package_amount_total'=>$package_amount_total,
        ]);


    }






    public function storeMitMonthlyRecord(Request $request){

              $validatedData = $request->validate([
                'mit_id' => 'required',
                'month' => 'required',
                'received_amount' => 'required',
                'cost_amount' => 'required',
              ]);

                  $analysis= new MitMonthlyRecord();
                  $analysis->mit_id=$request->mit_id;
                  $analysis->month=$request->month;
                  $analysis->received_amount=$request->received_amount;
                  $analysis->cost_amount=$request->cost_amount;
                  $analysis->comment=$request->comment ?? null;
                  $analysis->save();

                return response()->json([
                    'status'=>'OK',
                    'message'=>'added  successfully'
                ]);
    }













}
