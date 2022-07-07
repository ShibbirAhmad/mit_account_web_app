<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{


      protected $fillable = ['id', 'name', 'phone', 'email', 'share_value', 'share_qty', 'total_amount', 'total_paid_amount', 'total_refund_amount', 'address', 'status', 'created_at', 'updated_at'] ;


      public function payments(){

            return $this->hasMany('App\Models\DirectorPayment','director_id')->orderBy('id','desc') ;
      }


      public function refunds(){

            return $this->hasMany('App\Models\DirectorRefund','director_id')->orderBy('id','desc') ;
      }



}
