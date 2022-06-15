<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use Exception;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\City;
use App\Models\Team;
use App\Models\Offer;
use App\Models\Coupon;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\FlashDeal;
use App\Models\OrderItem;
use App\Models\SubCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\CampaignSlider;
use App\Models\SubSubCategory;
use App\Models\Category_slider;
use App\Models\OccasionProduct;
use App\Models\OtpVerification;
use App\Models\SeasonalProduct;
use App\Models\BuyOneGetOneOffer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\SubCity;
use App\Service\SmsService;

class HomeController extends Controller
{


    public function  get_team_members(){
            $team = Team::where('status',1)->orderBy('position','ASC')->get();
            return response()->json([
                'status' => "OK",
                'team'  => $team ,
        ]);
    }
    public function productIdWise(Request $request,$id){
      $product=Product::where('show_homepage',1)->where('id',$id)->with(['productImage','productAttribute.attribute','productVariant.variant'])->first();
        return response()->json([
            'product'=>$product
        ]);
    }
    public function products(Request $request)
    {

        $sub_categories =SubCategory::select('id','category_id','name','slug')->orderBy('position','ASC')->where('status',1)->where('show_homepage',1)->with(['category','subSubCategory'])->paginate(5);

        foreach($sub_categories as $sub_category){
             $sub_category->{'products'}=Product::where('sub_category_id',$sub_category->id)->with('productImage')
                                           ->orderBy('product_position','ASC')
                                           ->where('status',1)
                                           ->where('show_homepage',1)
                                           ->select('id','name','price','sale_price','slug','discount')
                                          ->get()
                                          ->take(10);
        }
         return response()->json($sub_categories);
    }

    public function flashSale(){
        $flash_sale_products=Product::where(['status'=>1,'product_placement'=>1])
                                     ->where('show_homepage',1)
                                     ->orderBy('product_position','DESC')
                                     ->with('productImage')
                                     ->select('id','name','price','sale_price','slug','discount')
                                     ->get();
        return \response()->json($flash_sale_products);
    }



    public function product($slug)
    {
         //this slug with encoding for any language
        $product = Product::where('slug', $slug)->where('show_homepage',1)->with(['productAttribute.attribute','productVariant.variant','productReviews.reviewer','merchant'])->first();
         if ($product) {
            $product_review=ProductReview::where('product_id',$product->id)->where('status',1)->get();
            $rating_stars=array();
            $rating_stars['five_star']=$product_review->where('rating_starts',5)->count();
            $rating_stars['four_star']=$product_review->where('rating_starts',4)->count();
            $rating_stars['three_star']=$product_review->where('rating_starts',3)->count();
            $rating_stars['two_star']=$product_review->where('rating_starts',2)->count();
            $rating_stars['one_star']=$product_review->where('rating_starts',1)->count();
            return response()->json([
                    'status' => "SUCCESS",
                    'product' => $product,
                    'rating_stars' => $rating_stars,
                ]);
        }else{
            return response()->json([
                'status' => 'FAILED'
            ]);
        }
    }



    public function productImage($slug){
      $product = Product::where('slug', $slug)->first();
      $product_images = ProductImage::where('product_id',$product->id)
                                        ->select('product_image')
                                        ->get();
     return response()->json($product_images);
    }


    public function relatedProduct($slug){

        $product=Product::where('slug',$slug)->first();

        $products=Product::where('sub_sub_category_id',$product->sub_sub_category_id)
                            ->whereNotNull('sub_sub_category_id')
                            ->where('id','!=',$product->id)
                            ->where('show_homepage',1)
                            ->with('productImage')->where('status',1)->take(24)->get();

        if(count($products) > 0){

             return response()->json($products);

        } else{

             $products=Product::where('sub_category_id',$product->sub_category_id)
                            ->whereNotNull('sub_category_id')
                            ->where('id','!=',$product->id)
                            ->where('show_homepage',1)
                            ->with('productImage')->where('status',1)->take(24)->get();

             return response()->json($products);
        }


 }

