<?php

use App\Http\Controllers\DateFilterController;
use Illuminate\Support\Facades\Route;

Route::get('/wel', function () {
  return view('DateMessage');
});
route::get('/', [DateFilterController::class, 'index']);
