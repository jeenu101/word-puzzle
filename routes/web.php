<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PuzzleController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\LeaderboardController;





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

// Authentication Routes
Route::get('/', [AuthController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showStudentRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'registerStudent']);

//admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AuthController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/puzzles', [PuzzleController::class, 'index'])->name('puzzles.index');
    Route::get('/puzzles/create', [PuzzleController::class, 'create'])->name('puzzles.create');
    Route::post('/puzzles', [PuzzleController::class, 'store'])->name('puzzles.store');
    Route::delete('/puzzles/{puzzle}', [PuzzleController::class, 'destroy'])->name('puzzles.destroy');
});

// Protected Game Routes for students
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/game', [GameController::class, 'index'])->name('game.index');
    Route::get('/game/new', [GameController::class, 'newGame'])->name('game.new');
    Route::post('/game/submit', [GameController::class, 'submitWord'])->name('game.submit');
    Route::post('/game/end', [GameController::class, 'endGame'])->name('game.end');
    Route::get('/game/progress', [GameController::class, 'progress'])->name('game.progress');
});

//common 
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index')->middleware('auth');