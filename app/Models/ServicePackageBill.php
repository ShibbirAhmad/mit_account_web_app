<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePackageBill extends Model
{
      protected $fillable = ['id', 'service_package_id', 'client_id', 'service_id', 'month', 'year', 'start_date', 'end_date', 'amount', 'paid', 'status', 'payment_date', 'created_by', 'created_at', 'updated_at'] ;
}