    public function categories()
    {
      $categories = Category::orderBy('id', 'ASC')
                                ->where(['status' => 1, 'is_selected' => 1])
                                ->select('slug','name','id')
                                ->with(['subCategory.SubSubCategory'])
                                ->take(11)
                                ->get();

     return response()->json([
            'SUCCESS' => true,
            'categories' => $categories,
            'clhg'=> true
        ]);
    }

     public function slider()
    {

        $slider = Slider::where('status',1)->where('position',1)->select('id','url','image')->latest()->get();
        $slider_banner =Slider::where('status',1)->where('position',2)->select('id','url','image')->orderBy('id','DESC')->first();
        $best_selling_produc_id=OrderItem::where('created_at','>=',Carbon::today()->subDays('7'))
                                                ->select('product_id',DB::raw('count(*) as total'))
                                                ->groupBy('product_id')
                                                ->orderBy('total','DESC')
                                                ->take(12)
                                                ->pluck('product_id');
        $best_seller_products =Product::WhereIn('id',$best_selling_produc_id)->select('id','name','price','sale_price','slug','discount')->where('show_homepage',1)->where('status',1)->with('productImage')->get();
        return response()->json([
            'SUCCESS' => true,
            'sliders' => $slider,
            'slider_banner'=>$slider_banner,
            'best_seller'=>$best_seller_products
        ]);

    }



    public function category($slug)
    {
      $category=Category::where('slug',$slug)->with(['subCategory'])->first();
      if($category){
        return response()->json([
                    'status'=> 'SUCCESS',
                    'category'=> $category ,
                ]);
      }
    }


    public function categoryWiseProduct(Request $request)
    {
        $category=Category::where('slug',$request->slug)->first();
        if ($category) {
            $products=Product::where('category_id',$category->id)->where('show_homepage',1)->orderBy('id','DESC')->where('status',1)->with('productImage')->paginate(5);
            return response()->json([
                        'status'=> 'SUCCESS',
                        'products'=> $products ,
                    ]);
        }
    }

    public function categoryWiseProductPriceFilter(Request $request){

        $category=Category::where('slug',$request->slug)->first();
        $products=Product::where('category_id',$category->id)->where('status',1)->where('show_homepage',1)->where('price','>=',$request->min_price)->where('price','<=',$request->max_price)->with('productImage')
            ->where('status',1)
            ->paginate(20);
        return response()->json([
            "status" => "OK",
            "products" => $products ,
        ]);
    }


 //function for display category slider
    public  function display_category_slider(Request $request){
        $category=Category::where('slug',$request->slug)->first();
        $category_sliders=Category_slider::where('status',1)->where('category_id',$category->id)->orderBy('id','DESC')->get();

            return response()->json([
                "status" => "OK",
                "category_sliders" => $category_sliders ,
            ]);
    }

    //function for display sub category slider
    public  function display_sub_category_slider(Request $request){
        $sub_category=SubCategory::where('slug',$request->slug)->first();
        $sub_category_sliders = Category_slider::where('status',1)->where('sub_category_id',$sub_category->id)->orderBy('id','DESC')->get();
        return response()->json([
                "status" => "OK",
                "sub_category_sliders" => $sub_category_sliders ,
        ]);
    }

     //function for display sub sub category slider
     public   function display_sub_sub_category_slider(Request $request){

        $sub_sub_category=SubSubCategory::where('slug',$request->slug)->first();

        $sub_sub_category_sliders = Category_slider::where('status',1)->where('sub_sub_category_id',$sub_sub_category->id)->orderBy('id','DESC')->get();
        return response()->json([
                "status" => "OK",
                "sub_sub_category_sliders" => $sub_sub_category_sliders ,
        ]);
    }

     public function offers(){
        $offers = Offer::where('status', 1)->orderBY('id','DESC')->select('id','image','url','name')->take(12)->get();
        return response()->json([
            'status' => 'SUCCESS',
            'offers' => $offers
        ]);
    }

