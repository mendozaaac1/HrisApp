<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Employee;
use App\Department;
use App\EmployeeType;
use App\EmploymentType;
use App\EmployeeSchedules;
use App\ScheduleLogs;

use Carbon\Carbon;

use App\Imports\LogsImport;
use Excel;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return Employee::latest()->with(['department','employeeType','employmentType','schedules'])->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string',
            'department' => 'required',
            'employeeType' => 'required',
            'employmentType' => 'required',
            'email' => 'required|email|unique:employees',
        ]);


        return Employee::create([
            'name' => $request->name,
            'department_id' => $request->department,
            'employee_type_id' => $request->employeeType,
            'employment_type_id' => $request->employmentType,
            'email' => $request->email,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|unique:employees,email,'.$employee->id,
            'department' => 'required',
            'employeeType' => 'required',
            'employmentType' => 'required',
            
        ]);
        $employee->update([
            'name'=> $request->name,
            'email'=> $request->email,
            'department_id'=> $request->department,
            'employee_type_id'=> $request->employeeType,
            'employment_type_id'=> $request->employmentType,

        ]);
        
        return ['message' => 'update employee: '.$id];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->schedules()->delete();
        $employee->delete();
        return ['message' => 'Employee: '.$id.' Deleted'];
    }



    public function departments(){
        return Department::all();

    }

    public function employeeTypes(){
        return EmployeeType::all();
    }

    public function employmentTypes(){
        return EmploymentType::all();
    }

    public function search() {
        if($search = \Request::get('q')) {
           $employees = Employee::where(function($query) use ($search) {
                                    $query->where('name','LIKE', "%$search%")
                                            ->orWhere('email','LIKE',"%$search%");
                                           
                                })
                                ->orWhereHas('department',function($query) use ($search){
                                    $query->where('name', 'LIKE',"%$search%");
                                } )
                                ->orWhereHas('employeeType',function($query) use ($search){
                                    $query->where('type', 'LIKE', "%$search%");
                                } )
                                ->orWhereHas('employmentType',function($query) use ($search){
                                    $query->where('type', 'LIKE', "%$search%");
                                } )
                                 ->with(['department','employeeType','employmentType','schedules'])
                                ->paginate(10);
        } else {
            $employees = Employee::latest()->with(['department','employeeType','employmentType','schedules'])->paginate(10);
        }

        return $employees;
    }
    

    public function inputSchedules(Request $request)
    {
        if ($inputs=$request->inputs) {
            foreach ($inputs as $key=>$input) {
                EmployeeSchedules::create([
                    'employee_id' => $request->employee_id,
                    'day' => $input['day'],
                    'from' => date("H:i",strtotime($input['from'])),
                    'to' => date("H:i",strtotime($input['to'])) ,
                    'schedule_name' =>$input['schedule_name'],   
                ]);
            }
        }
    }

    public function deleteSchedule()
    {
        $id     = \Request::get('id');
        $emp_id = \Request::get('emp_id');
        EmployeeSchedules::where('id',$id)->delete();

        $employee = Employee::find($emp_id);

        return $employee->schedules;
    }

    public function loadEmployeeLogs()
    {
        $emp_id = \Request::get('emp_id'); 
        return response()->json([
            'employee'  => Employee::where('id',$emp_id)->with(['department','schedules'])->first(),
            'logs'      => ScheduleLogs::where('employee_id',$emp_id)->with('employee','schedule')->paginate(10),
        ]);
    }

    public function getEmployeeLogsPage() {
        $emp_id = \Request::get('emp_id');
        return ScheduleLogs::where('employee_id',$emp_id)->with('employee','schedule')->paginate(10);
    }

    public function importExcel(Request $request)
    {
        try {
            Excel::import(new LogsImport($request->employee_id), request()->file('excel'));
            return 'success';   
        } catch (Exception $e) {
            return 'error';
        }
    }

    public function deleteLoggedSchedule()
    {
        $id = \Request::get('id');
        ScheduleLogs::where('id',$id)->delete();
    }
}
