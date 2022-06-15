<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Debit;
use App\Models\Credit;
use App\Models\Balance;
use App\Models\Company;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\SaleItems;
use Illuminate\Http\Request;
use App\Models\CompanySalePaid;
use App\Models\SupplierPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Service\SmsService;
use Barryvdh\DomPDF\Facade as PDF;

class SaleController extends Controller
{
    public function __construct(Request $request){

        $this->middleware('admin');

    }

    public function office_sale_index(Request $request){

        $item=$request->item??20;
        $sales=Sale::orderBy('id','DESC')->where('sale_type',1)->with('company')->paginate($item);
        return response()->json([
            'sales'=>$sales,
            'status'=>"SUCCESS"
        ]);

    }



   public function store(Request $request){
        //  return $request->all();
        DB::transaction( function() use($request){
            //if company sale then sell will be according to company role
             $admin_id=session()->get('admin')['id'];
             $max_id = Sale::max('id') + 1 ;
             $invoice_number = 'S-'.$max_id;
             $balance=Balance::where('department','mohasagor.com')->where('id',$request->paid_by)->first();
             $partial_balance=Balance::where('department','mohasagor.com')->where('name',$request->partials_paid_by)->first();
            if ($request->company_id) {
                $company= Company::where('id',$request->company_id)->first();
                    $sale=new Sale();
                    $sale->sale_type=$request->type;
                    $sale->company_id=$company->id;
                    $sale->paid_by=$balance->name;
                    $sale->name=$company->name;
                    $sale->mobile_no=$company->phone;
                    $sale->address=$company->address;
                    $sale->invoice_no=$invoice_number;
                    $sale->total=$request->AmountTotal;
                    $sale->paid=$request->paid ?? 0;
                    $sale->discount=$request->discount ?? 0;
                    $sale->create_by=$admin_id;
                    $sale->save();
                   //save the sale item
                    foreach ($request->products as $item) {
                        //manage product stock
                        $product = Product::where('id', $item['product_id'])->first();
                        $product->stock = $product->stock - $item['quantity'];
                        $product->save();
                        //save the product stock
                        $sale_item = new SaleItems();
                        $sale_item->sale_id = $sale->id;
                        $sale_item->product_id = $item['product_id'];
                        $sale_item->price = $item['price'];
                        $sale_item->qty = $item['quantity'];
                        $sale_item->total=$item['price'] * $item['quantity'];
                        $sale_item->save();
                    }

                    //if paid more than zero taka
                     if($sale->paid > 0){
                            $credit = new Credit();
                            $credit->department = "mohasagor.com";
                            $credit->purpose = "company sale";
                            $credit->amount = $sale->paid - $request->partials_payment_amount ;
                            $credit->credit_in=$balance->id;
                            $credit->comment = 'company sale('.$company->name.'), invoice no  S-'.$sale->id;
                            $credit->date = date('Y-m-d');
                            $credit->insert_admin_id=session()->get('admin')['id'];
                            $credit->save();
                            //inersting partial payment
                            if($request->partials_payment_amount > 0){
                                $credit = new Credit();
                                $credit->department = "mohasagor.com";
                                $credit->purpose = "company sale";
                                $credit->amount = $request->partials_payment_amount;
                                $credit->credit_in=$partial_balance->id;
                                $credit->comment ="company sale, Partials Payment";
                                $credit->date = date('Y-m-d');
                                $credit->insert_admin_id=session()->get('admin')['id'];
                                $credit->save();
                            }
                     }

                      if($sale->paid < $sale->total ){
                                DB::table('customer_dues')->insert([
                                'sale_id'=>$sale->id,
                                'customer_mobile_no'=>$company->phone,
                                'customer_name'=>$company->name,
                                'amount'=>$sale->total - $sale->paid,
                                'created_at'=>Carbon::now(),
                                'memo_no'=>$sale->id
                            ]);
                     }
                SmsService::SendMessageToCompany($company,$sale->total,$sale->invoice_no);
            }else if($request->supplier_id){

                    $supplier= Supplier::where('id',$request->supplier_id)->first();
                    $sale= new Sale();
                    $sale->sale_type=$request->type;
                    $sale->supplier_id=$supplier->id;
                    $sale->paid_by=$balance->name;
                    $sale->name=$supplier->name;
                    $sale->mobile_no=$supplier->phone;
                    $sale->address=$supplier->address;
                    $sale->invoice_no=$invoice_number;
                    $sale->total=$request->AmountTotal;
                    $sale->paid=$request->paid ?? 0;
                    $sale->discount=$request->discount ?? 0;
                    $sale->create_by=$admin_id;
                    $sale->save();
                   //save the sale item
                    foreach ($request->products as $item) {
                        //manage product stock
                        $product = Product::where('id', $item['product_id'])->first();
                        $product->stock = $product->stock - $item['quantity'];
                        $product->save();
                        //save the product stock
                        $sale_item = new SaleItems();
                        $sale_item->sale_id = $sale->id;
                        $sale_item->product_id = $item['product_id'];
                        $sale_item->price = $item['price'];
                        $sale_item->qty = $item['quantity'];
                        $sale_item->total=$item['price'] * $item['quantity'];
                        $sale_item->save();
                    }

                    //if paid more than zero taka
                     if($sale->paid > 0){
                            $credit = new Credit();
                            $credit->purpose = "supplier reverse sell";
                            $credit->amount = $sale->paid ;
                            $credit->credit_in=$balance->id;
                            $credit->comment ='supplier '. $supplier->company_name.'- invoice no  S-'.$sale->id;
                            $credit->date = date('Y-m-d');
                            $credit->insert_admin_id=session()->get('admin')['id'];
                            $credit->save();
                            //bebit insert
                            $debit = new Debit();
                            $debit->purpose = 28;
                            $debit->debit_from=$balance->id;
                            $debit->amount = $sale->paid ;
                            $debit->comment = 'supplier '. $supplier->company_name.' reverse sale, invoice no  S-'.$sale->id;
                            $debit->date = date('Y-m-d');
                            $debit->insert_admin_id=session()->get('admin')['id'];
                            $debit->save();
                     }

                    $supplier_payment=new SupplierPayment();
                    $supplier_payment->supplier_id=$supplier->id;
                    $supplier_payment->amount=$request->AmountTotal;
                    $supplier_payment->date=date('Y-m-d');
                    $supplier_payment->paid_by= 'reverse sale, invoice no  S-'.$sale->id .' and paid by '.$balance->name.' || '.$request->comment ;
                    $supplier_payment->save();
                    SmsService::SendReverseSaleMessageToSupplier($supplier,$sale->invoice_no,$supplier_payment->amount);
            }else{
                //if sale not company sale
                    $sale=new Sale();
                    $sale->sale_type=$request->type;
                    $sale->company_id= null;
                    $sale->paid_by=$balance->name;
                    $sale->name=$request->name ?? null;
                    $sale->mobile_no=$request->mobile_no ?? null;
                    $sale->address=$request->address;
                    $sale->invoice_no=$invoice_number;
                    $sale->total=$request->AmountTotal;
                    $sale->paid=$request->paid ?? 0;
                    $sale->discount=$request->discount ?? 0;
                    $sale->create_by=$admin_id;
                    $sale->save();
                    //save the sale item
                    foreach ($request->products as $item) {
                        //manage product stock
                        $product = Product::where('id', $item['product_id'])->first();
                        $product->stock = $product->stock - $item['quantity'];
                        $product->save();
                        //save the product stock
                        $sale_item = new SaleItems();
                        $sale_item->sale_id = $sale->id;
                        $sale_item->product_id = $item['product_id'];
                        $sale_item->price = $item['price'];
                        $sale_item->qty = $item['quantity'];
                        $sale_item->total=$item['price'] * $item['quantity'];
                        $sale_item->save();
                    }
                        //first search customer new or exists
                        $customer=Customer::where('phone',$request->mobile_no)->first();
                        //if not customer then save, as a new customer
                        if(!$customer){
                            $customer=new Customer();
                            $customer->name=$request->name;
                            $customer->phone=$request->mobile_no;
                            $customer->address=$request->address;
                            $customer->city_id=2;
                            $customer->custome_type=3;        //ofice sale customer
                            $customer->save();
                        }
                        else{
                            $customer->name=$request->name;
                            $customer->address=$request->address;
                            $customer->save();
                        }
                        //send message to customer
                        $amount= $sale->total - $sale->discount;
                        SmsService::SendSaleMessageToCustomer($customer,$amount,$sale->id);
                        //create a credit.......
                            // $comment='office sale. Amount BDT '.$sale->total.' and paid by '.$sale->paid_by .' Sale created by_'. session()->get('admin')['name'];
                            $credit = new Credit();
                            $credit->purpose = "Office sale";
                            $credit->amount = $sale->paid - $request->partials_payment_amount;
                            $credit->credit_in=$balance->id;
                            $credit->comment ='Office Sale. Invoice No  S-'.$sale->id;
                            $credit->date = date('Y-m-d');
                            $credit->insert_admin_id=session()->get('admin')['id'];
                            $credit->save();
                            if($request->partials_payment_amount > 0){
                                $credit = new Credit();
                                $credit->purpose = "Office sale";
                                $credit->amount = $request->partials_payment_amount;
                                $credit->credit_in=$partial_balance->id;
                                $credit->comment ="Office sale, Partials Payment";
                                $credit->date = date('Y-m-d');
                                $credit->insert_admin_id=session()->get('admin')['id'];
                                $credit->save();
                            }
                            //if customer not paid total amount,then create a customer dues
                            if($sale->paid < $sale->total ){
                                $due= intval($sale->total) - (intval($sale->paid) + intval($sale->discount));
                                if($due>0){
                                    DB::table('customer_dues')->insert([
                                    'sale_id'=>$sale->id,
                                    'customer_mobile_no'=>$request->mobile_no,
                                    'customer_name'=>$request->name,
                                    'amount'=>$due,
                                    'created_at'=>Carbon::now(),
                                    'memo_no'=>$sale->id
                                ]);
                                }
                            }
                        }
           });

            return response()->json([
                'status' => 'SUCCESS',
                'sale_id' => Sale::max('id'),
                'message' => 'new sale  added'
            ]);

       }