    public function subCategory($slug){
        $sub_category=SubCategory::where('slug',$slug)->with('subSubCategory')->first();
        if($sub_category){
            $sub_category->{'simillar_categories'}= SubCategory::where('status',1)->get();
            return response()->json([
                        'status'=> 'SUCCESS',
                        'sub_category'=> $sub_category ,
                    ]);
        }

    }

    public function subCategoryWiseProduct(Request $request){

        $sub_category=SubCategory::where('slug',$request->slug)->first();
        if ($sub_category) {
        $products=Product::where('sub_category_id',$sub_category->id)->where('show_homepage',1)->orderBy('id','desc')->with('productImage')->where('status',1)->orderBy('id','DESC')->paginate(100);
        return response()->json([
                'status'=> 'SUCCESS',
                'products'=> $products,
            ]);
        }

    }


    public function SearchProduct($search){
        $products=Product::where('name','like', '%' . $search . '%')
                          ->orWhere('product_code','like', '%' . $search . '%')
                          ->where('status',1)
                          ->where('show_homepage',1)
                          ->with('productImage')
                         ->get();
      return \response()->json($products);
    }

    public function subSubCategory($slug){
        $sub_sub_category=SubSubCategory::where('slug',$slug)->first();
        if($sub_sub_category){
        $sub_sub_category->{'simillar_categories'}= SubSubCategory::where('status',1)->get();
            return response()->json([
                        'status'=> 'SUCCESS',
                        'sub_sub_category'=> $sub_sub_category ,
                    ]);
        }

    }
    public function subSubCategoryWiseProduct(Request $request){

        $sub_sub_category=SubSubCategory::where('slug',$request->slug)->first();
        $products=Product::where('sub_sub_category_id',$sub_sub_category->id)->orderBy('id','DESC')->where('status',1)->where('show_homepage',1)->with('productImage')->orderBy('id','DESC')->paginate(100);
        return response()->json([
            'status' => 'SUCCESS',
            'products' => $products,
        ]);



    }



//function for sort via price
   public  function sort_by_price(Request $request){
        $orderBy='ASC';
            if($request->sort_value==2){
                $orderBy='DESC';
            }
            $category=Category::where('slug',$request->slug)->first();
            $products=Product::where('category_id',$category->id)->orderBy('price',$orderBy)->where('status',1)->where('show_homepage',1)->with('productImage')->get();
            return response()->json([
                    "products" => $products ,
            ]);

   }

   //function for sub_category_sort_by_price
    public  function sub_category_sort_by_price(Request $request){

        $orderBy='ASC';
        if($request->sort_value==2){
            $orderBy='DESC';
        }
        $sub_category=SubCategory::where('slug',$request->slug)->first();
        $products=Product::where('sub_category_id',$sub_category->id)->orderBy('price',$orderBy)->where('status',1)->where('show_homepage',1)->with('productImage')->get();
        return response()->json([
                "products" => $products ,
        ]);

     }

     //function for sub_sub_category_sort_by_price
    public  function sub_sub_category_sort_by_price(Request $request){

        $orderBy='ASC';
        if($request->sort_value==2){
            $orderBy='DESC';
        }
        $sub_sub_category=SubSubCategory::where('slug',$request->slug)->first();
        $products=Product::where('sub_sub_category_id',$sub_sub_category->id)->orderBy('price',$orderBy)->where('status',1)->where('show_homepage',1)->with('productImage')->get();
        return response()->json([
            "products" => $products
        ]);

     }

      public function get_quick_view_product($id){

        $product= Product::where('id',$id)->with(['productAttribute.attribute','productVariant.variant','productImage'])->first();
        $recommended_products=Product::where('sub_category_id',$product->sub_category_id)->where('id','!=',$product->id)->where('status',1)->where('show_homepage',1)
        ->with(['productAttribute.attribute','productVariant.variant','productImage'])
        ->take(24)->get();
              return  response()->json([
                  "status" => "OK",
                  "product" => $product ,
                  'recommended_products'=>$recommended_products
              ]);
      }


