<?php

use App\Http\Controllers\Api\TodoController;
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

Route::post('todo/create', [TodoController::class, 'store'])->name('api.todo.create');
Route::put('todo/update/{id}', [TodoController::class, 'update'])->name('api.todo.update');
Route::get('todo/show/{id}', [TodoController::class, 'show'])->name('api.todo.show');
Route::delete('todo/delete/{id}', [TodoController::class, 'destroy'])->name('api.todo.destroy');