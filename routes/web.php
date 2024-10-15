<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\AdsController;

Route::get('/admin', function () {
    return view('admin');
});
Route::get('/admin/users', function () {
    return view('users');
});
Route::get('/admin/campaigns', function () {
    return view('campaigns');
});
Route::get('/admin/ads', function () {
    return view('ads');
});

Route::resource('/api/admin/users', UserController::class)->only(['index', 'store', 'destroy']);
Route::resource('/api/admin/campaigns', CampaignController::class)->only(['index', 'store', 'destroy']);
Route::resource('/api/admin/ads', AdsController::class)->only(['index', 'store', 'destroy']);

Route::put('/api/admin/campaigns/{campaign}/status/{status}', [CampaignController::class, 'updateStatus']);
Route::put('/api/admin/ads/{ad}/budget', [AdsController::class, 'updateBudget']);
Route::put('/api/admin/ads/{ad}/content', [AdsController::class, 'updateContent']);

