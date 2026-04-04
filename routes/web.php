<?php

use Illuminate\Support\Facades\Route;

// Main app is handled by Filament (accessed via /admin)
Route::redirect('/', '/admin');
