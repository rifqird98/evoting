<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('home');
})->middleware('auth');

Route::get('/DaftarPemilihPrm', function () {
    return view('daftar_pilih_prm');
});
Route::get('/DaftarPemilihPra', function () {
    return view('daftar_pilih_pra');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/getVote', [App\Http\Controllers\HomeController::class, 'getVote']);
Route::get('/getPeserta', [App\Http\Controllers\HomeController::class, 'getPeserta']);
Route::put('/updateStatus', [App\Http\Controllers\HomeController::class, 'updateStatus']);
Route::get('/getDaftarCalon', [App\Http\Controllers\CalonFormaturController::class, 'getDataCalon']);
Route::get('/getPemilihPmr', [App\Http\Controllers\CalonFormaturController::class, 'getPemilihPmr']);
Route::get('/getPemilihPra', [App\Http\Controllers\CalonFormaturController::class, 'getPemilihPra']);
Route::post('/simpanSuara', [App\Http\Controllers\CalonFormaturController::class, 'simpanSuara']);
Route::get('/updateStatusPemilih', [App\Http\Controllers\CalonFormaturController::class, 'updateStatus']);