      public function SendOtp(Request $request){
       // return $request->all();
        $validatedData = $request->validate([
            'mobile_no' => 'required|digits:11'
        ]);

        $code=random_int(1000,9999);
        $otp=new OtpVerification();
        $otp->mobile_no=$request->mobile_no;
        $otp->code=Hash::make($code);
        if($otp->save()){
           SmsService::sendOtpCode ($otp->mobile_no,$code);
            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Sent one time pin on your number',
            ]);

      }
    }




 public function verifyCodeOtp(Request $request)

{
     $validatedData = $request->validate([
         'verify_code' => 'required '
     ]);

   //  return $request->all();
    $otp=OtpVerification::where('mobile_no',$request->mobile_no)->latest()->first();
     $to_time = strtotime(Carbon::now()->format('Y-m-d g:i:s'));
     $from_time = strtotime(Carbon::parse($otp->created_at)->format('Y-m-d g:i:s'));

       $expire_time= round(abs($to_time - $from_time) / 60,2);


     if (Hash::check($request->verify_code, $otp->code)) {
           if($expire_time > 5){
               return \response()->json('Code Time Expired');
             }else{
            $user=User::where('mobile_no',$request->mobile_no)->first();
            if(empty($user)){
                $user=new User();
                $user->mobile_no=$request->mobile_no;
                $user->password=Hash::make($request->mobile_no);
                $user->name=null;
                $user->email=null;
                $user->image=null;
                $user->city_id=null;
                $user->address=null;
                $user->status=1;
                $user->token=Hash::make(rand(555,999));
                $user->save();
           }
            Auth::loginUsingId($user->id);
            return \response()->json([
                'status'=>"OK",
                'message'=> 'Your number is verified',
                'user'=>Auth::user(),
                'token'=>$user->token,
             ]);

             }
     }else{
           return \response()->json('Code Dose Not Match');
     }

  }



      public function publish_occation_campaign(){

        $occasion=OccasionProduct::latest()->where('status',1)->first();
        if($occasion){
           $occasion_p_top=Product::where('product_code',$occasion->product_code_one)->select(['id','name','slug','price','sale_price'])->with('productImage')->first();
           $occasion_p_bottom=Product::where('product_code',$occasion->product_code_two)->select(['id','name','slug','price','sale_price'])->with('productImage')->first();

            return response()->json([
                  'status' => "OK",
                  'occasion' => $occasion,
                  'occasion_p_top' => $occasion_p_top,
                  'occasion_p_bottom' => $occasion_p_bottom,
            ]);
        }


   }


    public function publish_seasional_campaign(){

            $seasion=SeasonalProduct::latest()->where('status',1)->where('show_homepage',1)->first();
            if($seasion){
            $seasion_p_top=Product::where('product_code',$seasion->product_code_one)->select(['id','name','slug','price','sale_price'])->with('productImage')->first();
            $seasion_p_bottom=Product::where('product_code',$seasion->product_code_two)->select(['id','name','slug','price','sale_price'])->with('productImage')->first();

                return response()->json([
                    'status' => "OK",
                    'seasion' => $seasion,
                    'seasion_p_top' => $seasion_p_top,
                    'seasion_p_bottom' => $seasion_p_bottom,
                ]);
            }


    }


    public function publish_buy_one_get_one_campaign(){

                $buy_get=BuyOneGetOneOffer::latest()->first();
                $buy_get_p=Product::where('product_code',$buy_get->product_code)->where('show_homepage',1)->select(['id','name','slug','price','sale_price'])->with('productImage')->first();

                    return response()->json([
                        'status' => "OK",
                        'buy_get' => $buy_get,
                        'buy_get_p' => $buy_get_p,
                    ]);

        }




 public function flashDeals(){

    $flash_deals=FlashDeal::latest()->first();
    $today=Carbon::today()->format('y-m-d');
    //firstly replacing flash deals expired items

    //now finding which products are exists in flash deals
    $flash_deals_products= Product::where(['status'=>1,'product_placement'=>1])->where('expired_date','>',$today)->where('show_homepage',1)->orderBy('expired_date','ASC')->select('id','name','price','sale_price','slug','expired_date')->with('productImage')->take(5)->get();
    $flash_slider_products= Product::where(['status'=>1,'product_placement'=>1])->where('expired_date','>',$today)->where('show_homepage',1)->orderBy('product_position','DESC')->select('id','name','price','sale_price','slug')->with('productImage')->inRandomOrder()->take(15)->get();
    //adding as a object latest expired date of product
    if($flash_deals_products->count() > 0){
        $flash_deals->{'expired_time'} = $flash_deals_products[0]->expired_date;
    }
    return response()->json([
        'status' => 'OK',
        'flash_deals' => $flash_deals,
        'flash_deals_products' => $flash_deals_products,
        'flash_slider_products' => $flash_slider_products,
    ]);



}


    public function customer_review_to_product(Request $request,$id){
        //   return $request->all() ;
          $request->validate([
            'review_text' => 'required ',
            'star_number' => 'required ',
         ]);

          $review = new ProductReview();
          $review->product_id=$id;
          $review->user_id=Auth::user()->id;
          $review->rating_starts=$request->star_number+1;
          $review->review=$request->review_text;
          if ($request->hasFile('review_img')) {
            $file_path=$request->file('review_img')->store('images/product_review','public');
            $review->image=$file_path ;
         }
          $review->status=0;
          if($review->save()){
              return response()->json([
                  'status' => 'OK',
                  'message'=> 'your review added successfully',
              ]);
          }

    }



    public function merchantWiseProduct(Request $request)
    {

     $merchant=Merchant::where('id',$request->id)->first();
      $products=Product::where(['merchant_id'=>$request->id, 'status' => 1])->where('show_homepage',1)->orderBy('id','DESC')->with('productImage')->paginate(20);

      if(!empty($request->category_id)){

        $products=Product::where(['merchant_id'=>$request->id, 'status' => 1,'category_id'=>$request->category_id])->orderBy('id','DESC')->where('show_homepage',1)->with('productImage')->paginate(20);

      }

      return response()->json([
          'merchant'=>$merchant,
          'products' =>$products
      ]);
    }


