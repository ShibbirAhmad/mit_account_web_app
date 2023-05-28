<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoostAgencyPayment extends Model
{
      protected $guarded = [] ;

      public function balance(){
            return $this->belongsTo('App\Models\Balance','paid_by') ;
      }
}
