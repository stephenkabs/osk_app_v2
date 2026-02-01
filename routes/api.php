<?php

use App\Http\Controllers\Api\PaygateController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PropertyLeaseAgreementController;

use App\Http\Controllers\WirepickCallbackController;
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




Route::post('/payments', [PaymentController::class, 'store']);


Route::post('/payments', [PaygateController::class, 'create']);
Route::post('/payments/status', [PaygateController::class, 'status']);


Route::post('/wirepick/callback', [WirepickCallbackController::class, 'handle'])
    ->name('wirepick.callback');

