<?php

use Illuminate\Http\Request;

use App\Http\Controllers\Api\ReportController;

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

Route::middleware('auth:sanctum')->group(function () {
    // Get the authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });



});

  // Routes handled by ReportController in the Api namespace
    Route::get('/reports/by-constituencies', [ReportController::class, 'byConstituencies']);

    Route::get('/reports/by-constituencies-with-vote-type', [ReportController::class, 'byConstituenciesWithVoteType']);

    Route::get('/reports/get-wins-by-constituencies', [ReportController::class, 'getWinByConstituenciesData']);

    Route::get('/reports/count-constituency-wins', [ReportController::class, 'countConstituencyWins']);

    Route::get('/reports/constituency-wise', [ReportController::class, 'votesByConstituency']);


// ->get('/user', function (Request $request) {
//    return $request->user();


// });
