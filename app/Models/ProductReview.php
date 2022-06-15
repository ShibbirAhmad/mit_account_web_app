<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
     
      
  public function reviewer(){

       return   $this->belongsTo('App\User','user_id') ;

      }


    public function review_product(){

       return   $this->belongsTo('App\Models\Product','product_id') ;

      }




}
