<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreakType;

class BreakTypeController extends Controller
{
    public function index() {

        $break_type = BreakType::all();
        return \response()->json($break_type, 200);
    }

    public function add(Request $request) {

        try {
            $break_type = new BreakType();
            $break_type->name = $request->name;  // ຮັບຄ່າຊື່ຂໍ້ມູນທີ່ສົ່ງມາ
            $break_type->save();

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
            $break_type = BreakType::find($id);
            return response()->json($break_type, 200);
        } 
    }

    public function update(Request $request, $id) {
        
        try {
            $break_type = BreakType::find($id);
            $break_type->name = $request->name;  // ຮັບຄ່າຊື່ຂໍ້ມູນທີ່ສົ່ງມາ
            $break_type->save();

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
            $break_type = BreakType::find($id);
            $break_type->delete();

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
