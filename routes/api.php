<?php

use App\Controllers\TareaController;
use Lib\Route;

Route::get("/tareas", [TareaController::class,"todos"]);

Route::post("/tareas", [TareaController::class,"crear"]);
Route::put("/tareas/:id", [TareaController::class,"actualizar"]);
Route::get("/tareas/:id", [TareaController::class,"porId"]);
Route::delete("/tareas/:id", [TareaController::class,"eliminar"]);



Route::dispatch();