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
use App\Models\{ Logo, SuccessStory, History, SelfMessage, MissionStatement, VisionStatement };
use Carbon\Carbon;

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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'stories' => SuccessStory::orderBy('created_at', 'desc')->get(),
//         'histories' => History::orderBy('created_at', 'desc')->get(),
//         'mission' => MissionStatement::where('is_used', true)->latest()->first(),
//         'vision' => VisionStatement::where('is_used', true)->latest()->first(),
//     ]);
// })->name('landing-page');
Route::get('/', [UserController::class, 'ladingPage'])->name('landing-page');

Route::post('/send-otp', [UserController::class, 'sendOTP'])->name('send-otp');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('user')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile/{id}', [ProfileController::class, 'viewProfile'])->name('profile.view');
    Route::get('/album/{id}', [ProfileController::class, 'viewAlbum'])->name('album.view');
    Route::get('/achievement/{id}', [ProfileController::class, 'viewAchievement'])->name('achievement.view');
});

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::prefix('user-management')->group(function () {
        // Route::get('/', function () {
        //     return Inertia::render('Dashboard');
        // })->name('user-management');

        Route::get('/', [UserController::class, 'index'])->name('user-management');
        Route::post('/bulk-upload', [UserController::class, 'uploadUsers'])->name('upload-users');
        Route::post('/delete-user', [UserController::class, 'deleteUser'])->name('delete-user');
        Route::post('/save-user', [UserController::class, 'saveUser'])->name('save-user');
    });

    Route::prefix('course-management')->group(function () {
        Route::get('/', [UserController::class, 'courses'])->name('course-management');
        Route::post('/save-course', [UserController::class, 'saveCourse'])->name('user-save-course');
        Route::post('/delete-course', [UserController::class, 'deleteCourse'])->name('user-delete-course');
    });

    Route::prefix('archive')->group(function () {
        Route::get('/', [UserController::class, 'archiveUsers'])->name('archived-users');
        Route::post('/unarchive-user', [UserController::class, 'unarchivedUsers'])->name('unarchive-user');
    });

    Route::prefix('backup-and-restore')->group(function () {
        Route::get('/', [UserController::class, 'backupAndRestore'])->name('backup-and-restore');
        Route::get('/export-table/{table}', [UserController::class, 'backUp'])->name('bar');
        Route::post('/upload-sql', [UserController::class, 'restore'])->name('bar-upload');
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

        // Dynamic Endpoint
        Route::post('/delete-data', [HomepageController::class, 'deleteData'])->name('hpm-delete-data');
        Route::post('/use-data', [HomepageController::class, 'useData'])->name('hpm-use-data');
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [AlumniController::class, 'dashboard'])->name('staff-dashboard');
    });

    // Route::prefix('dashboard')->group(function () {
    //     Route::get('/', [HomepageController::class, 'index'])->name('staff-dashboard');
    // });

    Route::prefix('alumni-records')->group(function () {
        Route::get('/', [AlumniController::class, 'records'])->name('staff-alumni-records');
    });

    Route::prefix('achievements-and-recogniations')->group(function () {
        Route::get('/', [AchievementController::class, 'index'])->name('staff-achievements-and-recogniations');
        Route::post('/save-post', [AchievementController::class, 'savePost'])->name('staff-aap-save-post');
        Route::post('/delete-post', [AchievementController::class, 'deletePost'])->name('delete-achievement');
        Route::post('/save-like', [AchievementController::class, 'saveLike'])->name('staff-aap-save-like');
        Route::post('/save-comment', [AchievementController::class, 'saveComment'])->name('staff-aap-save-comment');
        Route::post('/edit-comment', [AchievementController::class, 'editComment'])->name('staff-aap-edit-comment');
        Route::post('/delete-comment', [AchievementController::class, 'deleteComment'])->name('staff-aap-delete-comment');
    });

    Route::prefix('school-album')->group(function () {
        Route::get('/', [AlbumController::class, 'index'])->name('staff-school-album');
        Route::post('/save-post', [AlbumController::class, 'savePost'])->name('staff-album-save-post');
        Route::post('/delete-post', [AlbumController::class, 'deletePost'])->name('delete-album');
        Route::post('/save-like', [AlbumController::class, 'saveLike'])->name('staff-album-save-like');
        Route::post('/save-comment', [AlbumController::class, 'saveComment'])->name('staff-album-save-comment');
        Route::post('/edit-comment', [AlbumController::class, 'editComment'])->name('staff-album-edit-comment');
        Route::post('/delete-comment', [AlbumController::class, 'deleteComment'])->name('staff-album-delete-comment');
    });

    Route::prefix('class-batches')->group(function () {
        Route::get('/', [BatchController::class, 'index'])->name('staff-class-batches');
        Route::get('/{id}', [BatchController::class, 'viewBatch'])->name('staff-view-batch');
        Route::post('/save-batch', [BatchController::class, 'saveBatch'])->name('staff-save-batch');
        Route::post('/delete-batch', [BatchController::class, 'deleteBatch'])->name('staff-delete-batch');
        Route::post('/save-batch-student', [BatchController::class, 'saveBatchStudent'])->name('staff-save-batch-student');
        Route::post('/delete-batch-student', [BatchController::class, 'deleteAlumni'])->name('staff-delete-batch-student');

    });
});

Route::prefix('alumni')->middleware(['auth', 'verified'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [AlumniController::class, 'dashboard'])->name('alumni-dashboard');
        Route::post('/share-feed', [AlumniController::class, 'shareFeed'])->name('alumni-share-feed');
    });

    Route::prefix('message-to-future-self')->group(function () {
        Route::get('/', [UserController::class, 'messageToFutureSelfIndex'])->name('alumni-mtfs-index');
        Route::post('/save-message', [UserController::class, 'saveMessageToSelf'])->name('alumni-mtfs-save-message');
    });

    Route::prefix('achievements-and-recogniations')->group(function () {
        Route::get('/', [AchievementController::class, 'index'])->name('alumni-achievements-and-recogniations');
        Route::post('/save-post', [AchievementController::class, 'savePost'])->name('alumni-aap-save-post');
        Route::post('/save-like', [AchievementController::class, 'saveLike'])->name('alumni-aap-save-like');
        Route::post('/save-comment', [AchievementController::class, 'saveComment'])->name('alumni-aap-save-comment');
    });

    Route::prefix('school-album')->group(function () {
        Route::get('/', [AlbumController::class, 'index'])->name('alumni-school-album');
        Route::post('/save-post', [AlbumController::class, 'savePost'])->name('alumni-album-save-post');
        Route::post('/save-like', [AlbumController::class, 'saveLike'])->name('alumni-album-save-like');
        Route::post('/save-comment', [AlbumController::class, 'saveComment'])->name('alumni-album-save-comment');
    });

    Route::prefix('class-batches')->group(function () {
        Route::get('/', [BatchController::class, 'index'])->name('alumni-class-batches');
        Route::get('/{id}', [BatchController::class, 'viewBatch'])->name('alumni-view-batch');
        Route::post('/save-batch', [BatchController::class, 'saveBatch'])->name('alumni-save-batch');
        Route::post('/save-batch-student', [BatchController::class, 'saveBatchStudent'])->name('alumni-save-batch-student');

    });
});


Route::get('/keep-alive', function () {
    return response()->json(['message' => 'Keep Alive'], 200);
});

Route::get('/mtfs', [UserController::class, 'mtfs']);



require __DIR__.'/auth.php';
