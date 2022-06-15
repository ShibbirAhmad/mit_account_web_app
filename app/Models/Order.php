<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{


 public function customer()
    {
        return $this->belongsTo('App\Models\Customer','customer_id');
    }
    public function createAdmin()
    {
        return $this->belongsTo('App\Models\Admin','create_admin_id');
    }
    public function courier()
    {
        return $this->belongsTo('App\Models\Courier','courier_id');
    }
    public function city()
    {
        return $this->belongsTo('App\Models\City','city_id');
    }
      public function sub_city()
    {
        return $this->belongsTo('App\Models\SubCity','sub_city_id');
    }

    public function orderItem(){
      return  $this->hasMany('App\Models\OrderItem','order_id');


    }

  public function orderPayment(){

      return $this->hasOne('App\Models\OrderPayment','order_id')->where('status',"Processing");
  }

    public function resellerOrderDetails(){
      return $this->hasOne('App\Models\ResellerOrderDetails','order_id');
    }

    public function approvedBy(){
        return $this->belongsTo('App\Models\Admin','approved_admin_id');
    }
    public function orderBarcode(){
        return $this->hasOne('App\Models\OrderBarcode','order_id');
    }
    public function reseller(){
        return $this->belongsTo('App\Models\Reseller','reseller_id');
    }



    public function pendingBy()
    {
        return $this->belongsTo('App\Models\Admin', 'pending_admin_id');
    }
    public function shipmentBy()
    {
        return $this->belongsTo('App\Models\Admin', 'shipment_admin_id');
    }
    public function deliveredBy()
    {
        return $this->belongsTo('App\Models\Admin', 'delivered_admin_id');
    }
    public function returnBy()
    {
        return $this->belongsTo('App\Models\Admin', 'return_admin_id');
    }
    public function cancelBy()
    {
        return $this->belongsTo('App\Models\Admin', 'cancel_admin_id');
    }



    public static function ordersAll($request){

          $paginate=$request->item??10;
          if($request->status!="all"){
                if($request->type=="all"){
                 $orders=Order::orderBy('id','DESC')
                            ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                            ->where('status',$request->status)
                            ->paginate($paginate);
              }else{
                $orders=Order::orderBy('id','DESC')
                             ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                            ->where('status',$request->status)
                            ->where('order_type',$request->type)
                            ->paginate($paginate);
              }
            }else{
               if($request->type=="all"){
                 $orders=Order::orderBy('id','DESC')
                                 ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                                ->paginate($paginate);
              }else{
                $orders=Order::orderBy('id','DESC')->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])->where('order_type',$request->type)->paginate($paginate);
              }
            }
            return \response()->json([
                'status'=>'SUCCESS',
                'orders'=>$orders,
                'order_count'=> self::orderStatusCount(),
                'couriers'=>Courier::where('status',1)->get(),
                'comments'=>Comment::all(),
            ]);
    }

    public static function orderFilterWithStartDate($request){

          $paginate=$request->item??10;
             if($request->status!="all"){
                if($request->type=="all"){

                    if($request->status==4){
                            $orders=Order::orderBy('id','DESC')
                           ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                            ->where('status',$request->status)
                            ->whereDate('shippment_date','=',$request->start_date)
                            ->paginate($paginate);

                    }else{

                   $orders=Order::orderBy('id','DESC')
                           ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                            ->where('status',$request->status)
                            ->whereDate('created_at','=',$request->start_date)
                            ->paginate($paginate);
                    }



              }else{
                $orders=Order::orderBy('id','DESC')
                            ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                            ->where('status',$request->status)
                            ->where('order_type',$request->type)
                            ->whereDate('created_at','=',$request->start_date)
                            ->paginate($paginate);
              }
            }else{

               if($request->type=="all"){
                 $orders=Order::orderBy('id','DESC')
                                 ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                                ->whereDate('created_at','=',$request->start_date)
                                ->paginate($paginate);
              }else{
                $orders=Order::orderBy('id','DESC')->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                               ->where('order_type',$request->type)
                               ->whereDate('created_at','=',$request->start_date)
                               ->paginate($paginate);
              }
            }
             return \response()->json([
                'status'=>'SUCCESS',
                'orders'=>$orders,
                'order_count'=> self::orderStatusCount(),
                'couriers'=>Courier::where('status',1)->get(),
                'comments'=>Comment::all(),
            ]);
    }

    public static function orderFilterWithStartAndEndDate($request){

        $paginate=$request->item??10;
             if($request->status!="all"){
                if($request->type=="all"){

                if($request->status==4){
                        $orders=Order::orderBy('id','DESC')
                                 ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                                ->whereDate('shippment_date','>=',$request->start_date)
                                 ->whereDate('shippment_date','<=',$request->end_date)
                                ->paginate($paginate);

                 }else{

                 $orders=Order::orderBy('id','DESC')
                            ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                            ->where('status',$request->status)
                            ->whereDate('created_at','>=',$request->start_date)
                            ->whereDate('created_at','<=',$request->end_date)
                            ->paginate($paginate);
                 }

              }else{
                $orders=Order::orderBy('id','DESC')
                            ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                            ->where('status',$request->status)
                            ->where('order_type',$request->type)
                            ->whereDate('created_at','>=',$request->start_date)
                            ->whereDate('created_at','<=',$request->end_date)
                            ->paginate($paginate);
              }
            }else{

               if($request->type=="all"){
                 $orders=Order::orderBy('id','DESC')
                                 ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                                ->whereDate('created_at','>=',$request->start_date)
                                 ->whereDate('created_at','<=',$request->end_date)
                                ->paginate($paginate);
              }else{
                $orders=Order::orderBy('id','DESC')->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                               ->where('order_type',$request->type)
                               ->whereDate('created_at','>=',$request->start_date)
                              ->whereDate('created_at','<=',$request->end_date)
                               ->paginate($paginate);
              }
            }
             return \response()->json([
                'status'=>'SUCCESS',
                'orders'=>$orders,
                'order_count'=> self::orderStatusCount(),
                'couriers'=>Courier::where('status',1)->get(),
                'comments'=>Comment::all(),
            ]);
    }




     public static function orderFilterWithCourier($request){


         $paginate=$request->item??10;
        if(!empty($request->start_date) && empty($request->end_date)){

            if($request->status!="all"){


                if($request->status==4){

                   $orders=Order::orderBy('id','DESC')
                        ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                        ->where('status',$request->status)
                        ->whereDate('shippment_date','=',$request->start_date)
                        ->where('courier_id',$request->courier_id)
                        ->paginate($paginate);

                }else{

                   $orders=Order::orderBy('id','DESC')
                        ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                        ->where('status',$request->status)
                        ->whereDate('created_at','=',$request->start_date)
                        ->where('courier_id',$request->courier_id)
                        ->paginate($paginate);
                }




              }else{
               $orders=Order::orderBy('id','DESC')->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                            ->whereDate('created_at','=',$request->start_date)
                            ->where('courier_id',$request->courier_id)
                            ->paginate($paginate);
             }

        }
        elseif(!empty($request->start_date) && !empty($request->end_date)){
              if($request->status!="all"){

                if($request->status==4){
                   $orders=Order::orderBy('id','DESC')
                        ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                        ->where('status',$request->status)
                        ->whereDate('shippment_date','=',$request->start_date)
                        ->whereDate('shippment_date','=',$request->end_date)
                        ->where('courier_id',$request->courier_id)
                        ->paginate($paginate);

                }else {
                $orders=Order::orderBy('id','DESC')
                             ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                            ->where('status',$request->status)
                            ->whereDate('created_at','>=',$request->start_date)
                            ->whereDate('created_at','<=',$request->end_date)
                            ->where('courier_id',$request->courier_id)
                            ->paginate($paginate);

                }

              }else{
               $orders=Order::orderBy('id','DESC')->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                            ->whereDate('created_at','>=',$request->start_date)
                            ->whereDate('created_at','<=',$request->end_date)
                            ->where('courier_id',$request->courier_id)
                            ->paginate($paginate);
             }

        }else{
            if($request->status!="all"){
                $orders=Order::orderBy('id','DESC')
                             ->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                            ->where('status',$request->status)
                            ->where('courier_id',$request->courier_id)
                            ->paginate($paginate);
              }else{
               $orders=Order::orderBy('id','DESC')->with(['customer:id,name,address','createAdmin:id,name','courier','orderItem.product'])
                             ->where('courier_id',$request->courier_id)
                            ->paginate($paginate);
             }
        }
        return \response()->json([

                'status'=>'SUCCESS',
                'orders'=>$orders,
                'order_count'=> self::orderStatusCount(),
                 'couriers'=>Courier::where('status',1)->get(),
                'comments'=>Comment::all(),
            ]);
    }





  public static function adminOrderAnalysis(){
       $admin_id=session()->get('admin')['id'];
       $admin_order=array();
       $admin_order['session_admin']=Order::where('create_admin_id',$admin_id)
                        ->select(DB::raw('DATE(created_at) as created_at'),DB::raw('count(*) as total'))
                        ->groupBy(DB::raw('DATE(created_at)'))
                        ->orderBy('created_at','DESC')
                        ->get();

      $admin_order['today']=Order::whereNotNull('create_admin_id')
                         ->where('created_at', '>=', Carbon::today()->startOfDay())
                         ->where('created_at', '<=', Carbon::today()->endOfDay())
                         ->select('create_admin_id',DB::raw('count(*) as total'),DB::raw('SUM(total) as total_amount') )
                         ->groupBy('create_admin_id')
                         ->orderBy('total','DESC')
                         ->with('createAdmin')
                         ->get();

      $admin_order['yesterday']=Order::whereNotNull('create_admin_id')
                  ->where('created_at', '>=', Carbon::yesterday()->startOfDay())
                  ->where('created_at', '<=', Carbon::yesterday()->endOfDay())
                  ->select('create_admin_id',DB::raw('count(*) as total'),DB::raw('SUM(total) as total_amount'))
                  ->groupBy('create_admin_id')
                  ->orderBy('total','DESC')
                  ->with('createAdmin')
                  ->get();

      $admin_order['this_week']=Order::whereNotNull('create_admin_id')
                  ->where('created_at', '>=', Carbon::today()->subDays('7')->startOfDay())
                  ->where('created_at', '<=', Carbon::today()->endOfDay())
                  ->select('create_admin_id',DB::raw('count(*) as total'),DB::raw('SUM(total) as total_amount'))
                  ->groupBy('create_admin_id')
                  ->orderBy('total','DESC')
                  ->with('createAdmin')
                  ->get();

      $admin_order['this_month']=Order::whereNotNull('create_admin_id')
                  ->where('created_at', '>=', Carbon::today()->subDays('30')->startOfDay())
                  ->where('created_at', '<=', Carbon::today()->endOfDay())
                  ->select('create_admin_id',DB::raw('count(*) as total'),DB::raw('SUM(total) as total_amount'))
                  ->groupBy('create_admin_id')
                  ->orderBy('total','DESC')
                  ->with('createAdmin')
                  ->get();


       return $admin_order;



    }




    public static function topSellingProductToday(){
      $products=OrderItem::where('created_at', '>=', Carbon::today()->startOfDay())
                         ->where('created_at', '<=', Carbon::today()->endOfDay())
                         ->select('product_id',DB::raw('count(*) as total'))
                         ->groupBy('product_id')
                         ->orderBy('total','DESC')
                         ->with('product')
                          ->get();
     return $products;

    }

