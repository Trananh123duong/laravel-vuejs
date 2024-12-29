<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\Auth\LoginController;

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

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('admin.auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::get('/admin/profile', function (Request $request) {
        $user = $request->get('user'); // Lấy thông tin user từ request
        return response()->json([
            'message' => 'Admin authenticated',
            'user' => $user,
        ]);
    });
});