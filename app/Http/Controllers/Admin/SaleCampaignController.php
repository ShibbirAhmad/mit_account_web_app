<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ParallaxBanner ;
use App\Models\SaleCampaign ;
use App\Models\OccasionProduct ;
use App\Models\SeasonalProduct;
use App\Models\BuyOneGetOneOffer;
use App\Models\FlashDeal;
use App\Models\PropUpBanner;

class SaleCampaignController extends Controller
{
      
    
    
public function get_sale_campaign_list() {

        $sale_campaigns = SaleCampaign::orderBy('id','desc')->get();
            return response()->json([
                'status' => "OK",
                 'sale_campaigns' => $sale_campaigns,
            ]);

}


   
public function store_sale_campaign(Request $request)
 {

      $validatedData = $request->validate([
          'name'  => 'required',
          'expired_date'  => 'required',
          'border_width'  => 'required',
          
      ]);
    
      $sale_campaign =new SaleCampaign();
      $sale_campaign->name=$request->name;
      $sale_campaign->expired_date=$request->expired_date;
      $sale_campaign->background_color=$request->background_color;
      $sale_campaign->border_color=$request->border_color;
      $sale_campaign->border_width=$request->border_width;
      $sale_campaign->order_by=$request->order_by;
      $sale_campaign->status=1;
 
      if ($sale_campaign->save()) {
          return response()->json([
              'status' => 'SUCCESS',
              'message' => 'Sale capmaign addedsuccessfully',
          ]);
      }else{
          return response()->json([
              'status' => 'FAIL',
              'message' => 'Expire date is required and something went wrong',
          ]); 
      }
}




public function active_sale_campaign($id) {

    $sale_campaign = SaleCampaign::findOrFail($id);
    $sale_campaign->status=1 ;
    if ($sale_campaign->save()) {
        return response()->json([
            "status" => "OK",
            "message" => "this campaign activated",
        ]);
    }

}




public function de_active_sale_campaign($id) {

    $sale_campaign = SaleCampaign::findOrFail($id);
    $sale_campaign->status=0 ;
    if ($sale_campaign->save()) {
        return response()->json([
            "status" => "OK",
            "message" => "this campaign de-activated",
        ]);
     }
  }




    public function destroy_sale_campaign($id) {

            $sale_campaign = SaleCampaign::findOrFail($id);
            if ($sale_campaign->delete()) {
                return response()->json([
                    "status" => "OK",
                    "message" => "this campaign destroyed",
                ]);
        }
    }






