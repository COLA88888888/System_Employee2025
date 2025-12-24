<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller
{
    public function index() {

        $positions = Position::all();
        return \response()->json($positions, 200);
    }

    public function add(Request $request) {

        try {
            $position = new Position();
            $position->name = $request->name; 
            $position->save();

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
        // edit products
          {
            $position = Position::find($id);
            return response()->json($position, 200);
        } 
    }

    public function update(Request $request, $id) {
        
        try {
            $position = Position::find($id);
            $position->name = $request->name;  // ຮັບຄ່າຊື່ຂໍ້ມູນທີ່ສົ່ງມາ
            $position->save();

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
            $position = Position::find($id);
            $position->delete();

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
