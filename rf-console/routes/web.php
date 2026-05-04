<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FrequencyController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SourceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

Route::get('/attributes', [AttributeController::class, 'index'])->name('attributes.index');
Route::get('/frequencies', [FrequencyController::class, 'index'])->name('frequencies.index');
Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
Route::get('/sources', [SourceController::class, 'index'])->name('sources.index');
Route::get('/compliance', [ComplianceController::class, 'index'])->name('compliance.index');
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
