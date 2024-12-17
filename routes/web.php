<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;

Route::get('/', [QuoteController::class, 'index']);
Route::put('/upvote/{quote}', [QuoteController::class, 'upvote']);
