<?php

use App\Http\Controllers\backend\BlogCategoryController;
use App\Http\Controllers\backend\BlogController as BackendBlogController;
use App\Http\Controllers\backend\BlogTagController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\CustomerController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\InvoiceController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\ProfileController;
use App\Http\Controllers\backend\PurchaseController;
use App\Http\Controllers\backend\SiteSettingController;
use App\Http\Controllers\backend\SupplierController;
use App\Http\Controllers\backend\TeamController;
use App\Http\Controllers\backend\UnitController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::prefix('')->middleware(['auth', 'verified'])->name('admin.')->group(function () {
    Route::resource('dashboard', DashboardController::class);
    Route::resource('profile', ProfileController::class);
    Route::post('team/bulk-delete', [TeamController::class, 'bulkDelete'])->name('team.bulkDelete');
    Route::resource('team', TeamController::class);


    Route::resource('supplier', SupplierController::class);

    Route::prefix('customer')->name('customer.')->group(function () {

        Route::resource('all', CustomerController::class);
        Route::resource('unit', UnitController::class);
    });


    Route::prefix('product')->name('product.')->group(function () {
        Route::resource('all', ProductController::class);
        Route::resource('category', CategoryController::class);
        Route::get('purchase/category', [PurchaseController::class, 'getCategory'])->name('purchase.getCategory');
        Route::get('purchase/product', [PurchaseController::class, 'getProduct'])->name('purchase.getProduct');

        Route::post('purchase/{status}/action', [PurchaseController::class, 'statusAction'])->name('purchase.status');
        Route::resource('purchase', PurchaseController::class);

        Route::resource('invoice', InvoiceController::class);
    });


    Route::prefix('invoice')->name('invoice.')->group(function () {
        Route::resource('all', InvoiceController::class);
        // Route::resource('category', CategoryController::class);
    });


    Route::get('site-setting', [SiteSettingController::class, 'index'])->name('site-setting.index');
    Route::post('site-setting', [SiteSettingController::class, 'store'])->name('site-setting.store');

    Route::prefix('blog')->name('blog.')->group(function () {
        Route::resource('category', BlogCategoryController::class);
        Route::resource('tag', BlogTagController::class);
        // Route::post('post/bulk-delete', [BackendBlogController::class, 'bulkDelete'])->name('post.bulkDelete');
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
