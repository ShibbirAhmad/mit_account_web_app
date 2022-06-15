<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{


        public function  today_credit_balance(){

             return $this->hasMany("App\Models\Credit","credit_in","id")->where('date', '>=', Carbon::today()->startOfDay())->where('date', '<=', Carbon::today()->endOfDay())->orderBy('created_at','desc') ;
         }



        public function  total_credit_balance(){

               return $this->hasMany("App\Models\Credit","credit_in","id")->select(['credit_in','amount','comment','date'])->orderBy('created_at','desc')  ;

         }


        public function  today_debit_balance(){

             return $this->hasMany("App\Models\Debit","debit_from","id")->where('date', '>=', Carbon::today()->startOfDay())->where('date', '<=', Carbon::today()->endOfDay())->orderBy('created_at','desc') ;

         }



        public function  total_debit_balance(){

             return $this->hasMany("App\Models\Debit","debit_from","id")->select(['amount','debit_from','comment','date'])->orderBy('created_at','desc')  ;

         }





}
