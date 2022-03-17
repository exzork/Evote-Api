<?php

use App\Http\Controllers\Api\v1\CandidateController;
use App\Http\Controllers\Api\v1\CommitteeController;
use App\Http\Controllers\Api\v1\ElectionController;
use App\Http\Controllers\Api\v1\EventController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\VoteController;
use App\Http\Controllers\Api\v1\VoterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::apiResource('/users', UserController::class)->only(['index', 'show']);
Route::apiResource('/events', EventController::class);
Route::apiResource('/events/{event}/committees', CommitteeController::class);
Route::apiResource('/events/{event}/elections', ElectionController::class);
Route::apiResource('/events/{event}/voters', VoterController::class);
Route::apiResource('/events/{event}/votes', VoteController::class);
Route::apiResource('/elections/{election}/candidates', CandidateController::class);
