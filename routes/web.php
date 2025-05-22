<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Storage;
>>>>>>> a0f3416 (first commit)
use App\Http\Controllers\PdfController;
use App\Http\Controllers\WordController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SuratPdfController;
<<<<<<< HEAD
=======

>>>>>>> a0f3416 (first commit)
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
<<<<<<< HEAD
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
=======
>>>>>>> a0f3416 (first commit)
*/

Route::get('/', function () {
    return view('welcome');
});

<<<<<<< HEAD
// Route::get('/stok', 'StokController@index');

Route::get('word', function(){
=======
Route::get('word', function () {
>>>>>>> a0f3416 (first commit)
    return view('word');
});

Route::post('word', [WordController::class, 'index'])->name('word.index');

Route::get('/view-pdf', [SuratPdfController::class, 'view_pdf']);

<<<<<<< HEAD

=======
// Download surat via storage disk 'public'
Route::get('/download-surat/{filename}', function ($filename) {
    $path = 'surats/' . $filename;

    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'File tidak ditemukan.');
    }

    return Storage::disk('public')->download($path);
})->name('download-surat');



// Download surat2 via storage disk 'public' juga (sesuaikan folder surat2s harus di-link ke public/storage)
Route::get('/download-surat2/{filename}', function ($filename) {
    $path = 'surat2s/' . $filename;

    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'File tidak ditemukan.');
    }

    return Storage::disk('public')->download($path);
})->name('download-surat2');
>>>>>>> a0f3416 (first commit)
