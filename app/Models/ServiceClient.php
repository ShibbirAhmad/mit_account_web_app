<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceClient extends Model
{
     protected $fillable = ['id', 'name', 'company_name', 'phone', 'email', 'total_amount', 'total_paid_amount', 'address', 'status', 'created_at', 'updated_at'];
}
