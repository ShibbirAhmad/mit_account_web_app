<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentLoanTransaction extends Model
{


    public function balanceFrom(){

         return $this->belongsTo('App\Models\Balance','balance_from')->select(['id','name','department']) ;
     }



    public function balanceTo(){

         return $this->belongsTo('App\Models\Balance','balance_to')->select(['id','name','department']) ;
     }

    public function transferby(){

         return $this->belongsTo('App\Models\Admin','transfer_by')->select(['id','name']) ;
     }


}
