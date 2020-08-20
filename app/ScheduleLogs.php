<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleLogs extends Model
{
    
	protected $fillable = ['employee_id','schedule_id','day','date','time_in','time_out','total_hours'];

    public function employee(){
		return $this->belongsTo('App\Employee','employee_id');
    }

    public function schedule(){
		return $this->belongsTo('App\EmployeeSchedules','schedule_id');
    }
}
