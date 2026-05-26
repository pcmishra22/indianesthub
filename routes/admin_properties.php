<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PropertyController;

// Admin: toggle guest-visible contact on properties
// Expected POST payload: enabled=1/0
Route::post('/properties/{property:id}/toggle-public-contact', [PropertyController::class, 'togglePublicContact'])
    ->name('properties.togglePublicContact');