   public function companySalePayment(Request $request){
         // return $request->all();
          DB::transaction(function() use($request){
              //finding company
             $balance=Balance::where('department','mohasagor.com')->where('id',$request->credit_in)->first();
              $company=Company::where('id',$request->company_id)->first();
              //inserting data in payment table
              $payment=new CompanySalePaid();
              $payment->date=$request->date ;
              $payment->company_id=$company->id ;
              $payment->amount=$request->amount ;
              $payment->credit_in=$balance->name ;
              $payment->comment=$request->comment ?? null ;
              $payment->save();
              //inserting crdit record
               $credit = new Credit();
               $credit->purpose = $company->name . " paid money";
               $credit->amount =  $request->amount;
               $credit->comment = $request->comment ?? null;
               $credit->date = $request->date;
               $credit->credit_in=$balance->id;
               $credit->insert_admin_id=session()->get('admin')['id'];
               $credit->save();
          });

            return response()->json([
               'status' => 'OK',
               'message' => 'Payment inserted successfully',
            ]);

   }



   public function CompanySaleDuelist(){

       $all_companies=Company::where('status',1)->get();
       $due_companies=[];
       foreach ($all_companies as $key => $company) {
                $paid_from_paid_table= CompanySalePaid::where('company_id',$company->id)->sum('amount');
                $partial_paid = Sale::where('company_id',$company->id)->sum('paid');
                $paid_amount = intval($paid_from_paid_table)  + intval($partial_paid) ;
                $total_purchase = Sale::where('company_id',$company->id)->sum('total');
                $total_discount = Sale::where('company_id',$company->id)->sum('discount');
                $due_amount = intval($total_purchase) - ( intval($paid_amount) + intval($total_discount) ) ;
                if($due_amount > 0){
                  $company->{'due_amount'} = $due_amount ;
                  array_push($due_companies,$company);
                }
       }

       $pdf = PDF::loadView('admin.pdf.company_sale_due',compact('due_companies'));
       return $pdf->stream();
   }



