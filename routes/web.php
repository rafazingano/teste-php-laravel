<?php

use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ImportController::class, 'index'])->name('import.index');
Route::post('/import', [ImportController::class, 'process'])->name('import.process');
Route::get('/start-processing', [ImportController::class, 'start'])->name('import.start');
Route::post('/start-processing', [ImportController::class, 'startProcessing'])->name('import.start.processing');


