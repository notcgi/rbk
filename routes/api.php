<?php

use Illuminate\Support\Facades\Route;

Route::get('rate', [\App\src\Infrastructure\ApiController::class, 'rates'])->name('rates');
Route::get('rate/{base}/{to}', [\App\src\Infrastructure\ApiController::class, 'pair'])->name('pair');

