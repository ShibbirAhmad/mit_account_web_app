<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{

    
    public function product(){
        return $this->belongsTo('App\Models\Product','product_id')->select(['id','merchant_id','name','stock','price','sale_price','discount','product_code','thumbnail_img']);
    }

    public function attribute(){
        return $this->belongsTo('App\Models\Attribute','attribute_id');
    }


    public function variant(){
         return $this->belongsTo('App\Models\Variant','variant_id');
    }




}
