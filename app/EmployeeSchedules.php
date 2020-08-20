<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmployeeSchedules extends Model
{

	protected $fillable = ["employee_id","day","from","to","schedule_name"];

	protected $appends = ["from_formatted","to_formatted"];

    public function employee(){
		return $this->belongsTo('App\Employee','employee_id');
    }

    public function getFromFormattedAttribute(){


    	return Carbon::now()->format('Y-m-d').' '.$this->from;
    	// return date("g:i A",strtotime($this->from));
    }

     public function getToFormattedAttribute(){
    	
    	 return Carbon::now()->format('Y-m-d').' '.$this->to;
    	// return date("g:i A",strtotime($this->to));
    }
}
