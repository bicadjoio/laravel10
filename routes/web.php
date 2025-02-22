<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CSVController;
use App\Http\Controllers\UploadHistoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;



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
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/upload', [CsvController::class, 'showUploadForm'])->name('upload.form');
    Route::post('/upload', [CsvController::class, 'uploadCsv'])->name('upload.csv');
    Route::get('/upload_history', [UploadHistoryController::class, 'index'])->name('upload_history');
    Route::patch('/upload_history/{id}/marcar-conferido', [UploadHistoryController::class, 'marcarConferido'])->name('upload_history.marcarConferido');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/download/{filename}', [UploadHistoryController::class, 'downloadFile'])->name('download.file');
});


require __DIR__.'/auth.php';
