<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductReview;

class ProductReviewController extends Controller
{
             
       public function get_customer_review(){

             $reviews = ProductReview::latest()->with('review_product','reviewer')->paginate(20) ;
             return response()->json([
                  "status" => "OK",
                  "reviews" => $reviews,
             ]);
       }
       



      public function deactive_customer_review($id){

             $review = ProductReview::findOrFail($id) ;
             $review->status=0;
             $review->save();
             return response()->json([
                  "status" => "OK",
                  "message" => 'review de-activated',
             ]);
       }



      public function active_customer_review($id){

             $review = ProductReview::findOrFail($id) ;
             $review->status=1;
             $review->save();
             return response()->json([
                  "status" => "OK",
                  "message" => 'review activated',
             ]);
       }


      public function remove_customer_review($id){

                  $review = ProductReview::findOrFail($id) ;
                  $review->delete();
                  return response()->json([
                        "status" => "OK",
                        "message" => 'review deleted',
                  ]);
       }











}
