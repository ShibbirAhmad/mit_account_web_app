<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debit extends Model
{

    public function admin(){
        return $this->belongsTo('App\Models\Admin','insert_admin_id');
    }

    public function purpose(){
         return $this->belongsTo('App\Models\Account_purpose','purpose');
    }

     public function balance(){

         return $this->belongsTo('App\Models\Balance','debit_from','id');
    }


    protected $fillable = [ 'id', 'date', 'department', 'purpose', 'is_expense', 'is_fund_transfer', 'debit_from', 'amount', 'comment', 'signature', 'insert_admin_id', 'created_at', 'updated_at'] ;

     
}
