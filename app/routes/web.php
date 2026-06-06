<?php

use App\Http\Controllers\RetailerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/retailers', [RetailerController::class, 'index'])->name('retailers.index');
Route::get('/retailers/{id}', [RetailerController::class, 'show'])->name('retailers.show');