public static function orderStatusCount(){
      $order=array();
        $order['total']=Order::count();

        $order['new_order']=Order::where('status',1)
                                  ->count();
        $order['pending_order']=Order::where('status',2)
                                ->count();
        $order['approved_order']=Order::where('status',3)
                                 ->count();
        $order['shipment_order']=Order::where('status',4)
                                  ->count();
        $order['delivered_order']=Order::where('status',5)
                                      ->count();
        $order['return_order']=Order::where('status',7)
                                  ->count();
        $order['cancel_order']=Order::where('status',6)
                                ->count();
      return $order;
}


 public static function orderCount(){
        $order = array();
        $order['total'] = Order::count();

        $order['new_order'] = Order::where('status', 1)
            ->count();

        $order['new_order_amount'] = Order::where('status', 1)->sum('total')-Order::where('status', 1)->sum('discount');
        $order['pending_order'] = Order::where('status', 2)
            ->count();

        $order['pending_order'] = Order::where('status', 2)
            ->count();
        $order['pending_order_amount'] = Order::where('status', 2)->sum('total') - Order::where('status', 2)->sum('discount');

        $order['approved_order'] = Order::where('status', 3)
            ->count();

        $order['approved_order_amount'] = Order::where('status', 3)->sum('total') - Order::where('status', 3)->sum('discount');

        $order['shipment_order'] = Order::where('status', 4)
            ->count();

        $order['shipment_order_amount'] = Order::where('status', 4)->sum('total') - Order::where('status', 4)->sum('discount');

        $order['delivered_order'] = Order::where('status', 5)
            ->count();
        $order['return_order'] = Order::where('status', 7)
            ->count();
        $order['cancel_order'] = Order::where('status', 6)
            ->count();
        return $order;
  }

  public static function profite(){
    $profite=array();
    $profite['today']=0;
    $today_order_items=OrderItem::where('created_at','>=',Carbon::today()->startOfDay())
                                ->where('created_at','<=',Carbon::today()->endOfDay())
                                ->get()
                                ->map(function($value){
                                  return $value->price*$value->quantity;
                                  });




    return  $today_order_items;
  }



