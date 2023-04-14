<?php


use App\Notifications\ClaimNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Models\User;
use App\Models\UploadItem;
use App\Models\ReportItem;
use App\Http\Controllers\AuthController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register',[UserController::class,'register']);

Route::post('login',[UserController::class,'login']);

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

// Route::post('/claimNotification/{foundItemId}', function ($foundItemId) {
//     $found_item = FoundItem::findOrFail($foundItemId);
//     $claimer = Auth::user();
//     $finder = User::find($found_item->user_id);

//     $finder->notify(new ClaimNotification($claimer, $found_item));

//     return response()->json(['message' => 'Notification sent.']);
// })->middleware('auth');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});


?>