public function get_all_flash_deals(){

//return "hello";
    $today=Carbon::today()->format('y-m-d') ;
    $products=Product::where('expired_date','>',$today)->orderBy('expired_date','ASC')
              ->where(['status'=>1,'product_placement'=>1])->with('productImage')->get();
    $expired_time=$products[0]->expired_date ;

    return response()->json([
        'products' =>  $products,
        'expired_time' => $expired_time ,
      ]);


  }


    public function ApplyCoupon(Request $request){


        $coupon=Coupon::where('code',$request->coupoon_code)->first();

        $curentDate=date('Y-m-d');

        if(empty($coupon)){
            return response()->json('Please Give A Valid Coupon Code');
        }

        if($coupon->status != 1){
            return response()->json('This Coupon Not Active');
         }

      //  return $coupon->start_date;
        if($coupon->start_date <= $curentDate){
             if($coupon->expire_date >= $curentDate){

                return response()->json([

                    'success' =>"OK",
                    'coupon' =>$coupon,
                    'message'=>"Coupon Was Successfully Applied"
                ]);

               }else{
                return response()->json("This Coupon Already Exipird");
             }
           }else{
            return response()->json('This Coupon Start From '. $coupon->start_date);
        }

        return response()->json("Some Thing Wrong");
    }


       public function campaigns(){

        $campaigns=Campaign::where('status',1)
                            ->whereDate('start_date','<=',Carbon::now()->today())
                            ->whereDate('end_date','>=',Carbon::now()->today())
                            ->get()
                            ->each(function($campaign){
                                $campaign->load(['campaignProducts'=> function($query){
                                    return $query->with('product.productImage');
                                }]);
                            });
        $sliders=CampaignSlider::whereNull('campaign_id')->where('status',1)->get();
        return response()->json([
            'campaign' => $campaigns,
            'sliders' => $sliders
        ]);
    }
   public function campaign($slug){

        $campaign=Campaign::where(['slug'=>$slug, 'status' =>  1,])->whereDate('start_date','<=',Carbon::now()->today())
        ->whereDate('end_date','>=',Carbon::now()->today())->with('campaignProducts.product.productImage')->first();
         $sliders=CampaignSlider::where('campaign_id',$campaign->id)->where('status',1)->get();
        return response()->json([
            'campaign' => $campaign,
            'sliders' => $sliders
        ]);
    }



    public function cartCheck(){
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
       }else{
        return \response()->json([
            'message' => "Go To Next",
            'success' => true
        ]);
       }
    }



    public function addToCart(Request $request, $id){
    //    return $request->all();
        $product = Product::with('productImage')->findOrFail($id);

        // if ($product->stock <= 0) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => "Product Stock Out",
        //     ]);
        // }
        // if ($request->quantity > $product->stock) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => "Product Highest Quanity '$product->stock'",
        //     ]);
        // }

        $ip = $request->ip();
        $carts = Cart::where([
            'user_ip' => $ip,
            'product_id' => $product->id,
            'variant_id' => $request->params['variant_id'],
        ])->get();
        if (count($carts) > 0) {
            foreach ($carts as $cart) {
                $cart->quantity += $request->params['quantity'];
                $cart->save();
            }
        } else {
            $cart = new Cart();
            $cart->product_id = $product->id;
            $cart->merchant_id = $product->merchant_id;
            $cart->product_name = $product->name;
            $cart->product_slug = $product->slug;
            $cart->price = $product->price;
            $cart->quantity = $request->params['quantity'] ?? 1;
            $cart->attribute_id = $request->params['attribute_id'] ?? null;
            $cart->variant_id = $request->params['variant_id'] ?? null;
            $cart->user_ip = $ip;
            $cart->product_image = $product->productImage[0]->product_image;
            $cart->save();
        }
        return response()->json([
            'status' => 'SUCCESS',
            'message' => $product->name . " Added To Your Cart",
        ]);

    }

    public function getCartConent()
    {
        return response()->json([
            'cart_total' => Cart::cartTotal(),
            'cart_content' => Cart::CartContent(),
            'item_count' => Cart::cartItem(),
        ]);

    }

    public function removeCartItem($id)
    {
        $cart = Cart::find($id);
        if ($cart->delete()) {
            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Item Removed",
            ]);
        }
    }


    public function UpdateCartItem(Request $request,$id){
        // return $request->all();
        $cart = Cart::findOrFail($id);
        $cart->quantity = $request->qty;
        $cart->save() ;
            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Cart Updated",
            ]);

    }



   public function cityListSearch($name){
        $cities = City::orderBy('name')->orWhere('name','like', '%' . $name . '%')->orWhere('name','like','[a-z]%')->where('status',1)->get();
           return response()->json([
                'status' => 'SUCCESS',
                'cities' => $cities,
            ]);
    }


   public function cityList(){
        $cities = City::orderBy('name')->where('status',1)->get();
           return response()->json([
                'status' => 'SUCCESS',
                'cities' => $cities,
            ]);
    }



   public function cityWiseSubCitySearch($city_id,$name){
        $sub_cities = SubCity::orderBy('name')->where('status',1)->where('name','like','%'.$name.'%')->get();
           return response()->json([
                'status' => 'SUCCESS',
                'sub_cities' => $sub_cities,
            ]);
    }




   public function cityWiseSubCity($id){
        $sub_cities = SubCity::orderBy('name')->where('city_id',$id)->where('status',1)->get();
           return response()->json([
                'status' => 'SUCCESS',
                'sub_cities' => $sub_cities,
            ]);
    }



}


