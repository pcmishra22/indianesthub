<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PropertyViewersController;

Route::prefix('admin')->middleware('auth:admin')->name('admin.')->group(function () {
    Route::get('/properties/{property}/viewers', [PropertyViewersController::class, 'index'])
        ->name('properties.viewers.index');
});