public static function due()
    {
        $due = [];
        $due['office_sale_due'] = CustomerDue::where('status', 0)->sum('amount');
        $shipment_order = Order::where('status', 4)->get();
        $due['order_due'] = 0;
        $due['whole_sale_due'] = 00;
        foreach ($shipment_order as $item) {
            // return $item->order_type;
            if ($item->order_type != 3) {
                $due['order_due'] += ($item->total + $item->shipping_cost) - ($item->paid + $item->discount);
            }
            if ($item->order_type == 3) {
                $due['whole_sale_due'] += ($item->total + $item->shipping_cost) - ($item->paid + $item->discount);
            }
        }

        $a = SupplierPayment::all()->sum('amount');
        $b = Purchase::all()->sum('total');

        $product_supplier_id = Supplier::where('type', 1)->select('id')->pluck('id');
        $fabrice_supplier_id = Supplier::where('type', 2)->select('id')->pluck('id');

        $product_purchase_supplier_paid_amount = SupplierPayment::whereIn('supplier_id', $product_supplier_id)->sum('amount');
        $fabrics_purchase_supplier_paid_amount = SupplierPayment::whereIn('supplier_id', $fabrice_supplier_id)->sum('amount');
        $due['product_supplier'] =  Purchase::whereIn('supplier_id',$product_supplier_id)->sum('total') - $product_purchase_supplier_paid_amount;
        $due['fabrics_supplier'] = Purchase::whereIn('supplier_id',$fabrice_supplier_id)->sum('total') - $fabrics_purchase_supplier_paid_amount;
        return $due;
    }



    public static function analysis(){

        $analysis=[];
        $analysis['order']=self::OrderAnalysis();
        $analysis['wholesale']=self::WholeSaleAnalysis();
        $analysis['officeSale']=self::SaleAnalysis();
        return $analysis;

    }

  public static function OrderAnalysis(){

    //get today order
    $today_orders=Order::where('created_at','>=',Carbon::today()->startOfDay())
                          ->where('created_at','<=',Carbon::today()->endOfDay())
                          ->where('status','!=',6)
                           ->where('order_type','!=',3)
                          ->get();

    //get yesterday order
    $yesterday_orders=Order::where('created_at','>=',Carbon::yesterday()->startOfDay())
                          ->where('created_at','<=',Carbon::yesterday()->endOfDay())
                          ->where('status','!=',6)
                           ->where('order_type','!=',3)
                          ->get();
   //get this week order
   $this_week_orders=Order::where('created_at','>=',Carbon::yesterday()->subDays('7')->startOfDay())
                          ->where('created_at','<=', Carbon::yesterday()->endOfDay())
                          ->where('status','!=',6)
                           ->where('order_type','!=',3)
                          ->get();

   //get this month order
   $this_month_orders=Order::where('created_at','>=',Carbon::yesterday()->subDays('30')->startOfDay())
                          ->where('created_at','<=', Carbon::yesterday()->endOfDay())
                          ->where('status','!=',6)
                          ->where('order_type','!=',3)
                          ->get();


      $analysis=[];
      $product_quantity_today=0;
      $product_quantity_yesterday=0;
      $product_quantity_week=0;
      $product_quantity_month=0;

      $analysis['today']=[];
      $analysis['yesterday']=[];
      $analysis['this_week']=[];
      $analysis['this_month']=[];


      //today order summary
        foreach($today_orders as $today_order){
            $product_quantity_today+=OrderItem::where('order_id',$today_order->id)->sum('quantity');
            $analysis['today']=[
                'order_quantity'=>$today_orders->count(),
                'product_quanity'=>$product_quantity_today,
                'amount'=>$today_orders->sum('total') - $today_orders->sum('discount')
              ];
          }


      //yesterday order summary
        foreach($yesterday_orders as $yesterday_order){
            $product_quantity_yesterday+=OrderItem::where('order_id',$yesterday_order->id)->sum('quantity');
            $analysis['yesterday']=[
                'order_quantity'=>$yesterday_orders->count(),
                'product_quanity'=>$product_quantity_yesterday,
                'amount'=>$yesterday_orders->sum('total')-$yesterday_orders->sum('discount')
              ];
          }

      //this week  order summary
          foreach($this_week_orders as $this_week_order){
            $product_quantity_week += OrderItem::where('order_id',$this_week_order->id)->sum('quantity');
            $analysis['this_week']=[
                'order_quantity'=>$this_week_orders->count(),
                'product_quanity'=>$product_quantity_week,
                'amount'=>$this_week_orders->sum('total')-$this_week_orders->sum('discount')
              ];
          }

      //this month  order summary
          foreach($this_month_orders as $this_month_order){
            $product_quantity_month += OrderItem::where('order_id',$this_month_order->id)->sum('quantity');
            $analysis['this_month']=[
                'order_quantity'=>$this_month_orders->count(),
                'product_quanity'=>$product_quantity_month,
                'amount'=>$this_month_orders->sum('total')-$this_month_orders->sum('discount')
              ];
          }

          return $analysis;

  }



  public static function WholeSaleAnalysis(){

    //get today _whole_sales
    $today_whole_sales=Order::where('created_at','>=',Carbon::today()->startOfDay())
                          ->where('created_at','<=',Carbon::today()->endOfDay())
                          ->where('status','!=',6)
                           ->where('order_type',3)
                          ->get();

     //get yesterday _whole_sales
    $yesterday_whole_sales=Order::where('created_at','>=',Carbon::yesterday()->startOfDay())
                          ->where('created_at','<=',Carbon::yesterday()->endOfDay())
                          ->where('status','!=',6)
                           ->where('order_type',3)
                          ->get();

   //get this week _whole_sales
   $this_week_whole_sales=Order::where('created_at','>=',Carbon::today()->subDays('7')->startOfDay())
                          ->where('created_at','<=', Carbon::today()->endOfDay())
                          ->where('status','!=',6)
                           ->where('order_type',3)
                          ->get();

   //get this month _whole_sales
   $this_month_whole_sales=Order::where('created_at','>=',Carbon::today()->subDays('30')->startOfDay())
                          ->where('created_at','<=', Carbon::today()->endOfDay())
                          ->where('status','!=',6)
                          ->where('order_type',3)
                          ->get();
    //return $this_month_whole_sales->count();

    $analysis=[];
    $product_quantity_today=0;
    $product_quantity_yesterday=0;
    $product_quantity_week=0;
    $product_quantity_month=0;
    $analysis['today']=[];
    $analysis['yesterday']=[];
    $analysis['this_week']=[];
    $analysis['this_month']=[];


    //today _whole_sales summary
      foreach($today_whole_sales as $today_whole_sale){
          $product_quantity_today+=OrderItem::where('order_id',$today_whole_sale->id)->sum('quantity');
          $analysis['today']=[
              'order_quantity'=>$today_whole_sales->count(),
              'product_quanity'=>$product_quantity_today,
              'amount'=>$today_whole_sales->sum('total')-$today_whole_sales->sum('discount')
            ];
      }

      //yesterday whole sales summary
      foreach($yesterday_whole_sales as $yesterday_whole_sale){
          $product_quantity_yesterday+=OrderItem::where('order_id',$yesterday_whole_sale->id)->sum('quantity');
          $analysis['yesterday']=[
              'order_quantity'=>$yesterday_whole_sales->count(),
              'product_quanity'=>$product_quantity_yesterday,
              'amount'=>$yesterday_whole_sales->sum('total')-$yesterday_whole_sales->sum('discount')
            ];
      }

    //this week  _whole_sales summary
        foreach($this_week_whole_sales as $this_week_whole_sale){
          $product_quantity_week += OrderItem::where('order_id',$this_week_whole_sale->id)->sum('quantity');
          $analysis['this_week']=[
              'order_quantity'=>$this_week_whole_sales->count(),
              'product_quanity'=>$product_quantity_week,
              'amount'=>$this_week_whole_sales->sum('total')-$this_week_whole_sales->sum('discount')
            ];
        }

    //this month  _whole_sales summary
        foreach($this_month_whole_sales as $this_month_whole_sale){
          $product_quantity_month += OrderItem::where('order_id',$this_month_whole_sale->id)->sum('quantity');
          $analysis['this_month']=[
              'order_quantity'=>$this_month_whole_sales->count(),
              'product_quanity'=>$product_quantity_month,
              'amount'=>$this_month_whole_sales->sum('total')-$this_month_whole_sales->sum('discount')
            ];
        }

        return $analysis;

  }

   public static function SaleAnalysis(){

        //get today sales
        $today_sales=Sale::where('created_at','>=',Carbon::today()->startOfDay())
                            ->where('created_at','<=',Carbon::today()->endOfDay())
                            ->get();

        //get yester sales
        $yesterday_sales=Sale::where('created_at','>=',Carbon::yesterday()->startOfDay())
                            ->where('created_at','<=',Carbon::yesterday()->endOfDay())
                            ->get();

      //get this week _whole_sales
      $this_week_sales=Sale::where('created_at','>=',Carbon::yesterday()->subDays('7')->startOfDay())
                            ->where('created_at','<=', Carbon::yesterday()->endOfDay())
                            ->get();

      //get this month order
      $this_monthe_sales=Sale::where('created_at','>=',Carbon::yesterday()->subDays('30')->startOfDay())
                              ->where('created_at','<=', Carbon::yesterday()->endOfDay())
                              ->get();


    $analysis=[];
    $product_quantity_today=0;
    $product_quantity_yesterday=0;
    $product_quantity_week=0;
    $product_quantity_month=0;
    $analysis['today']=[];
    $analysis['yesterday']=[];
    $analysis['this_week']=[];
    $analysis['this_month']=[];


    //today order summary
      foreach($today_sales as $today_sale){
          $product_quantity_today+=SaleItems::where('sale_id',$today_sale->id)->sum('qty');
          $analysis['today']=[
              'order_quantity'=>$today_sales->count(),
              'product_quanity'=>$product_quantity_today,
              'amount'=>$today_sales->sum('total')-$today_sales->sum('discount')
            ];
        }
    //yesterday order summary
    foreach($yesterday_sales as $yesterday_sale){
        $product_quantity_yesterday +=SaleItems::where('sale_id',$yesterday_sale->id)->sum('qty');
        $analysis['yesterday']=[
          'order_quantity' => $yesterday_sales->count(),
          'product_quanity' => $product_quantity_yesterday,
          'amount' => $yesterday_sales->sum('total')-$yesterday_sales->sum('discount'),
        ];
    }
    //this week  order summary
        foreach($this_week_sales as $this_week_sale){
          $product_quantity_week += SaleItems::where('sale_id',$this_week_sale->id)->sum('qty');
          $analysis['this_week']=[
              'order_quantity'=>$this_week_sales->count(),
              'product_quanity'=>$product_quantity_week,
              'amount'=>$this_week_sales->sum('total')-$this_week_sales->sum('discount')
            ];
        }

    //this month  order summary
        foreach($this_monthe_sales as $this_monthe_sale){
          $product_quantity_month += SaleItems::where('sale_id',$this_monthe_sale->id)->sum('qty');
          $analysis['this_month']=[
              'order_quantity'=>$this_monthe_sales->count(),
              'product_quanity'=>$product_quantity_month,
              'amount'=>$this_monthe_sales->sum('total')-$this_monthe_sales->sum('discount')
            ];
        }

        return $analysis;

      }




}
