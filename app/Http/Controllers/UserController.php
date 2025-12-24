<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
    public function register (Request $request) {
        
        $user = new User();
        $user->name = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = 'employee'; // Default role for registered users
        $user->save();

        $response = [
            'message' => 'User register successfully',
            'user' => $user,
        ];

        return response ()->json($response, 201);
    }
    
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user_login = [
            'email' => $request->email,
            'password' => $request->password
        ];

        
        // check remember me
        if($request->remember) {
            // set time
            JWTAuth::factory()->setTTL(24*60*3); 
            // JWTAuth::factory()->setTTL(1); 
        }

        $token = JWTAuth::attempt($user_login);
        $user = Auth::user();

        if($token) {
            return response()->json([
                'success' => true,
                'message' => 'User logged in successfully',
                'token' => $token,
                'user' => $user
            ]);
        }

        else {
            return response()->json([
                'success' => false,
                'message' => 'ອີເມວ໌ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ',
            ], 401);
        }

    }

    public function logout()
    {
        $token = JWTAuth::getToken();

        if ($token) {
            JWTAuth::invalidate($token);
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Token not found '
            ], 401);
        }
    }

    // Get all users
    public function index()
    {
        $user = User::all();
        return \response()->json($user, 200);
    }

        public function add(Request $request) {

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->role = $request->role; // Default role for registered users
            $user->save();

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

    // Get a specific user
    public function edit($id)
    {
            try {
            $user = User::find($id);
            
            $success = true;
            $message = 'ດຶງຂໍ້ມູນສຳເລັດ';
        }catch (\Illuminate\Database\QueryException $e) {

            $success = false;
            $message =  $e->getMessage();
        }
        $response = [
            'success' => $success,
            'message' => $message,
            'user' => $user
        ];
        return response()->json($response, 200);
    }

    // Update a user
    public function update(Request $request, $id) {
        
        try {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role; // Default role for registered users
            $user->save();

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
    // Delete a user
    public function delete($id)
    {
        try {
            $user = User::find($id);
            $user->delete();

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