<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminContentController;
use App\Http\Controllers\AdminQuestionController;
use App\Http\Controllers\AdminResultController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AssessmentController::class, 'home'])->name('home');
Route::get('/prepare', [AssessmentController::class, 'prepare'])->name('prepare');
Route::get('/assessment/start', [AssessmentController::class, 'start'])->name('assessment.start');
Route::get('/assessment', [AssessmentController::class, 'show'])->name('assessment.show');
Route::post('/assessment', [AssessmentController::class, 'answer'])->name('assessment.answer');
Route::get('/result', [AssessmentController::class, 'result'])->name('result');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::view('/kustom-ruangan', 'pages.custom-room')->name('custom-room');
    Route::get('/user/{username}', [UserProfileController::class, 'show'])->name('user.profile');

    Route::prefix('/admin')->name('admin.')->group(function () {
        Route::get('/content', [AdminContentController::class, 'index'])->name('content.index');
        Route::put('/content/home', [AdminContentController::class, 'updateHome'])->name('content.home');
        Route::put('/content/navigation', [AdminContentController::class, 'updateNavigation'])->name('content.navigation');
        Route::put('/content/social-links', [AdminContentController::class, 'updateSocialLinks'])->name('content.social-links');
        Route::resource('questions', AdminQuestionController::class)->except(['show']);
        Route::resource('results', AdminResultController::class)->except(['show']);
    });
});

Route::redirect('/index.html', '/');
Route::redirect('/HomePage.html', '/');
Route::redirect('/PreparePage.html', '/prepare');
Route::redirect('/AsesmentPage.html', '/assessment');
Route::redirect('/HasilPage.html', '/result');
Route::redirect('/Perantau/index.html', '/');
Route::redirect('/Perantau/HomePage.html', '/');
Route::redirect('/Perantau/PreparePage.html', '/prepare');
Route::redirect('/Perantau/AsesmentPage.html', '/assessment');
Route::redirect('/Perantau/HasilPage.html', '/result');
