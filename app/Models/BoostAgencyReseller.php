<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use DateTimeInterface;

class BoostAgencyReseller extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'boost_agency_resellers';
    protected $fillable = ['id', 'name', 'company_name', 'phone', 'address', 'password', 'status', 'created_at', 'updated_at'];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }




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
