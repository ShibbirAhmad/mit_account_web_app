<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoostAgencyResellerAdAccount extends Model
{
       public  function transactions(){
          return $this->hasMany('App\Models\BoostAgencyResellerDollarTransaction','boost_agency_reseller_ad_account_id')->orderBy('id','desc') ;
     }
}
