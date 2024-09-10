<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\{
    UserController,
    HomepageController
};

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

// Route::get('/', function () {
//     return redirect()->route('login');
// });

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('landing-page');

Route::post('/send-otp', [UserController::class, 'sendOTP'])->name('send-otp');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {

    Route::prefix('home-page-management')->group(function () {
        Route::get('/', [HomepageController::class, 'index'])->name('home-page-management');
        Route::post('/save-logo', [HomepageController::class, 'saveLogo'])->name('home-page-management-logo');
        Route::post('/save-greeting', [HomepageController::class, 'saveGreetingMessage'])->name('home-page-management-greeting');
        Route::post('/save-story', [HomepageController::class, 'saveSuccessStory'])->name('home-page-management-story');
        Route::post('/save-history', [HomepageController::class, 'saveHistory'])->name('home-page-management-history');
        Route::post('/save-hymn', [HomepageController::class, 'saveHymn'])->name('home-page-management-hymn');
        Route::post('/save-mission', [HomepageController::class, 'saveMission'])->name('home-page-management-mission');
        Route::post('/save-vision', [HomepageController::class, 'saveVision'])->name('home-page-management-vision');
        Route::post('/save-program', [HomepageController::class, 'saveProgram'])->name('home-page-management-program');
        Route::post('/save-faq', [HomepageController::class, 'saveFaq'])->name('home-page-management-faq');
    });

    Route::prefix('user-management')->group(function () {
        // Route::get('/', function () {
        //     return Inertia::render('Dashboard');
        // })->name('user-management');

        Route::get('/', [UserController::class, 'index'])->name('user-management');
        Route::post('/bulk-upload', [UserController::class, 'uploadUsers'])->name('upload-users');
    });

    Route::prefix('archive')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Dashboard');
        })->name('archive');
    });

    Route::prefix('backup-and-restore')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Dashboard');
        })->name('backup-and-restore');
    });
});


Route::get('/keep-alive', function () {
    return response()->json(['message' => 'Keep Alive'], 200);
});

require __DIR__.'/auth.php';
