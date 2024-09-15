<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class BoostAgencyResellerAdAccount extends Model
{

     protected function serializeDate(DateTimeInterface $date)
     {
          return $date->format('Y-m-d H:i:s');
     }

     protected $guarded = [];

     public  function transactions()
     {
          return $this->hasMany('App\Models\BoostAgencyResellerDollarTransaction', 'boost_agency_reseller_ad_account_id')->orderBy('id', 'desc');
     }
}
