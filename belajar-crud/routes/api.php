<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\JenisPelangganController;
use App\Http\Controllers\Api\SalesmanController;
use App\Http\Controllers\Api\PelangganController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 1. RUTE STATIS / EXPORT (WAJIB DI ATAS)
Route::get('/jenis-pelanggan/export-excel', [JenisPelangganController::class, 'exportExcel']);
Route::get('/jenis-pelanggan/export-pdf', [JenisPelangganController::class, 'exportPdf']);

// 2. RUTE UTAMA CRUD
Route::get('/jenis-pelanggan', [JenisPelangganController::class, 'index']);
Route::post('/jenis-pelanggan', [JenisPelangganController::class, 'store']);

// RUTE EXPORT MASTER SALESMAN (WAJIB DI ATAS PARAMETER)
Route::get('/salesman/export-excel', [SalesmanController::class, 'exportExcel']);
Route::get('/salesman/export-pdf', [SalesmanController::class, 'exportPdf']);

// 3. RUTE DENGAN PARAMETER DINAMIS (WAJIB DI BAWAH)
Route::get('/jenis-pelanggan/{kd_jenis_customer}', [JenisPelangganController::class, 'show']);
Route::put('/jenis-pelanggan/{kd_jenis_customer}', [JenisPelangganController::class, 'update']);
Route::delete('/jenis-pelanggan/{kd_jenis_customer}', [JenisPelangganController::class, 'destroy']);

// RUTE UTAMA CRUD SALESMAN
Route::get('/salesman', [SalesmanController::class, 'index']);
Route::post('/salesman', [SalesmanController::class, 'store']);
Route::get('/salesman/{kd_salesman}', [SalesmanController::class, 'show']);
Route::put('/salesman/{kd_salesman}', [SalesmanController::class, 'update']);
Route::delete('/salesman/{kd_salesman}', [SalesmanController::class, 'destroy']);

Route::apiResource('pelanggan', PelangganController::class);