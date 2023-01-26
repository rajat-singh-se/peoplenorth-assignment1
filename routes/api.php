<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('employee/get',[EmployeeController::class,'employees']);
Route::post('employee/create',[EmployeeController::class,'createEmployee']);
Route::post('employee/update/{id}',[EmployeeController::class,'updateEmployeeData']);
Route::get('employee/delete/{id}',[EmployeeController::class,'deleteEmployee']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