    public function get_edit_campaign($id) {

        $sale_campaign = SaleCampaign::findOrFail($id);
            return response()->json([  
                'status' => "OK",
                'sale_campaign'=>$sale_campaign,
            ]);
        
        }


     

     
  public function update_sale_campaign(Request $request,$id){

    $validatedData = $request->validate([
        'name'  => 'required',
        'expired_date'  => 'required',
        'border_width'  => 'required',
        
    ]);
  
    $sale_campaign = SaleCampaign::findOrFail($id);
    $sale_campaign->name=$request->name;
    $sale_campaign->expired_date=$request->expired_date;
    $sale_campaign->background_color=$request->background_color;
    $sale_campaign->border_color=$request->border_color;
    $sale_campaign->border_width=$request->border_width;
    $sale_campaign->order_by=$request->order_by;
    $sale_campaign->status=1;

    if ($sale_campaign->save()) {
        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Sale capmaign updated successfully',
        ]);
    }else{
        return response()->json([
            'status' => 'FAIL',
            'message' => 'Expire date is required and something went wrong',
        ]); 
     }

 }




    // occasion campaign is start from here
        
    public function get_occasional_campaign() {

        $campaign = OccasionProduct::latest()->take(1)->first();
        if ($campaign) {
            return response()->json([
                "status" => "OK",
                "campaign" => $campaign ,
            ]);
        }

    }



    public function edit_occasional_campaign(Request $request,$id)
    {

    $validatedData = $request->validate([
            'heading' => 'required',
            'quote' => 'required',
            'product_code_one' => 'required',
            'product_code_two' => 'required',
        ]);

        $campaign = OccasionProduct::findOrFail($id);
        $campaign->heading=$request->heading;
        $campaign->quote=$request->quote;
        $campaign->product_code_one=$request->product_code_one;
        $campaign->product_code_two=$request->product_code_two;
        $campaign->status=$request->status;
        if ($request->hasFile('campaign_background') ) {
            $background_path = $request->file('campaign_background')->store('images/occasion_campaign', 'public');
            $campaign->background_image = $background_path;
        }
        if ($campaign->save()) {
            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Seasional campaign updated successfully',
            ]);
        }

    }


     //seasonal camapaign is start from here
     public function get_seasional_campaign() {

            $campaign = SeasonalProduct::latest()->take(1)->first();
            if ($campaign) {
                return response()->json([
                    "status" => "OK",
                    "campaign" => $campaign ,
                ]);
            }

        }



    public function edit_seasional_campaign(Request $request,$id)
        {

         $validatedData = $request->validate([
                'heading' => 'required',
                'quote' => 'required',
                'product_code_one' => 'required',
                'product_code_two' => 'required',
            ]);

            $campaign = SeasonalProduct::findOrFail($id);
            $campaign->heading=$request->heading;
            $campaign->quote=$request->quote;
            $campaign->product_code_one=$request->product_code_one;
            $campaign->product_code_two=$request->product_code_two;
            $campaign->status=$request->status;
            if ($request->hasFile('campaign_background') ) {
                $background_path = $request->file('campaign_background')->store('images/seasion_campaign', 'public');
                $campaign->background_image = $background_path;
            }
            if ($campaign->save()) {
                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'Seasional campaign updated successfully',
                ]);
            }

        }




    

     //buy one get one is here  
     public function get_buy_one_get_one_campaign(){

            $campaign = BuyOneGetOneOffer::latest()->take(1)->first();
            if ($campaign) {
                return response()->json([
                    "status" => "OK",
                    "campaign" => $campaign ,
                ]);
            }

        }



     public function edit_buy_one_get_one_campaign(Request $request,$id){

         $validatedData = $request->validate([
                'product_code' => 'required',
            ]);

            $campaign = BuyOneGetOneOffer::findOrFail($id);
            $campaign->product_code=$request->product_code;
            if ($request->hasFile('banner') ) {
                $banner_path = $request->file('banner')->store('images/buy_one_get_one', 'public');
                $campaign->banner = $banner_path;
            }
            if ($campaign->save()) {
                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'Campaign updated successfully',
                ]);
            }

        }


        

        
     //seasonal camapaign is start from here
     public function get_flash_deals_campaign() {

        $campaign = FlashDeal::latest()->first();
        if ($campaign) {
            return response()->json([
                "status" => "OK",
                "campaign" => $campaign ,
            ]);
        }

    }



public function edit_flash_deals_campaign(Request $request,$id)
    {

     $validatedData = $request->validate([
            'expired_date' => 'required',
        ]);

        $campaign = FlashDeal::findOrFail($id);
        $campaign->discount_stand_out=$request->discount_stand_out;
        $campaign->expired_date=$request->expired_date;
        if ($request->hasFile('banner') ) {
            $banner_path = $request->file('banner')->store('images/flash_campaign', 'public');
            $campaign->banner = $banner_path;
        }
        if ($campaign->save()) {
            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Flash Deals updated successfully',
            ]);
        }

    }
    

  


    public function get_prop_up(){

        $campaign = PropUpBanner::latest()->first();
        if ($campaign) {
            return response()->json([
                "status" => "OK",
                "campaign" => $campaign ,
            ]);
        }

    }



 public function update_prop_up_banner(Request $request,$id){

     $validatedData = $request->validate([
            'url' => 'required',
            'status' => 'required',
        ]);

        $campaign = PropUpBanner::findOrFail($id);
        $campaign->url=$request->url;
        $campaign->status=$request->status;
        if ($request->hasFile('banner') ) {
            $banner_path = $request->file('banner')->store('images/prop_up_banner', 'public');
            $campaign->banner = $banner_path;
        }
        if ($campaign->save()) {
            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'updated successfully',
            ]);
        }

    }
    
    
    
    
    
    

     public function parallaxBanner(){
            $campaign = ParallaxBanner::latest()->first();
            if ($campaign) {
                return response()->json([
                    "status" => "OK",
                    "campaign" => $campaign ,
                ]);
            }

        }



     public function parallaxBannerUpdate(Request $request,$id){

         $validatedData = $request->validate([
                'url' => 'required',
                'status' => 'required',
            ]);

            $campaign = ParallaxBanner::findOrFail($id);
            $campaign->url=$request->url;
            $campaign->status=$request->status;
            if ($request->hasFile('offer_banner') ) {
                $banner_path = $request->file('offer_banner')->store('images/parallax_banner', 'public');
                $campaign->offer_banner = $banner_path;
            }

           if ($request->hasFile('background_banner') ) {
                $banner_path = $request->file('background_banner')->store('images/parallax_banner', 'public');
                $campaign->background_banner = $banner_path;
            }

            if ($campaign->save()) {
                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'updated successfully',
                ]);
            }

        }





    
    


}
 