   public function CompanySaleDetails($id){

       $company=Company::findOrFail($id);
       $sales=Sale::where('company_id',$id)->orderBy('id','DESC')->with('saleItems','dueAmount')->paginate(50);
       $paid_from_paid_table= CompanySalePaid::where('company_id',$id)->sum('amount');
       $partial_paid = Sale::where('company_id',$id)->sum('paid');
       $paid_amount = intval($paid_from_paid_table)  + intval($partial_paid) ;
       $paid_records = CompanySalePaid::where('company_id',$id)->orderBy('id','desc')->paginate(20);
       $total_purchase = Sale::where('company_id',$id)->sum('total');
       $total_discount = Sale::where('company_id',$id)->sum('discount');

       return response()->json([
           'sales'=>$sales,
           'status'=>"OK",
           'company'=> $company,
           'paid_amount'=> $paid_amount,
           'paid_records'=> $paid_records,
           'total_purchase'=> $total_purchase,
           'total_discount'=> $total_discount,
       ]);

   }


   public function SupplierSaleDetails($id){

       $sales=Sale::where('supplier_id',$id)->orderBy('created_at','DESC')->get();
       return response()->json([
           'sales'=>$sales,
           'status'=>"OK",
       ]);

   }


   public function companyPayment($id) {

           $company=Company::findOrFail($id);
           $payments=CompanySalePaid::where('company_id',$id)->orderBy('id','DESC')->paginate(50);
           return response()->json([
               'payments'=>$payments,
               'status'=>"OK",
               'company'=> $company,
           ]);

       }




