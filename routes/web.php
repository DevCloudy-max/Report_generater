<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

// Root URL redirects to reports create
Route::get('/', function () {
    return redirect()->route('reports.create');
});

// Report routes
Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
Route::post('/reports/store', [ReportController::class, 'store'])->name('reports.store');

// Download routes
Route::get('/download/pdf/{id}', [ReportController::class, 'downloadPDF'])->name('download.pdf');
Route::get('/download/ppt/{id}', [ReportController::class, 'downloadPPT'])->name('download.ppt');
Route::get('/download/word/{id}', [ReportController::class, 'downloadWord'])->name('download.word');
