<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuscaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LancamentoController;
use App\Http\Controllers\MetaFinanceiraController;
use App\Http\Controllers\RelatorioController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/busca', [BuscaController::class, 'index'])->name('busca.index');

    Route::resource('contas', ContaController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('lancamentos', LancamentoController::class);
    Route::resource('metas', MetaFinanceiraController::class)->parameters(['metas' => 'meta']);

    Route::patch('/lancamentos/{lancamento}/baixar', [LancamentoController::class, 'baixar'])->name('lancamentos.baixar');

    Route::get('/relatorios/lancamentos/pdf', [RelatorioController::class, 'lancamentosPdf'])->name('relatorios.lancamentos.pdf');
    Route::get('/relatorios/lancamentos/excel', [RelatorioController::class, 'lancamentosExcel'])->name('relatorios.lancamentos.excel');
});
