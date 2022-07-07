<?php

namespace App\Http\Controllers\Admin;


use Throwable;
use App\Models\Debit;
use App\Models\Credit;
use App\Models\Director;
use App\Service\LogTracker;
use App\Service\SmsService;
use Illuminate\Http\Request;
use App\Models\DirectorRefund;
use App\Models\DirectorPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DirectorController extends Controller
{




    public function getDirectors(Request $request){

        if (!empty($request->search)) {
              $director=Director::where('name','like','%'.$request->search.'%')
                                    ->orWhere('phone','like','%'.$request->search.'%')
                                    ->paginate(30);
              return response()->json([
                    'success' => 'OK',
                    'director' => $director
                  ]);
        }else{
           $director=Director::paginate(30);
            return response()->json([
                   'success' => 'OK',
                   'director' => $director,
                   'total_amount' => Director::sum('total_amount'),
                   'total_paid_amount' => Director::sum('total_paid_amount'),
                   'total_refund_amount' => Director::sum('total_refund_amount'),
                ]);
        }
    }





    public function addDirector(Request $request){

        $data = $request->validate([
            'name' => 'required',
            'share_value' => 'required|integer|min:1',
            'share_qty' => 'required|integer|min:1',
            'phone' => 'required|digits:11|unique:directors',
            'email' => 'nullable|email|unique:directors',
            'address'=>"required",
          ]);

        $data['total_amount'] = $data['share_value'] * $data['share_qty'] ;
        Director::query()->create($data);
        return response()->json([
            'status'=>'OK',
            'message'=>'Added successfully'
        ]);
    }




    public function getDirector($id){

            $director = Director::with(['payments.created_by','refunds.created_by'])->findOrFail($id);
            return response()->json([
                'status'=>'OK',
                'director'=> $director ,
            ]);
    }



    public function searchDirector($phone){
            $director = Director::where('phone',$phone)->first();
            return response()->json([
                'status'=>1,
                'director'=> $director ,
            ]);
    }




    public function updateDirector(Request $request,$id){

            $data = $request->validate([
                'share_value' => 'required|integer|min:1',
                'share_qty' => 'required|integer|min:1',
                'name' => 'required',
                'phone' => 'required|digits:11|unique:directors,phone,'.$id,
                'email' => 'nullable|email|unique:directors,email,'.$id,
                'address'=>"required",
            ]);

            $data['total_amount'] = $data['share_value'] * $data['share_qty'] ;
            $director= Director::findOrFail($id);
            $director->update($data);
            return response()->json([
                'status'=>'OK',
                'message'=>'updated successfully'
            ]);
    }




    public function storeDirectorPayment(Request $request){

              $data = $request->validate([
                'director_id' => 'required|integer',
                'trx_id' => 'nullable',
                'comment' => 'nullable',
                'amount' => 'required|integer|min:1',
                'credit_in' => 'required|integer|min:1',
              ]);
              DB::beginTransaction();
              try{

                $director = Director::where('id',$data['director_id'])->first();
                $director->total_paid_amount =  $director->total_paid_amount + $data['amount'] ;
                $director->save();
                //store payment
                $data['insert_admin_id'] = session()->get('admin')['id'] ;
                $data['created_by'] = session()->get('admin')['id'] ;
                DirectorPayment::query()->create($data);
                //store credit
                $data['purpose'] =  "director payment  from ". $director->name  ;
                $data['comment'] = $request->comment ??  "director payment from ". $director->name ;
                $data['date']  = date('Y-m-d');
                Credit::query()->create($data);
                $payable_amount = $director->total_amount -  $director->total_paid_amount ;
                SmsService::directorPaymentConfirmationMessage($director,$data['amount'],$payable_amount,$director->total_amount);
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




    public function refundDirectorPayment(Request $request){

          $data = $request->validate([
            'director_id' => 'required|integer',
            'trx_id' => 'nullable',
            'comment' => 'nullable',
            'amount' => 'required|integer|min:1',
            'debit_from' => 'required|integer|min:1',
          ]);

          DB::beginTransaction();
          try{

            $director = Director::where('id',$data['director_id'])->first();
            $director->total_paid_amount =  $director->total_paid_amount - $data['amount'] ;
            $director->total_refund_amount =  $director->total_refund_amount + $data['amount'] ;
            $director->save();
            //store payment
            $data['created_by'] = session()->get('admin')['id'] ;
            $data['insert_admin_id'] = session()->get('admin')['id'] ;
            DirectorRefund::query()->create($data);
            //store credit
            $data['purpose'] = "director payment  refund  ". $director->name  ;
            $data['comment'] = $request->comment ??  "director payment refund ". $director->name ;
            $data['date']  = date('Y-m-d');
            Debit::query()->create($data);
            $payable_amount = $director->total_amount -  $director->total_paid_amount ;
            SmsService::directorRefundConfirmationMessage($director,$data['amount'],$payable_amount);
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












}
