<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{



    public  function category(){
        return $this->belongsTo('App\Models\Category','category_id');
    }
     public function subSubCategory()
    {
        return $this->hasMany('App\Models\SubSubCategory','subcategory_id')->where('status',1)->select(['id','name','subcategory_id','status','show_homepage']);

    }
      public function products()

      {

       return $this->hasMany('App\Models\Product' ,'sub_category_id','id')->select(['id','sub_category_id','name','slug','price','sale_price'])->where('status',1);

    }
}
