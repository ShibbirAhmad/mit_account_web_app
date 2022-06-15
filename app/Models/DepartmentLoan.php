<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentLoan extends Model
{
    //

    protected $table='department_loans';
    protected $fillable =[ 'name', 'given_amount', 'taken_amount'];

    public function transactions(){

          return $this->hasMany('App\Models\DepartmentLoanTransaction','department_loan_id')->orderBy('id','desc') ;
    }

}
