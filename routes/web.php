<?php

use App\Http\Controllers\backend\BlogCategoryController;
use App\Http\Controllers\backend\BlogController as BackendBlogController;
use App\Http\Controllers\backend\BlogTagController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\ProfileController;
use App\Http\Controllers\backend\SiteSettingController;
use App\Http\Controllers\backend\SupplierController;
use App\Http\Controllers\backend\TeamController;

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::prefix('')->middleware(['auth', 'verified'])->name('admin.')->group(function () {
    Route::resource('dashboard', DashboardController::class);
    Route::resource('profile', ProfileController::class);
    Route::post('team/bulk-delete', [TeamController::class, 'bulkDelete'])->name('team.bulkDelete');
    Route::resource('team', TeamController::class);


    Route::resource('supplier', SupplierController::class);




    Route::get('site-setting', [SiteSettingController::class, 'index'])->name('site-setting.index');
    Route::post('site-setting', [SiteSettingController::class, 'store'])->name('site-setting.store');

    Route::prefix('blog')->name('blog.')->group(function () {
        Route::resource('category', BlogCategoryController::class);
        Route::resource('tag', BlogTagController::class);
        Route::post('post/bulk-delete', [BackendBlogController::class, 'bulkDelete'])->name('post.bulkDelete');
        Route::resource('post', BackendBlogController::class);
    });

    // Route::get('/profile', function () {})->name('profile');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});
Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
