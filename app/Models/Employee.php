<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'fname',
        'department_id',
        'position_id',
        'lname',
        'gender',
        'dob',
        'tel',
        'email',
        'address',
        'hire_date',
        'status'
    ];
}
