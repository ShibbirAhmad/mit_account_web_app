<?php

namespace App\Models;
use DateTimeInterface ;
use Illuminate\Database\Eloquent\Model;

class BoostAgencyPayment extends Model
{

      protected function serializeDate(DateTimeInterface $date)
{
    return $date->format('Y-m-d H:i:s');
}

      protected $guarded = [] ;

      public function balance(){
            return $this->belongsTo('App\Models\Balance','paid_by') ;
      }
}
