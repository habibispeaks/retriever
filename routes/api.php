<?php

use App\Notifications\ClaimNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Models\User;
use App\Models\UploadItem;
// use App\Models\ReportItem;
use App\Http\Controllers\AuthController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});
// Route::post('register',[AuthController::class,'register']);
// Route::post('register',[UserController::class,'register']);

// Route::post('login',[UserController::class,'login']);

Route::post('/logout', [UserController::class, 'logout']);

// Route::middleware('auth:sanctum')->group(function () {
//     // your authenticated API routes here
//     Route::post('uploaditem',[ItemController::class,'uploaditem']);
// });
Route::post('uploaditem',[ItemController::class,'uploaditem']);

Route::post('reportitem',[ItemController::class,'reportitem']);
Route::post('feedback',[ItemController::class,'feedback']);

Route::put('updateuser/{id}',[UserController::class,'updateuser']);
Route::delete('deleteuser/{id}', [UserController::class, 'deleteuser']);

Route::get('/searchitem', [ItemController::class, 'searchitem'])->name('search.index');

Route::get('/items', function (Request $request) {
    $items = UploadItem::all();
    return response()->json(['data' => $items]);
});

// Route::get('searchitem/{key}',[ItemController::class,'searchitem']);

Route::get('/items/{itemId}/claim', [ItemController::class, 'claimItem'])->middleware('auth');

Route::get('/notification', function () {
    $user = User::find(1);
    $item = User::find(2);
    $user->notify(new ClaimNotification($item->id));
    return 'Notification sent.';
});

// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth'
// ], function ($router) {
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::post('/refresh', [AuthController::class, 'refresh']);
//     Route::get('/user-profile', [AuthController::class, 'userProfile']);
// });

