<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BoostAgencyReseller extends Model
{


    protected $fillable = [ 'id', 'boost_agency_id', 'name', 'company_name', 'phone', 'address', 'dollar_rate', 'status', 'created_at', 'updated_at'] ;


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
