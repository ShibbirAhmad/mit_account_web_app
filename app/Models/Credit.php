<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    public function admin(){
        return $this->belongsTo('App\Models\Admin','insert_admin_id');
    }

   public function balance(){
         return $this->belongsTo('App\Models\Balance','credit_in','id');
    }  
    
    public function service(){
        return $this->belongsTo('App\Models\Service','service_id');
    }


   protected $fillable = ['id', 'date', 'department', 'service_id', 'purpose', 'credit_in', 'amount', 'comment', 'insert_admin_id', 'created_at', 'updated_at'] ;

}
