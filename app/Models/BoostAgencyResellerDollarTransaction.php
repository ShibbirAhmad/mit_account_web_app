<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoostAgencyResellerDollarTransaction extends Model
{


     public  function account(){
         return $this->belongsTo('App\Models\BoostAgencyResellerAdAccount','boost_agency_reseller_ad_account_id') ;
     }

     public  function reseller(){
        return $this->belongsTo('App\Models\BoostAgencyReseller','boost_agency_reseller_id') ;
    }


     protected $fillable = ['id', 'boost_agency_reseller_id', 'boost_agency_reseller_ad_account_id', 'dollar', 'rate','supplier_rate', 'amount', 'created_at', 'updated_at'] ;




}
