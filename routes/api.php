<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Player\PlayerActionController;
use App\Http\Controllers\Player\PlayerController;
use App\Http\Controllers\Player\PlayerInventoryController;
use App\Http\Controllers\Player\PlayerLogsItemsController;
use App\Http\Controllers\Player\PlayerLogsKillsController;
use App\Http\Controllers\Player\PlayerNoteController;
use App\Http\Controllers\Player\PlayerVehiclesController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Team\TeamController;
use App\Http\Controllers\Team\TeamMembersController;
use App\Http\Controllers\Team\TeamVehiclesController;
use App\Http\Controllers\Vehicle\VehicleController;
use App\Http\Controllers\Vehicle\VehicleInventoryController;
use App\Http\Controllers\Vehicle\VehicleLogsItemsController;
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

Route::get('/', function() {
    return "test";
});

Route::controller(AuthController::class)
    ->prefix("auth")
    ->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
    });

Route::middleware('auth:sanctum')
->group(function () {
    Route::controller(ProfileController::class)
        ->prefix("profile")
        ->group(function () {
            Route::get('/info', 'info');
        });

    Route::controller(ItemController::class)
        ->prefix("item")
        ->group(function () {
            Route::get('/list', 'list');

            Route::prefix("{id}")->whereNumber('id')
                ->group(function() {
                    Route::get('/get', 'get');
                });
        });

    Route::controller(VehicleController::class)
        ->prefix("vehicle")
        ->group(function () {
            Route::post('/list', 'list');

            Route::prefix("{id}")->whereNumber('id')
                ->group(function() {
                    Route::get('/', 'get');

                    Route::controller(VehicleInventoryController::class)
                        ->prefix("inventory")
                        ->group(function () {
                            Route::get('/', 'get');
                            Route::post('/add', 'add');
                            Route::post('/delete', 'delete');
                            Route::get('/clear', 'clear');
                        });

                    Route::prefix("log")
                        ->group(function () {
                            Route::post('/items', [VehicleLogsItemsController::class, 'list']);
                        });
                });
        });

    Route::controller(TeamController::class)
        ->prefix("team")
        ->group(function () {
            Route::post('/list', 'list');

            Route::prefix("{id}")->whereNumber('id')
                ->group(function() {
                    Route::get('/', 'get');

                    Route::controller(TeamMembersController::class)
                        ->prefix("members")
                        ->group(function () {
                            Route::post('/', 'list');
                            Route::post('/get', 'get');
                        });

                    Route::controller(TeamVehiclesController::class)
                        ->prefix("vehicles")
                        ->group(function () {
                            Route::post('/', 'list');
                        });
                });
        });

    Route::controller(PlayerController::class)
        ->prefix("player")
        ->group(function () {
            Route::post('/list', 'list');

            Route::prefix("{id}")->whereNumber('id')
                ->group(function() {
                    Route::get('/', 'get');

                    Route::controller(PlayerNoteController::class)
                        ->prefix("note")
                        ->group(function () {
                            Route::get('/', 'get');
                            Route::post('/update', 'update');
                        });

                    Route::controller(PlayerActionController::class)
                        ->prefix("action")
                        ->group(function () {
                            Route::post('/kick', 'kick');
                            Route::post('/time-ban', 'timeBan');
                            Route::post('/warn', 'warn');
                            Route::get('/suspend', 'suspend');
                            Route::get('/de-suspend', 'deSuspend');
                            Route::get('/call-to-support', 'callToSupport');
                            Route::get('/reload-inventory', 'reloadInventory');
                        });

                    Route::controller(PlayerInventoryController::class)
                        ->prefix("inventory")
                        ->group(function () {
                            Route::get('/', 'get');
                            Route::post('/add', 'add');
                            Route::post('/delete', 'delete');
                            Route::get('/clear', 'clear');
                        });

                    Route::controller(PlayerVehiclesController::class)
                        ->prefix("vehicles")
                        ->group(function () {
                            Route::post('/', 'list');
                        });

                    Route::prefix("log")
                        ->group(function () {
                            Route::post('/kills', [PlayerLogsKillsController::class, 'list']);
                            Route::post('/items', [PlayerLogsItemsController::class, 'list']);
                        });
                });
        });
    });
