<?php

use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;

Route::post("/incoming-emails", [EmailController::class, "store"]);
