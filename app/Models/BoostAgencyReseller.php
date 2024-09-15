<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class BoostAgencyReseller extends Model
{

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    protected $fillable = ['id', 'name', 'company_name', 'phone', 'address', 'password', 'status', 'created_at', 'updated_at'];


    public  function transactions()
    {

        return $this->hasMany('App\Models\BoostAgencyResellerDollarTransaction', 'boost_agency_reseller_id');
    }

    public  function payments()
    {

        return $this->hasMany('App\Models\BoostAgencyResellerPayment', 'boost_agency_reseller_id');
    }



    public  function accounts()
    {

        return $this->hasMany('App\Models\BoostAgencyResellerAdAccount', 'boost_agency_reseller_id');
    }
}
