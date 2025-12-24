<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('app');
// });

use App\Http\Controllers\PayrollController;

// Server-side payroll print (renders Blade view)
Route::get('/payroll/print/{id}', [PayrollController::class, 'printView']);

Route::get('{any}', function () {
    return view('app');
})->where('any','.*');