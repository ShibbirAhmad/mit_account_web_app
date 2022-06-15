<?php

namespace App\Http\Controllers\Admin;

use App\Models\Credit;
use App\Models\Balance;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\SupplierNote;
use Illuminate\Http\Request;
use App\Exports\SupplierExport;
use App\Models\SupplierPayment;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\SupplierReversePayment;
use App\Service\SmsService;

class SupplierController extends Controller
{

    public function __construct()
    {
        return $this->middleware('admin');

    }

    public function index(Request $request)
    {
       $item=$request->item??20;
        $suppliers = Supplier::orderBy('id', 'DESC')->where('type',$request->type)->paginate($item);
        $suppliers_id = Supplier::where('type',$request->type)->select('id')->pluck('id');


        foreach($suppliers as $supplier){
            $supplier->{'due'}= (Purchase::where('supplier_id',$supplier->id)->sum('total') + SupplierReversePayment::where('supplier_id',$supplier->id)->sum('amount')  )-  SupplierPayment::where('supplier_id',$supplier->id)->sum('amount');
        }
        $total_due_amount=Purchase::whereIn('supplier_id',$suppliers_id)->sum('total')-SupplierPayment::whereIn('supplier_id',$suppliers_id )->sum('amount');;
        return response()->json([
            'status' => 'SUCCESS',
            'suppliers' => $suppliers,
            'total_due_amout' =>number_format($total_due_amount)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       // return  $request->all();
        $validatedData = $request->validate([
            //'name' => 'required ',
            'company_name' => 'required',
            'address' => 'required',
            'phone' => 'required|unique:suppliers|digits:11',


        ]);
        $supplier = new Supplier();
        $supplier->name = $request->name ?? $request->company_name;
        $supplier->company_name = $request->company_name;
        $supplier->address = $request->address;
        $supplier->phone = $request->phone;
        $supplier->type=$request->type ?? 1;

        if ($supplier->save()) {
            if(!empty($request->opening_balance)){
              //  $invoice_no=Purchase::max('invoice_no') ?? 0;
                $purchase=new Purchase();
                $purchase->total=$request->opening_balance;
                $purchase->supplier_id=$supplier->id;
                $purchase->invoice_no=0000;
                $purchase->paid=0;
                $purchase->status=$supplier->type;
                $purchase->purchase_date= date('Y-m-d');
                $purchase->save();
            }
            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'supplier add successfully'
            ]);
        }
    }





    public function receiveReversePayment(Request $request)
    {
        $data= $request->validate([
            'supplier_id' => 'required|integer',
            'amount' => 'required|integer',
            'balance_id' => 'required|integer',
        ]);

        DB::transaction(function() use($request){

            $supplier=Supplier::findOrFail($request->supplier_id);
            $balance=Balance::findOrFail($request->balance_id);
            $credit = new Credit();
            $credit->purpose = "supplier reverse payment receive";
            $credit->amount = $request->amount ;
            $credit->credit_in=$balance->id;
            $credit->comment = $request->comment ?? 'supplier reverse payment from '. $supplier->company_name ;
            $credit->date = date('Y-m-d');
            $credit->insert_admin_id=session()->get('admin')['id'];
            $credit->save();
            //supplier reverse payment record insert
            $payment=new SupplierReversePayment();
            $payment->supplier_id=$supplier->id;
            $payment->amount=$request->amount;
            $payment->date=date('Y-m-d');
            $payment->paid_by=$balance->name;
            $payment->save();
            SmsService::reversePaymentMessageToSupplier($supplier,$request->amount);
        });

        return response()->json([
            'success' => true,
            'message' => 'payment received successfully'
        ]);
    }




    public function search_supplier($data){

        $suppliers = Supplier::where('company_name','like','%'.$data.'%')
                        ->orWhere('name','like','%'.$data.'%')
                        ->orWhere('phone','like','%'.$data.'%')
                        ->orWhere('address','like','%'.$data.'%')
                        ->paginate(10);

            return response()->json([
            "status" => "OK",
            'suppliers' => $suppliers ,
            ]);
    }



