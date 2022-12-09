<?php

use Illuminate\Support\Facades\Route;

Route::get('rate', [\App\Http\Controllers\ApiController::class, 'rates'])->name('rates');
Route::get('rate/{base}/{to}', [\App\Http\Controllers\ApiController::class, 'pair'])->name('pair');

