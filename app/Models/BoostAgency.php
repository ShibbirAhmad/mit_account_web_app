<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoostAgency extends Model
{

      public function resellers(){

           return $this->hasMany('App\Models\BoostAgencyReseller','boost_agency_id') ;
      }


      public  function payments(){

            return $this->hasMany('App\Models\BoostAgencyPayment','boost_agency_id') ;
        }


      protected $guarded = [] ;




}
