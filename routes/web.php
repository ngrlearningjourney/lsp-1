<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\TransaksiController;
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
// transaksi
Route::get('/', [TransaksiController::class,'index_transaksi'])->name('index.transaksi');
Route::get('/create-transaksi-pelanggan', [TransaksiController::class,'create_transaksi_pelanggan'])->name('create.transaksi.pelanggan');
Route::get('/fetch-transaksi-pelanggan',[TransaksiController::class,'fetch_transaksi_pelanggan'])->name('fetch.transaksi.pelanggan');
Route::get('/pilih-pelanggan/{id}',[TransaksiController::class,'create_transaksi'])->name('create.transaksi');
Route::get('/fetch-transaksi-buku',[TransaksiController::class,'fetch_transaksi_buku'])->name('fetch.transaksi.buku');
Route::post('/store-transaksi',[TransaksiController::class,'store_transaksi'])->name('store.transaksi');


// buku
Route::get('/index-buku',[BukuController::class,'index_buku'])->name('index.buku');
Route::get('/fetch-buku',[BukuController::class,'fetch_buku'])->name('fetch.buku');
Route::get('/create-buku',[BukuController::class,'create_buku'])->name('create.buku');
Route::post('/store-buku',[BukuController::class,'store_buku'])->name('store.buku');

// Pelanggan
Route::get('/index-pelanggan',[PelangganController::class,'index_pelanggan'])->name('index.pelanggan');
Route::get('/fetch-pelanggan',[PelangganController::class,'fetch_pelanggan'])->name('fetch.pelanggan');
Route::get('/create-pelanggan',[PelangganController::class,'create_pelanggan'])->name('create.pelanggan');
Route::post('/store-pelanggan',[PelangganController::class,'store_pelanggan'])->name('store.pelanggan');



