<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentLoanNetwork extends Model
{
    protected $table ='department_loan_networks';
    protected $fillable=['department_from', 'department_to'];
}
