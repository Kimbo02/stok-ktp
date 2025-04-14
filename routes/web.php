<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\WordController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SuratPdfController;
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

// Route::get('/stok', 'StokController@index');

Route::get('word', function(){
    return view('word');
});

Route::post('word', [WordController::class, 'index'])->name('word.index');

Route::get('/view-pdf', [SuratPdfController::class, 'view_pdf']);


