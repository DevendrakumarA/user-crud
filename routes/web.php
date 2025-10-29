<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;


Route::get('/', [AdminController::class, 'index'])->name('admins.index');
Route::get('/create', [AdminController::class, 'create'])->name('admins.create');
Route::post('/store', [AdminController::class, 'store'])->name('admins.store');
Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('admins.edit');
Route::post('/update/{id}', [AdminController::class, 'update'])->name('admins.update');
Route::get('/delete/{id}', [AdminController::class, 'destroy'])->name('admins.delete');
Route::get('/export/csv', [AdminController::class, 'exportCsv'])->name('admins.export.csv');
Route::get('/export/pdf', [AdminController::class, 'exportPdf'])->name('admins.export.pdf');


 
