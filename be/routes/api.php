<?php

use App\Http\Controllers\Api\Authorization\LoginController;
use App\Http\Controllers\Api\DeckCard\Draw\DeckCardDrawController;
use App\Http\Controllers\Api\Duels\GetDuelsHistoryController;
use App\Http\Controllers\Api\Game\ActualGameDataController;
use App\Http\Controllers\Api\Game\CreateMoveController;
use App\Http\Controllers\Api\Game\StartGameController;
use App\Http\Controllers\Api\User\Data\GetUserDataController;
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

Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    //START THE DUEL
    Route::post('duels', StartGameController::class);

    //CURRENT GAME DATA
    Route::get('duels/active', ActualGameDataController::class);

    //User has just selected a card
    Route::post('duels/action', CreateMoveController::class);

    //DUELS HISTORY
    Route::get('duels', GetDuelsHistoryController::class);

    //CARDS
    Route::post('cards', DeckCardDrawController::class);

    //USER DATA
    Route::get('user-data', GetUserDataController::class);
});
