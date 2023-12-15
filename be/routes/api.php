<?php

use App\Http\Controllers\Api\Authorization\LoginController;
use App\Http\Controllers\Api\DeckCard\Draw\DeckCardDrawController;
use App\Http\Controllers\Api\Game\ActualGameDataController;
use App\Http\Controllers\Api\Game\CreateMoveController;
use App\Http\Controllers\Api\Game\StartGameController;
use App\Http\Controllers\Api\User\Data\GetUserDataController;
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
    Route::get('duels', function (Request $request) {
        return [
            [
                'id' => 1,
                'player_name' => 'Jan Kowalski',
                'opponent_name' => 'Piotr Nowak',
                'won' => 0,
            ],
            [
                'id' => 2,
                'player_name' => 'Jan Kowalski',
                'opponent_name' => 'Tomasz Kaczyński',
                'won' => 1,
            ],
            [
                'id' => 3,
                'player_name' => 'Jan Kowalski',
                'opponent_name' => 'Agnieszka Tomczak',
                'won' => 1,
            ],
            [
                'id' => 4,
                'player_name' => 'Jan Kowalski',
                'opponent_name' => 'Michał Bladowski',
                'won' => 1,
            ],
        ];
    });

    //CARDS
    Route::post('cards', DeckCardDrawController::class);

    //USER DATA
    Route::get('user-data', GetUserDataController::class);
});
