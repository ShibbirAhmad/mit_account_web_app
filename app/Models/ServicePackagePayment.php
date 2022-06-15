<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePackagePayment extends Model
{


    protected $fillable = ['id', 'client_id', 'service_package_id', 'amount', 'is_regular', 'payment_method_id', 'trx_id', 'comment', 'created_by', 'created_at', 'updated_at'] ;


    public function  created_by(){

         return $this->belongsTo('App\Models\Admin','created_by') ;
    }



}
