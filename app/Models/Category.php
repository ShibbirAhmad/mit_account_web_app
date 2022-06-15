<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     public  function subCategory(){

        return $this->hasMany('App\Models\SubCategory','category_id')->where('status',1)->select(['id','name','category_id','status','show_homepage']);
    }
    public  function subSubCategory(){

        return $this->hasMany('App\Models\SubSubCategory','category_id')->where('status',1);
    }

    public  function product(){

        return $this->hasMany('App\Models\Product','category_id');

    }
}
