<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BoostAgencyReseller extends Model
{
    public  function transactions(){

          return $this->hasMany('App\Models\BoostAgencyResellerDollarTransaction','boost_agency_reseller_id') ;
     }

    public  function payments(){

            return $this->hasMany('App\Models\BoostAgencyResellerPayment','boost_agency_reseller_id') ;
        }



    public  function accounts(){

        return $this->hasMany('App\Models\BoostAgencyResellerAdAccount','boost_agency_reseller_id') ;
    }





}
