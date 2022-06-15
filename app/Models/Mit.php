<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mit extends Model
{

      public function clients(){

           return $this->hasMany('App\Models\MitClient','mit_id') ;
      }

}
