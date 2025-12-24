<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'department_id',
        'position_id',
        'pay_month',
        'salary',  
        'bonus',
        'del_salary',
        'amount',
        'pay_method',
        'status',
    ];
}
