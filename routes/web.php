<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\{
    UserController
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

Route::get('/', function () {
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);

    return redirect()->route('login');
});

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {

    Route::prefix('home-page-management')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Dashboard');
        })->name('home-page-management');
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
