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
