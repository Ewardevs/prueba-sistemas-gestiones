<?php
use Lib\Route;

Route::get("/", function () {
    echo "Hello World";
});
Route::get("/kri", function () {
    echo "hola kri";
}); 

Route::dispatch();