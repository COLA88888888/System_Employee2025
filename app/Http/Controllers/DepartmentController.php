<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index() {

        $departments = Department::all();
        return \response()->json($departments, 200);
    }

    public function add(Request $request) {

        try {
            $department = new Department();
            $department->name = $request->name;  // ຮັບຄ່າຊື່ຂໍ້ມູນທີ່ສົ່ງມາ
            $department->save();

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
            $department = Department::find($id);
            return response()->json($department, 200);
        } 
    }

    public function update(Request $request, $id) {
        
        try {
            $department = Department::find($id);
            $department->name = $request->name;  // ຮັບຄ່າຊື່ຂໍ້ມູນທີ່ສົ່ງມາ
            $department->save();

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
            $department = Department::find($id);
            $department->delete();

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
