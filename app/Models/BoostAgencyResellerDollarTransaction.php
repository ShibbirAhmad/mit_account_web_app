<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoostAgencyResellerDollarTransaction extends Model
{
     public  function account(){
         return $this->belongsTo('App\Models\BoostAgencyResellerAdAccount','boost_agency_reseller_ad_account_id') ;
     }
}
