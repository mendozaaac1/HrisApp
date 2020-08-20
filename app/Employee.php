<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    protected $fillable=[
      'name',
      'department_id',
      'employee_type_id',
      'employment_type_id',
      'email'
    ];

    public function department(){
		  return $this->belongsTo('App\Department','department_id');
    }

    public function employeeType(){
		  return $this->belongsTo('App\EmployeeType','employee_type_id');
    }


    public function employmentType(){
		  return $this->belongsTo('App\EmploymentType','employment_type_id');
    }

    public function schedules(){
		  return $this->hasMany('App\EmployeeSchedules','employee_id');
    }

    


}
