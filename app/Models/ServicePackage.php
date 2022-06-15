<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    protected $fillable = ['id', 'client_id', 'service_id', 'amount', 'paid', 'is_monthly', 'is_paid', 'status', 'status_note', 'created_by', 'created_at', 'updated_at'] ;



    public function  service(){

         return $this->belongsTo('App\Models\Service','service_id') ;
    }

    public function  client(){

         return $this->belongsTo('App\Models\ServiceClient','client_id') ;
    }


    public function  created_by(){

         return $this->belongsTo('App\Models\Admin','created_by') ;
    }


    public function  bills(){

         return $this->hasMany('App\Models\ServicePackageBill','service_package_id') ;
    }






}
