<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\BreakTypeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RoleController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/logout', [UserController::class, 'logout']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/department', [DepartmentController::class, 'index']);
    Route::post('/department/add', [DepartmentController::class, 'add']);
    Route::get('/department/edit/{id}', [DepartmentController::class, 'edit']);  
    Route::post('/department/update/{id}', [DepartmentController::class, 'update']);
    Route::delete('/department/delete/{id}', [DepartmentController::class, 'delete']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user/add', [UserController::class, 'add']);
    Route::get('/user/edit/{id}', [UserController::class, 'edit']);  
    Route::post('/user/update/{id}', [UserController::class, 'update']);
    Route::delete('/user/delete/{id}', [UserController::class, 'delete']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/position', [PositionController::class, 'index']);
    Route::post('/position/add', [PositionController::class, 'add']); 
    Route::get('/position/edit/{id}', [PositionController::class, 'edit']);  
    Route::post('/position/update/{id}', [PositionController::class, 'update']);
    Route::delete('/position/delete/{id}', [PositionController::class, 'delete']); 
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/employee', [EmployeeController::class, 'index']); 
    Route::post('/employee/add', [EmployeeController::class, 'add']);
    Route::get('/employee/edit/{id}', [EmployeeController::class, 'edit']);   
    Route::post('/employee/update/{id}', [EmployeeController::class, 'update']); 
    Route::delete('/employee/delete/{id}', [EmployeeController::class, 'delete']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/attendance', [AttendanceController::class, 'index']);    
    Route::post('/attendance/add', [AttendanceController::class, 'add']);
    Route::get('/attendance/edit/{id}', [AttendanceController::class, 'edit']);
    Route::post('/attendance/update/{id}', [AttendanceController::class, 'update']);
    Route::delete('/attendance/delete/{id}', [AttendanceController::class, 'delete']); 
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/break_type', [BreakTypeController::class, 'index']);
    Route::post('/break_type/add', [BreakTypeController::class, 'add']);
    Route::get('/break_type/edit/{id}', [BreakTypeController::class, 'edit']);  
    Route::post('/break_type/update/{id}', [BreakTypeController::class, 'update']); 
    Route::delete('/break_type/delete/{id}', [BreakTypeController::class, 'delete']); 
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/leave', [LeaveController::class, 'index']);
    Route::post('/leave/add', [LeaveController::class, 'add']);
    // Route::get('/leave/edit/{id}', [LeaveController::class, 'edit']);  
    Route::post('/leave/update/{id}', [LeaveController::class, 'update']); 
    // Route::delete('/leave/delete/{id}', [LeaveController::class, 'delete']); 
    Route::post('/leave/status/{id}', [LeaveController::class, 'updateStatus']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/payroll', [PayrollController::class, 'index']);
    Route::post('/payroll/add', [PayrollController::class, 'add']);
    Route::get('/payroll/edit/{id}', [PayrollController::class, 'edit']);  
    Route::post('/payroll/update/{id}', [PayrollController::class, 'update']); 
    Route::delete('/payroll/delete/{id}', [PayrollController::class, 'delete']); 
    Route::post('/payroll/status/{id}', [PayrollController::class, 'updateStatus']);
    Route::get('/payroll/export', [PayrollController::class, 'export']);
    Route::get('/payroll/print/{id}', [PayrollController::class, 'printSlip']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/company', [CompanyController::class, 'index']);
    Route::post('/company', [CompanyController::class, 'store']);
    Route::put('/company/{company}', [CompanyController::class, 'update']);
    Route::delete('/company/{company}', [CompanyController::class, 'destroy']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::post('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
});