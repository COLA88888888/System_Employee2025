<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportPayroll;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
     public function index(Request $request) {

        $status = $request->get('status');
        $search = $request->get('search');
        $perpage = $request->get('perpage', 10);
        $pay_month = $request->get('pay_month');

        $payroll = Payroll::join('employees','payrolls.employee_id','=','employees.id')
        ->join('positions','payrolls.position_id','=','positions.id')
        ->join('departments','payrolls.department_id','=','departments.id')
        ->select('payrolls.*','employees.fname as employee_fname','positions.name as position_name','departments.name as department_name')
        ->orderBy('payrolls.id','desc');

        if (!empty($status) && $status !== 'all') {
            switch ($status) {
                case 'paid':
                    $payroll->whereIn('payrolls.status', ['paid', 'ຈ່າຍແລ້ວ']);
                    break;
                case 'paid_yet':
                    $payroll->whereIn('payrolls.status', ['paid_yet', 'ຍັງບໍ່ທັນຈ່າຍ']);
                    break;
                default:
                    $payroll->where('payrolls.status', $status);
                    break;
            }
        }

        if (!empty($search)) {
            $payroll->where('employees.fname', 'like', "%{$search}%");
        }

        if ($pay_month) {
            // support passing YYYY-MM (month) or full date
            if (preg_match('/^\d{4}-\d{2}$/', $pay_month)) {
                [$y, $m] = explode('-', $pay_month);
                $payroll->whereYear('payrolls.pay_month', $y)->whereMonth('payrolls.pay_month', $m);
            } else {
                $payroll->whereDate('payrolls.pay_month', '=', $pay_month);
            }
        }

        // Handle perpage parameter - if "all", return all records; otherwise use pagination
        if ($perpage === 'all' || $perpage == -1) {
            $payroll = $payroll->get()->toArray();
        } else {
            $perpage = intval($perpage) ?: 15;  // Default to 15 if invalid
            $payroll = $payroll->paginate($perpage)->toArray();
        }

        return \response()->json($payroll, 200);
    }

    public function add(Request $request) {

        try {
            // Parse the date correctly
            $pay_month = Carbon::parse($request->pay_month)->format('Y-m-d');

            $payroll = new Payroll();
            $payroll->employee_id = $request->employee_id;  
            $payroll->department_id = $request->department_id;  
            $payroll->position_id = $request->position_id;  
            $payroll->pay_month = $pay_month;  
            $payroll->salary = $request->salary;  
            $payroll->bonus = $request->bonus;  
            $payroll->del_salary = $request->del_salary;  
            $payroll->amount = $request->amount;  
            $payroll->pay_method = $request->pay_method;  
            $payroll->status = $request->status;  
            $payroll->save();

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

        {
            $payroll = Payroll::find($id);
            return response()->json($payroll, 200);
        } 
    }

    public function update(Request $request, $id) {
        
        try {
            $payroll = Payroll::find($id);
            $payroll->employee_id = $request->employee_id; 
            $payroll->department_id = $request->department_id;  
            $payroll->position_id = $request->position_id;  
            $payroll->pay_month = $request->pay_month;  
            $payroll->salary = $request->salary;  
            $payroll->bonus = $request->bonus;  
            $payroll->del_salary = $request->del_salary;  
            $payroll->amount = $request->amount;  
            $payroll->pay_method = $request->pay_method;  
            $payroll->status = $request->status; 
            $payroll->save();

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
            $payroll = Payroll::find($id);
            $payroll->delete();

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

    public function updateStatus(Request $request, $id) {
        $payroll = Payroll::find($id);
        $payroll->status = $request->status;
        $payroll->save();

        return response()->json([
            'success' => true,
            'message' => 'ອັບເດດສະຖານະສຳເລັດ!',
            'status' => $payroll->status
        ], 200);
    }

    public function export () {
        try{
            return Excel::download(new ExportPayroll,'Payroll.xlsx');
        }
        catch (\Exception $e) {
            return \response()->json([
                'success' => false,
                'message' => "Error exproting payroll:" . $e->getMessage(),
            ],500);
        }
    }

public function printSlip($id)
{
    $data = Payroll::join('employees','payrolls.employee_id','=','employees.id')
        ->join('departments','payrolls.department_id','=','departments.id')
        ->join('positions','payrolls.position_id','=','positions.id')
        ->select(
            'payrolls.*',
            'employees.fname as employee_name',
            'departments.name as department_name',
            'positions.pos_name as position_name'
        )
        ->where('payrolls.id', $id)
        ->first();

    return response()->json($data, 200);
}

public function printView($id)
{
    $data = Payroll::join('employees','payrolls.employee_id','=','employees.id')
        ->join('departments','payrolls.department_id','=','departments.id')
        ->join('positions','payrolls.position_id','=','positions.id')
        ->select(
            'payrolls.*',
            'employees.fname as employee_name',
            'departments.name as department_name',
            'positions.pos_name as position_name'
        )
        ->where('payrolls.id', $id)
        ->first();

    return view('Bill_payroll', ['data' => $data]);
}


}