    public function show($id){

        $sale=Sale::where('id',$id)->with('company','create')->first();
        $sale_item=SaleItems::where('sale_id',$sale->id)->with('product')->get();

        return response()->json([
            'sale'=>$sale,
            'items'=>$sale_item,
            'status'=>"SUCCESS"
        ]);
    }




    public function paid($id){

        $sale=Sale::findOrFail($id);
        $sale->status=2;
        if($sale->save()){
                //make comment
                $paid_by='';
                if($sale->paid_by==1){
                    $paid_by='Cash';
                }elseif($sale->paid_by==2){
                    $paid_by="bKash";
                }else{
                    $paid_by="Bank";
                }

                $comment="company sale.created at ".date_format($sale->created_at,'Y-m-d'). " and paid date " . date_format($sale->updated_at,'Y-m-d') . ". Amount BDT ". $sale->total . " and paid by ".$paid_by;
                $credit = new Credit();
                $credit->purpose = "company sale";
                $credit->amount = $sale->total;
                $credit->comment =$comment;
                $credit->date = date('Y-m-d');
                $credit->insert_admin_id=session()->get('admin')['id'];
                $credit->save();

                return response()->json([
                    'success'=>"OK",
                    'message'=>'sale paid was successfully'
                ]);

       }

    }

    public function return($id){
        $sale=Sale::findOrFail($id);
        $sale->status=3;
        $sale->save();

        $sale_itmes=SaleItems::where('sale_id',$id)->get();
      //save the sale item
      foreach ($sale_itmes as $item) {
        //return $item->qty;
        //manage product stock
        $product = Product::where('id', $item->product_id)->first();
        $product->stock = $product->stock + $item->qty;
        $product->save();

        return \response()->json([
            'success'=>"OK",
            'message'=>'sale returned was successfully'
        ]);


         }

    }



    public function  office_sale_search_according_data($search){

        $sale_items=Sale::where('sale_type',1)->get();
        $sales=Sale::where('id', 'like', '%' . $search . '%')
                        ->orWhere('mobile_no','like', '%' . $search . '%')
                        ->orWhere('name','like', '%' . $search . '%')
                        ->where('sale_type',1)
                        ->orderBy('id', 'DESC')
                        ->paginate(10);

        return response()->json([
            'status'=>"OK",
            'sales'=>$sales,
        ]);
    }




    public function  office_sale_search_according_date_wise(Request $request){

        $sales='';
        $paginate=$request->item??10;
        if(!empty($request->start_date) && empty($request->end_date)){

                $sales=Sale::whereDate('created_at','=',$request->start_date)
                             ->where('sale_type',1)
                             ->paginate($paginate);

        }
        elseif(!empty($request->end_date) && !empty($request->start_date)){

                $sales=Sale::whereDate('created_at', '>=', $request->start_date)
                                ->whereDate('created_at', '<=', $request->end_date)
                                ->where('sale_type',1)
                                ->paginate($paginate);
         }else{
              $sales=Sale::whereDate('created_at','=',$request->end_date)
                     ->where('sale_type',1)
                     ->paginate($paginate);

         }
             return response()->json([
                'status'=>'OK',
                'sales'=>$sales
             ]);


    }





    public function company_sale_index(Request $request)
    {
        $item=$request->item??20;

        $sales=Sale::orderBy('id','DESC')->where('sale_type',2)->with('company')->paginate($item);
        return response()->json([
            'sales'=>$sales,
            'status'=>"SUCCESS"
        ]);
    }




