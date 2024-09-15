<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class BoostAgency extends Model
{
      protected function serializeDate(DateTimeInterface $date)
      {
            return $date->format('Y-m-d H:i:s');
      }

      public function resellers()
      {

            return $this->hasMany('App\Models\BoostAgencyReseller', 'boost_agency_id');
      }


      public  function payments()
      {

            return $this->hasMany('App\Models\BoostAgencyPayment', 'boost_agency_id');
      }


      protected $guarded = [];
}
