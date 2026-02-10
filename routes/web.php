<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('index');
});

// SPA fallback: cualquier ruta del frontend debe servir el mismo entrypoint.
Route::get('/{any}', function () {
    return view('index');
})->where('any', '.*');
