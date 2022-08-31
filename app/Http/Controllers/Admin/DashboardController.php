<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Balance;
use App\Models\Product;
use App\Models\Purchaseitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function OrderCreateAnalysis(){

         $stock=array();
         $stock['total_price']=0;
         $products=Product::where('stock','>',0)->get();
         $stock['total_quantity']=$products->sum('stock');

        foreach($products as $product){
            $product_purchase_item=Purchaseitem::where('product_id',$product->id)->get();
             $price=0;
            foreach($product_purchase_item as $item){
                $price+=$item->price*$item->stock;
            }
            if($product_purchase_item->sum('stock')>0){
                $stock['total_price'] += ($price/$product_purchase_item->sum('stock'))*$product->stock;
            }
        }

        $admin_order=Order::adminOrderAnalysis();
        $topSellinProductToday=Order::topSellingProductToday();
        $analysis=Order::analysis();
        $due=Order::due();
        return response()->json([
            'stock'=>$stock,
            'admin_order'=>$admin_order,
            'top_selling_products_today'=>$topSellinProductToday,
            'due'=>$due,
            'analysis'=>$analysis
        ]);
    }

    public function dashboard(){

        $order = Order::orderCount();
        return response()->json([
            'orders' => $order,
            'today' => Carbon::today()->format('Y-m-d'),
        ]);

    }


    public function DepartmentWiseAccounts(Request $request){

        $balance=Balance::where('status',1)->where('department',$request->department)->with('today_credit_balance','total_credit_balance','today_debit_balance','total_debit_balance')->get();
        return response()->json([
            'balance' => $balance,
        ]);
    }






}
