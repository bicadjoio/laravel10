<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CSVController;
use App\Http\Controllers\UploadHistoryController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/upload', [CsvController::class, 'showUploadForm'])->middleware('auth')->name('upload.form');
Route::post('/upload', [CsvController::class, 'uploadCsv'])->middleware('auth')->name('upload.csv');
Route::get('/upload_history', [UploadHistoryController::class, 'index'])->middleware('auth')->name('upload_history');

require __DIR__.'/auth.php';
