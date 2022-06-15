<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderBarcode;
use App\Models\OrderItem;
use App\Models\Product;
use App\Service\SmsService;
use App\User;
use Cart;
use Illuminate\Support\Facades\Auth;
use Picqer;


class OrderController extends Controller
{

    public function __construct(Request $request)
    {
        if(!$request->ajax()){
            return abort(404);
        }

    }
    public function checkout(Request $request){

       // return Cart::total();
      //  return str_replace(Cart::total(),',','');
     //   return $request->all();
        $validatedData = $request->validate([
            'mobile_no' => 'required|digits:11',
            'name' => 'required ',
            'address' => 'required',
            'city' => 'required',
            'sub_city' => 'required',

        ]);


         if (Cart::total() <= 0) {
            return \response()->json([
                'message' => 'Your cart is empty',
            ]);
        }

         //////check single product checkout
        $product_need_count = 0;
        foreach (Cart::content() as $cart_item) {
            $not_single_checkut_product = Product::where('id', $cart_item->id)->where('single_checkout', 0)->first();
            if ($not_single_checkut_product) {
                $product_need_count += $cart_item->qty;
            }
        }
        if (Cart::count() < $product_need_count * 2) {
            return \response()->json([
                'message' => "১ টাকায় টি-শার্ট এর সাথে রেগুলার দামে আপনাকে আরো ". (($product_need_count * 2) - Cart::count())." টি  টি-শার্ট ক্রয় করতে হবে"
            ]);
        }

       // return $request->all();
       // $user=User::where('id',Auth::user()->id)->first();
        //update user city and address
        // $user->city_id=$request->city;
        // $user->address=$request->address;
        // $user->name=$request->name;
        // $user->save();

        $customer=Customer::where('phone',$request->mobile_no)->first();
        if(!$customer){
            $customer=new Customer();
            $customer->name=$request->name;
            $customer->phone=$request->mobile_no;
            $customer->address=$request->address;
            $customer->city_id=$request->city;
            $customer->custome_type=1;
             $customer->save();

        } else{
            $customer->name=$request->name;
            $customer->address=$request->address;
            $customer->city_id=$request->city;
            $customer->save();

        }

        //  return Cart::total();
          //save the order
          $id = Order::max('id') ?? 1;
          $invoice = 41151 + $id;
           $total=Cart::total();
          if(!empty($request->coupon_disocunt) && $request->coupon_disocunt > 0 ){
             $total=$total-$request->coupon_disocunt;
          }

          $order=new Order();
          $order->customer_id=$customer->id;
          $order->cutomer_phone=$request->mobile_no;
          $order->invoice_no=$invoice;
          $order->order_type=1;
          $order->city_id=$request->city;
          $order->shipping_cost=$request->shipping_cost ?? 0;
          $order->discount=$request->discount ?? 0;
          $order->paid=$request->paid ?? 0;
          $order->total=$total;
          $order->coupon_id=$request->coupon_id ?? null;
          $order->coupon_disocunt=$request->coupon_disocunt ?? null;
          $order->status=1;
         $order->sub_city_id=$request->sub_city;

        //if order save then save the order details

        if($order->save()){
            foreach(Cart::content() as $product){

              //update product stock
                // $product_stock=Product::where('id',$product->id)->first();
                // $product_stock->stock=$product_stock->stock-$product->qty;
                // $product_stock->save();

                $details=new OrderItem();
                $details->order_id=$order->id;
                $details->product_id=$product->id;
                $details->price=$product->price;
                $details->quantity=$product->qty;
                $details->attribute_id=$product->options->attribute_id??null;
                $details->variant_id=$product->options->variant_id??null;
                $details->total=$product->qty*$product->price;
                $details->save();
             }


            SmsService::SendMessageCustomer($order->cutomer_phone,$customer->name,$order->invoice_no);


             //create a order barcode
            $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
            $barcode = $generator->getBarcode($order->invoice_no, $generator::TYPE_CODE_128);
            $order_barcode = new OrderBarcode();
            $order_barcode->order_id = $order->id;
            $order_barcode->barcode = $barcode;
            $order_barcode->barcode_number = $order->invoice_no;
            $order_barcode->save();
             session()->put('order_id',$order->id);

             return \response()->json([
                'status'=>'SUCCESS',
                'message'=>'Order was place successfully',
                'order_id'=>$order->id,
                'order'=>Order::where('id',$order->id)->with('customer')->first(),

                 Cart::destroy()
            ]);

        }

    }

    public function orderList(){

       $user=Auth::user();

       $customer=Customer::where('phone',$user->mobile_no)->first();
        if($customer){
            $orders=Order::where('customer_id',$customer->id)->orderBy('id','DESC')->paginate(10);
            return response()->json([
                'status'=>'SUCCESS',
                'orders'=>$orders
            ]);
        }
    }


    public function user_order_details($id){

          $order =  Order::findOrFail($id);
          $order_items=OrderItem::where('order_id',$order->id)->with(['product.productVariant.variant.attribute','attribute','variant'])->get();

          return response()->json([
            'status'=>'SUCCESS',
            'order'=>$order,
            'items'=>$order_items
        ]);
    }




    public function customer_invoice_print($id){

           $order_id = explode(',',$id) ;
           $orders = Order::whereIn('id',$order_id)->get();
           return view('frontend.pdf.invoicePrint', \compact('orders'));
    }

     public function userLastOrder(){

        $user=Auth::user();

        $customer=Customer::where('phone',$user->mobile_no)->first();
         if($customer){
             $order=Order::where('customer_id',$customer->id)->with('customer')->orderBy('id','DESC')->first();
             $order_items=OrderItem::where('order_id',$order->id)->with(['product.productVariant.variant.attribute','attribute','variant'])->get();

             return response()->json([
                 'status'=>'SUCCESS',
                 'order'=>$order,
                 'items' => $order_items
             ]);
         }
     }

}
