<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStockTransfer extends Model
{
    public function from_product(){

        return $this->belongsTo('App\Models\Product','from_product_id');
    }
    public function to_product(){

        return $this->belongsTo('App\Models\Product','to_product_id');
    }
    public function creator(){

        return $this->belongsTo('App\Models\Admin','creator_id');
    }
}
