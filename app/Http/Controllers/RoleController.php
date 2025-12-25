<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index() {
        return response()->json(Role::with('users:id,name,email,role_id')->get());
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array'
        ]);
        
        $role = Role::create($validated);
        return response()->json(['message' => 'Role created', 'role' => $role]);
    }

    public function update(Request $request, $id) {
        $role = Role::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'permissions' => 'nullable|array'
        ]);

        // ຮັບປະກັນວ່າຖ້າບໍ່ມີການສົ່ງ permissions ມາ ໃຫ້ມັນເປັນ array ຫວ່າງ
        if (!$request->has('permissions')) {
            $validated['permissions'] = [];
        }

        $role->update($validated);
        
        return response()->json([
            'message' => 'ບັນທຶກສິດສຳເລັດ', 
            'role' => $role
        ]);
    }

    public function destroy($id) {
        Role::destroy($id);
        return response()->json(['message' => 'Role deleted']);
    }
}
