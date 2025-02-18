<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomOccupancyLogBookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->group(function () {
    Route::resource('rooms', RoomController::class);
});
Route::middleware('api')->group(function () {
    Route::resource('room-occupancy-log-books', RoomOccupancyLogBookController::class);
    Route::get('get-chart-data', [RoomOccupancyLogBookController::class, 'getChartData']);
});
