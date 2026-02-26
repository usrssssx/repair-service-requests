<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DispatcherController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\RepairRequestController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    $userId = $request->session()->get('user_id');
    if (!$userId) {
        return redirect('/login');
    }

    $user = User::find($userId);
    if (!$user) {
        $request->session()->forget('user_id');
        return redirect('/login');
    }

    return redirect($user->role === 'dispatcher' ? '/dispatcher/requests' : '/master/requests');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth.session');

Route::middleware('auth.session')->group(function () {
    Route::get('/requests/create', [RepairRequestController::class, 'create']);
    Route::post('/requests', [RepairRequestController::class, 'store']);

    Route::middleware('role:dispatcher')->group(function () {
        Route::get('/dispatcher/requests', [DispatcherController::class, 'index']);
        Route::patch('/dispatcher/requests/{id}/assign', [DispatcherController::class, 'assign']);
        Route::patch('/dispatcher/requests/{id}/cancel', [DispatcherController::class, 'cancel']);
    });

    Route::middleware('role:master')->group(function () {
        Route::get('/master/requests', [MasterController::class, 'index']);
        Route::patch('/master/requests/{id}/take', [MasterController::class, 'take']);
        Route::patch('/master/requests/{id}/done', [MasterController::class, 'done']);
    });
});