    public function edit($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            return response()->json([
                'status' => 'SUCCESS',
                'supplier' => $supplier
            ]);
        }
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required ',
            'company_name' => 'required',
            'address' => 'required',
            'phone' => 'required',


        ]);
        $supplier = Supplier::findOrFail($id);

        if ($supplier) {
            $supplier->name = $request->name;
            $supplier->company_name = $request->company_name;
            $supplier->address = $request->address;
            $supplier->phone = $request->phone;
            if ($supplier->save()) {
                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'supplier update successfully'
                ]);
            }
        }

    }




    public function active($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $supplier->status = 1;
            if ($supplier->save()) {
                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'supplier active successfully'
                ]);
            }
        }
    }

    public function deActive($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $supplier->status = 0;
            if ($supplier->save()) {
                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'supplier de-active successfully'
                ]);
            }
        }
    }

    public function supplierAmountList($id){

         $total_paid_item=SupplierPayment::where('supplier_id',$id)->orderBy('date','DESC')->get();
         $reverse_payments=SupplierReversePayment::where('supplier_id',$id)->orderBy('date','DESC')->get();
         $purchase_items=Purchase::where('supplier_id',$id)->with('purchaseItems')->orderBy('id','desc')->get();
         $supplier_amount=array();
         $supplier_amount['total_purchase_amount']= Purchase::where('supplier_id',$id)->sum('total');
         $supplier_amount['total_paid_amount']=   $total_paid_item->sum('amount');
         $supplier_amount['total_reverse_paid_amount']=   $reverse_payments->sum('amount');
         $supplier=Supplier::where('id',$id)->first();

         return response()->json([
             'amount'=>$supplier_amount,
             'paid_items'=>$total_paid_item,
             'reverse_payments'=>$reverse_payments,
             'supplier'=>$supplier,
             'purchase_items'=>$purchase_items
         ]);
    }

    public function supplierList(){
        $suppliers=Supplier::where('status',1)->get();
            return response()->json($suppliers);
    }


    public function  export_supplier(){
        return Excel::download(new SupplierExport, 'supplier.xlsx');
    }


    public function pdfSupplierPurchase($id){

         $p_items=Purchase::where('supplier_id',$id)->orderBy('id','DESC')->with('purchaseItems')->get();
         $supplier=Supplier::where('id',$id)->first();

         $total_paid_items=SupplierPayment::where('supplier_id',$id)->orderBy('date','DESC')->get();
         $supplier_amount=array();
         $supplier_amount['total_purchase_amount']=intval(Purchase::where('supplier_id',$id)->sum('total'));
         $supplier_amount['total_paid_amount']=intval($total_paid_items->sum('amount'));
         $supplier_amount['total_due_amount']= $supplier_amount['total_purchase_amount']-$supplier_amount['total_paid_amount'];
         $pdf=PDF::loadView('admin.pdf.supplier',compact('p_items','supplier','supplier_amount'));

          return $pdf->stream();
    }


    public function pdfSupplierAmount($id){

         $total_paid_items=SupplierPayment::where('supplier_id',$id)->orderBy('date','DESC')->get();
         $supplier=Supplier::where('id',$id)->first();
         $supplier_amount=array();
         $supplier_amount['total_purchase_amount']=intval(Purchase::where('supplier_id',$id)->sum('total'));
         $supplier_amount['total_paid_amount']=intval($total_paid_items->sum('amount'));
         $supplier_amount['total_due_amount']= $supplier_amount['total_purchase_amount']-$supplier_amount['total_paid_amount'];
         $pdf=PDF::loadView('admin.pdf.supplierPayment',compact('total_paid_items','supplier','supplier_amount'));

         return $pdf->stream();

    }






     public  function note_list($id){

        $notes=SupplierNote::orderBy('id','desc')->where('supplier_id',$id)->paginate(10);
        return response()->json([
            'success' => 'OK',
            'notes' => $notes,
        ]);

    }



    public  function store_note(Request $request,$id)
    {
          $validateData= $request->validate([ 'text' => 'required' ]);
          $note=new SupplierNote();
          $note->supplier_id=$id;
          $note->text=$request->text;
          if ($request->hasFile('attachment')) {
              $file_path=$request->file('attachment')->store('images/supplier_attachment','public');
              $note->attachment=$file_path ;
          }
          $note->save();
          return response()->json([
               'success' => 'OK',
               'message' => 'Noted successfully'
          ]);
    }


     public  function delete_note($id){

        $note=SupplierNote::findOrFail($id);
        $note->delete();
        return response()->json([
            'status' => 'OK',
            'message' => 'Deleted successfully'
        ]);

    }





}
