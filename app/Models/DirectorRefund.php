<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectorRefund extends Model
{

    public function created_by(){
         return $this->belongsTo('App\Models\Admin','created_by')->select('id','name');
    }


    public function balance(){
         return $this->belongsTo('App\Models\Balance','balance_id');
    }

    protected $fillable = ['id', 'director_id', 'amount', 'balance_id', 'trx_id', 'comment', 'created_by', 'created_at', 'updated_at'] ;




}
