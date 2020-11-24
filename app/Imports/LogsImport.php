<?php

namespace App\Imports;

use App\ScheduleLogs;
use App\EmployeeSchedules;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Carbon\Carbon;

class LogsImport implements ToModel, WithHeadingRow
{
    private $employee_id;
    
    public function __construct($employee_id)
    {
        $this->employee_id = $employee_id;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $time_in = Carbon::parse($row['date'])->format('Y-m-d') .' '.  Carbon::createFromTimestamp(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($row['time_in']))->format('H:i:s');
        $time_out = Carbon::parse($row['date'])->format('Y-m-d') .' '.  Carbon::createFromTimestamp(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($row['time_out']))->format('H:i:s');
        $schedule_id = $this->getScheduleId($time_in, $time_out, strtolower($row['day']), Carbon::parse($row['date'])->format('Y-m-d'));

        return new ScheduleLogs([
            'employee_id'   => $this->employee_id,
            'schedule_id'   => $schedule_id,
            'day'           => strtolower($row['day']),
            'date'          => Carbon::parse($row['date'])->format('Y-m-d'),
            'time_in'       => Carbon::createFromTimestamp(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($row['time_in']))
                                    ->format('H:i:s'),
            'time_out'      => Carbon::createFromTimestamp(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($row['time_out']))
                                    ->format('H:i:s'),
            'total_hours'   => $this->getTotalHours($time_in, $time_out),
        ]);
    }

    public function getTotalHours($time_in, $time_out)
    {
        $start = Carbon::parse($time_in);
        $finish = Carbon::parse($time_out);
        $totalDuration = $finish->diffInSeconds($start);
        return gmdate('H:i:s', $totalDuration);
    }

    public function getScheduleId($time_in, $time_out, $day, $date)
    {
        $employee_schedules = EmployeeSchedules::where('employee_id',$this->employee_id)->where('day',$day)->get();
        $start = Carbon::parse($time_in);
        $finish = Carbon::parse($time_out);

        foreach ($employee_schedules as $key => $schedule) {
            

            if($schedule->day == $day) {
                $check_start = $start->between(Carbon::parse($date.' '.$schedule->from)->subMinute(), Carbon::parse($date.' '.$schedule->to)->addMinute());
                $check_before_start = $start->between(Carbon::parse($date.' '.$schedule->from)->subMinute(20), Carbon::parse($date.' '.$schedule->to)->addMinute());
                $employee_schedule_id = $schedule->id;
                if($check_start === true || $check_before_start === true ) {
                    return $employee_schedule_id;
                
                }
            }
        }
    }
}
