<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Notification;
use App\Models\Employee;

class LeaveController extends Controller
{
 public function index(Request $request)
    {
        $search = $request->get('search');
        $break_type_id = $request->get('break_type_id');
        $status = $request->get('status');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $perpage = \Request::get('perpage', 10);

        $leave = Leave::join('employees', 'leaves.employee_id', '=', 'employees.id')
            ->join('break_types', 'leaves.break_type_id', '=', 'break_types.id')
            ->select('leaves.*', 'employees.fname as employee_fname', 'break_types.name as break_type_name')
            ->orderBy('leaves.id', 'desc');

        
        if ($break_type_id && $break_type_id !== 'all') {
            $leave->where('leaves.break_type_id', $break_type_id);
        }

        // ກຳນົດສະຖານະ
       if (!empty($status) && $status !== 'all') {
            switch ($status) {
                case 'pending':
                    $leave->whereIn('leaves.status', ['pending', 'ລໍຖ້າອະນຸມັດ']);
                    break;
                case 'approved':
                    $leave->whereIn('leaves.status', ['approved', 'ອະນຸມັດແລ້ວ']);
                    break;
                case 'rejected':
                    $leave->whereIn('leaves.status', ['rejected', 'ປະຕິເສດ']);
                    break;
                default:
                    $leave->where('leaves.status', $status);
                    break;
            }
        }   


        // ຖ້າກຳນົດຊ່ວງວັນທີແລ້ວຢາກໃຫ້ສະແດງເທົ່າໃບທີ່ເປັນຊ່ວງນັ້ນແທ້ໆ
       if ($start_date && $end_date) {
            $leave->whereDate('leaves.start_date', '=', $start_date)
            ->whereDate('leaves.end_date', '=', $end_date);
        }


        // ຖ້າມີວັນດຽວ
        if ($start_date && !$end_date) {
            $leave->whereDate('leaves.start_date', '=', $start_date);
        }

        // ຄົ້ນຫາຊື່ພະນັກງານ
        if (!empty($search)) {
            $leave->where('employees.fname', 'like', "%{$search}%");
        }

        // Handle perpage parameter - if "all", return all records; otherwise use pagination
        if ($perpage === 'all' || $perpage == -1) {
            $leave = $leave->get()->toArray();
        } else {
            $perpage = intval($perpage) ?: 15;  // Default to 15 if invalid
            $leave = $leave->paginate($perpage)->toArray();
        }

        return response()->json($leave, 200);
    }


    public function add(Request $request) {

        try {
            $leave = new Leave();
            $leave->start_date = $request->start_date;
            $leave->employee_id = $request->employee_id;
            $leave->end_date = $request->end_date;
            $leave->break_type_id = $request->break_type_id;
            $leave->reason = $request->reason;
            $leave->status = $request->status;
            $leave->save();

            // Notify admins about new leave request
            $emp = Employee::find($leave->employee_id);
            Notification::send(
                'ຄຳຮ້ອງຂໍລາພັກໃໝ່',
                "ພະນັກງານ: {$emp->fname} ໄດ້ສົ່ງຄຳຮ້ອງຂໍລາພັກ",
                'leave',
                null, 
                '/leave'
            );

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

    // public function edit($id) {
        
    //     try {
    //         $leave = Leave::find($id);
            
    //         $success = true;
    //         $message = 'ດຶງຂໍ້ມູນສຳເລັດ';

    //     }catch (\Illuminate\Database\QueryException $e) {

    //         $success = false;
    //         $message =  $e->getMessage();
    //     }

    //     $response = [
    //         'success' => $success,
    //         'message' => $message,
    //         'leave' => $leave
    //     ];
    //     return response()->json($response, 200);
    // }

    public function update(Request $request, $id) {
        
        try {
            $leave = Leave::find($id);
            $leave->start_date = $request->start_date;
            $leave->employee_id = $request->employee_id;
            $leave->end_date = $request->end_date;
            $leave->break_type_id = $request->break_type_id;
            $leave->reason = $request->reason;
            $leave->status = $request->status;
            $leave->save();

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

    // public function delete($id) {

    //     try {
    //         $leave = Leave::find($id);
    //         $leave->delete();

    //         $success = true;
    //         $message = 'ລົບຂໍ້ມູນສຳເລັດ';

    //     } catch (\Illuminate\Database\QueryException $e) {
    //         $success = false;
    //         $message = $e->getMessage();     
    //     }

    //     $response = [
    //         'success' => $success,
    //         'message' => $message,
    //     ];

    //     return response()->json($response, 200);
    // }

    public function updateStatus($id, Request $request)
{
    try {
        $leave = Leave::find($id);

        if (!$leave) {
            return response()->json([
                'success' => false,
                'message' => 'ບໍ່ພົບຂໍ້ມູນການລາພັກນີ້'
            ], 404);
        }

        $leave->status = $request->status;
        $leave->save();

        // Notify about status update
        $emp = Employee::find($leave->employee_id);
        Notification::send(
            'ອັບເດດສະຖານະການລາພັກ',
            "ຄຳຮ້ອງຂໍລາພັກຂອງ {$emp->fname} ຖືກ {$leave->status}",
            'leave',
            null, 
            '/leave'
        );

        return response()->json([
            'success' => true,
            'message' => 'ອັບເດດສະຖານະສຳເລັດ!',
            'data' => $leave
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


}
