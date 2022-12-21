<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Loaner extends Model
{
    public function loan(){
        return $this->hasOne('App\Models\Loan','loaner_id');
    }


    protected $fillable=[ 'id', 'name', 'mobile_no', 'address', 'created_at', 'updated_at'] ;

}
