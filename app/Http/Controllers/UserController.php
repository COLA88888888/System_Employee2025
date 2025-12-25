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
        
        // Find default role 'employee'
        $role = \App\Models\Role::where('name', 'employee')->orWhere('name', 'Employee')->first();
        if ($role) {
            $user->role = $role->name;
            $user->role_id = $role->id;
        } else {
             $user->role = 'employee';
        }
        
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

    $credentials = [
        'email' => $request->email,
        'password' => $request->password
    ];

    // --- ສ່ວນທີ່ເພີ່ມເຂົ້າໄປ ---
    // ຖ້າກົດ Remember Me ໃຫ້ຕັ້ງເວລາ Token ໃຫ້ດົນຂຶ້ນ
    if($request->remember) {
        JWTAuth::factory()->setTTL(24 * 60 * 3); // 3 ມື້
    } else {
        JWTAuth::factory()->setTTL(60); // 1 ຊົ່ວໂມງ (ຫຼືຕາມຄ່າ default)
    }

    // ພະຍາຍາມ Login ແລະ ສ້າງ Token
    if (!$token = JWTAuth::attempt($credentials)) {
        return response()->json([
            'success' => false,
            'message' => 'ອີເມວ໌ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ',
        ], 401);
    }
    // -----------------------

    $user = Auth::user();
    
    // Self-healing: If role_id is missing but role name exists, try to link it
    if (is_null($user->role_id) && !empty($user->role)) {
        // Try exact match
        $role = \App\Models\Role::where('name', $user->role)->first();
        
        // Try case-insensitive match
        if (!$role) {
             // Get all roles and check with php to avoid SQL compatibility issues with different DBs
             $roles = \App\Models\Role::all();
             foreach ($roles as $r) {
                 if (strtolower($r->name) === strtolower($user->role)) {
                     $role = $r;
                     break;
                 }
             }
        }

        // Fallback to 'Employee' or 'employee' if still not found
        if (!$role) {
             $role = \App\Models\Role::where('name', 'Employee')->orWhere('name', 'employee')->first();
        }

        if ($role) {
            $user->role_id = $role->id;
            // Update role name to match the DB exactly
            $user->role = $role->name; 
            $user->save();
        }
    }

    // Reload user with new relationship
    $user = User::with('role_relation')->find($user->id);

    return response()->json([
        'success' => true,
        'message' => 'User logged in successfully',
        'token' => $token,
        'user' => $user
    ]);
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
        $user = User::with('role_relation')->get();
        return \response()->json($user, 200);
    }

        public function add(Request $request) {

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            
            // Logic for Role Assignment
            if ($request->filled('role_id')) {
                 $role = \App\Models\Role::find($request->role_id);
                 $user->role = $role ? $role->name : 'employee';
                 $user->role_id = $request->role_id;
            } else {
                // If no role_id provided, look up by legacy 'role' field or default to employee
                $roleName = $request->role ?? 'employee';
                $role = \App\Models\Role::where('name', $roleName)->orWhere('name', ucfirst($roleName))->first();
                if ($role) {
                    $user->role = $role->name;
                    $user->role_id = $role->id;
                } else {
                    $user->role = $roleName;
                }
            }
            
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
            $user = User::with('role_relation')->find($id);
            
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
            
            // Logic for Role Assignment
            if ($request->filled('role_id')) {
                 $role = \App\Models\Role::find($request->role_id);
                 $user->role = $role ? $role->name : 'employee';
                 $user->role_id = $request->role_id;
            } else {
                // If no role_id provided, look up by legacy 'role' field or default to employee
                $roleName = $request->role ?? 'employee';
                $role = \App\Models\Role::where('name', $roleName)->orWhere('name', ucfirst($roleName))->first();
                if ($role) {
                    $user->role = $role->name;
                    $user->role_id = $role->id;
                } else {
                    $user->role = $roleName;
                    $user->role_id = null; // Clear if not found
                }
            }

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