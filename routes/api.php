<?php

use App\Http\Controllers\Api\NetworkEventApiController;
use Illuminate\Support\Facades\Route;

Route::post('/network-events', [NetworkEventApiController::class, 'store']);