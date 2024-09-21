<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\{
    UserController,
    HomepageController,
    AlumniController,
    AchievementController,
    AlbumController,
    BatchController
};
use App\Models\{ Logo, SuccessStory, History };

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
    return Inertia::render('Welcome', [
        'stories' => SuccessStory::orderBy('created_at', 'desc')->get(),
        'histories' => History::orderBy('created_at', 'desc')->get()
    ]);
})->name('landing-page');

Route::post('/send-otp', [UserController::class, 'sendOTP'])->name('send-otp');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
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

Route::prefix('staff')->middleware(['auth', 'verified'])->group(function () {
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

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [HomepageController::class, 'index'])->name('staff-dashboard');
    });

    Route::prefix('alumni-records')->group(function () {
        Route::get('/', [AlumniController::class, 'records'])->name('staff-alumni-records');
    });

    Route::prefix('achievements-and-recogniations')->group(function () {
        Route::get('/', [AchievementController::class, 'index'])->name('staff-achievements-and-recogniations');
        Route::post('/save-post', [AchievementController::class, 'savePost'])->name('staff-aap-save-post');
        Route::post('/save-like', [AchievementController::class, 'saveLike'])->name('staff-aap-save-like');
        Route::post('/save-comment', [AchievementController::class, 'saveComment'])->name('staff-aap-save-comment');
    });

    Route::prefix('school-album')->group(function () {
        Route::get('/', [AlbumController::class, 'index'])->name('staff-school-album');
        Route::post('/save-post', [AlbumController::class, 'savePost'])->name('staff-album-save-post');
        Route::post('/save-like', [AlbumController::class, 'saveLike'])->name('staff-album-save-like');
        Route::post('/save-comment', [AlbumController::class, 'saveComment'])->name('staff-album-save-comment');
    });

    Route::prefix('class-batches')->group(function () {
        Route::get('/', [BatchController::class, 'index'])->name('staff-class-batches');
        Route::post('/save-batch', [BatchController::class, 'saveBatch'])->name('staff-save-batch');

    });
});


Route::get('/keep-alive', function () {
    return response()->json(['message' => 'Keep Alive'], 200);
});

require __DIR__.'/auth.php';