    public function  company_sale_search_according_data($search){

        $sales = Sale::where('sale_type',2)->where('name', 'like', '%' . $search . '%')
                        ->orWhere('mobile_no','like', '%' . $search . '%')
                        ->orWhere('address','like', '%' . $search . '%')

                        ->orderBy('id', 'DESC')
                        ->paginate(10);

        return response()->json([
            'status'=>'OK',
            'sales'=>$sales
        ]);
    }




    public function  company_sale_search_according_date_wise(Request $request){

        $sales='';
        $paginate=$request->item??10;
        if(!empty($request->start_date) && empty($request->end_date)){

                $sales=Sale::whereDate('created_at','=',$request->start_date)
                             ->where('sale_type',2)
                             ->paginate($paginate);

        }
        elseif(!empty($request->end_date) && !empty($request->start_date)){

                $sales=Sale::whereDate('created_at', '>=', $request->start_date)
                                ->whereDate('created_at', '<=', $request->end_date)
                                ->where('sale_type',2)
                                ->paginate($paginate);
         }else{

            $sales=Sale::whereDate('created_at','=',$request->end_date)
                     ->where('sale_type',2)
                     ->paginate($paginate);

         }
        return response()->json([
            'status'=>'OK',
            'sales'=>$sales
        ]);


    }

    public function exchangeStore(Request $request){


        //return $request->all();
        if(empty($request->products)){
            return \response()->json('Sale Product Empty');
        }

        if(empty($request->exchnage_products)){
            return \response()->json('exchnage_products Empty');
        }
        if($request->exchange_total>$request->sale_total){
            return \response()->json('Exchaneg amount can not be bigger then Sale amount');
        }
        if($request->AmountTotal < $request->paid){
            return \response()->json('Paid can not be bigger invoice total');

        }

        $sale=new Sale();
        $sale->sale_type=$request->type;
        $sale->paid_by=$request->paid_by;
        $sale->name=$request->name ?? null;
        $sale->mobile_no=$request->mobile_no ?? null;
        $sale->address=$request->address;
        $sale->total=$request->AmountTotal;
        $sale->paid=$request->paid ?? 0;
        $sale->discount=$request->discount ?? 0;
        $sale->status=2;

        if($sale->save()){
            foreach ($request->products as $item) {

            //manage product stock
            $product = Product::where('id', $item['product_id'])->first();
            $product->stock = $product->stock - $item['quantity'];
            $product->save();

            //save the product stock
            $sale_item = new SaleItems();
            $sale_item->sale_id = $sale->id;
            $sale_item->product_id = $item['product_id'];
            $sale_item->price = $item['price'];
            $sale_item->qty = $item['quantity'];
            $sale_item->total=$item['price'] * $item['quantity'];
            $sale_item->save();

         }
         foreach ($request->exchnage_products as $prroduct) {

            //manage product stock
            $pro = Product::where('id', $prroduct['product_id'])->first();
            $pro->stock = $pro->stock + $prroduct['quantity'];
            $pro->save();

            $sale_item = new SaleItems();
            $sale_item->sale_id = $sale->id;
            $sale_item->product_id = $prroduct['product_id'];
            $sale_item->price = $prroduct['price'];
            $sale_item->qty = $prroduct['quantity'];
            $sale_item->total=$prroduct['price'] * $prroduct['quantity'];
            $sale_item->status=2;
            $sale_item->save();

         }

         //new credit
           if($sale->paid>0){
                $credit = new Credit();
                $credit->purpose = "Office sale";
                $credit->amount = $sale->paid;
                $credit->credit_in=$sale->paid_by;
                $credit->comment ='Office Sale | Exchange Sale. Invoice No  S-'.$sale->id;
                $credit->date = date('Y-m-d');
                $credit->insert_admin_id=session()->get('admin')['id'];
                $credit->save();
           }
           if($request->due>0){
                 DB::table('customer_dues')->insert([
                    'sale_id'=>$sale->id,
                    'customer_mobile_no'=>$request->mobile_no,
                    'customer_name'=>$request->name,
                    'amount'=>$request->due,
                    'created_at'=>Carbon::now(),
                    'memo_no'=>$sale->id
                ]);
           }

           return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Exchange Sale  added',
         ]);
        }
    }



}
