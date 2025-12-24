<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index() {

        $search = \Request::get('search');
        $perpage = \Request::get('perpage');
        $start_date = \Request::get('start_date');
        $end_date = \Request::get('end_date');

        $attendance = Attendance::join('employees', 'attendances.employee_id', '=', 'employees.id')
        ->select('attendances.*', 'employees.fname as employee_fname')
        ->orderBy('attendances.id', 'desc')
        ->when($search, function ($query, $search) {
            return $query->where('employees.fname', 'like', '%' . $search . '%');   // ຄົ້ນຫາຊື່ພະນັກງານ
        });

        $attendance = $attendance->paginate($perpage)
            -> toArray();

        return \response()->json($attendance, 200);
    }

    public function add(Request $request) {

        try {
            $attendance = new Attendance();
            $attendance->date_here = $request->date_here; 
            $attendance->employee_id = $request->employee_id;  
            $attendance->check_in = $request->check_in;  
            $attendance->break_start = $request->break_start;  
            $attendance->break_end = $request->break_end;  
            $attendance->check_out = $request->check_out;   
            $attendance->hour = $request->hour;  
            $attendance->save();

            $success = true;
            $message = 'ບັນທຶກຂໍ້ມູນສຳເລັດ';

        } catch (\Illuminate\Database\QueryException $e) {
            $success = false;
            $message = $e->getMessage();     
        }

        $response = [
            'success' => $success,
            'message' => $message,
        ];

        return response()->json($response, 201);
    }

    public function edit($id) {
        // edit attendance
        try {
            $attendance = Attendance::find($id);
            
            $success = true;
            $message = 'ດຶງຂໍ້ມູນສຳເລັດ';
        }catch (\Illuminate\Database\QueryException $e) {

            $success = false;
            $message =  $e->getMessage();
        }
        $response = [
            'success' => $success,
            'message' => $message,
            'attendance' => $attendance
        ];
        return response()->json($response, 200);
    }

    public function update(Request $request, $id) {
        
        try {
            $attendance = Attendance::find($id);
            $attendance->date_here = $request->date_here; 
            $attendance->employee_id = $request->employee_id;  
            $attendance->check_in = $request->check_in;  
            $attendance->break_start = $request->break_start;  
            $attendance->break_end = $request->break_end;  
            $attendance->check_out = $request->check_out;  
            $attendance->hour = $request->hour; 
            $attendance->save();
            $success = true;
            $message = 'ແກ້ໄຂຂໍ້ມູນສຳເລັດ';

        } catch (\Illuminate\Database\QueryException $e) {
            $success = false;
            $message = $e->getMessage();     
        }

        $response = [
            'success' => $success,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    public function delete($id) {

        try {
            $attendance = Attendance::find($id);
            $attendance->delete();

            $success = true;
            $message = 'ລົບຂໍ້ມູນສຳເລັດ';

        } catch (\Illuminate\Database\QueryException $e) {
            $success = false;
            $message = $e->getMessage();     
        }

        $response = [
            'success' => $success,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }
}