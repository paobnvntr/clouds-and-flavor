<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;

use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//NOT AUTH
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');



//Group Admin routes role = 2
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Admin/CategoryController
    Route::controller(CategoryController::class)->group(function () {
        Route::get('admin/category', 'index')->name('admin.categories.index');
        Route::get('admin/category/create', 'create')->name('admin.categories.create');
        Route::post('admin/category/store', 'store')->name('admin.categories.store');
        Route::get('admin/category/edit/{category}', 'edit')->name('admin.categories.edit');
        Route::patch('admin/category/update/{category}', 'update')->name('admin.categories.update');
        Route::delete('admin/category/delete/{category}', 'destroy')->name('admin.categories.destroy');
    });

    // Admin/ProductController
    Route::controller(ProductController::class)->group(function () {
        Route::get('admin/product', 'index')->name('admin.products.index');
        Route::get('admin/product/create', 'create')->name('admin.products.create');
        Route::post('admin/product/store', 'store')->name('admin.products.store');
        Route::get('admin/product/edit/{product}', 'edit')->name('admin.products.edit');
        Route::patch('admin/product/update/{product}', 'update')->name('admin.products.update');
        Route::delete('admin/product/delete/{product}', 'destroy')->name('admin.products.destroy');
    });

});



//Group Staff routes role = 1
Route::middleware(['auth', 'verified', 'role:staff'])->group(function () {
    Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
});




//Group User routes role = 0
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {

    // Route for User Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // User/ProductController
    Route::controller(UserProductController::class)->group(function () {
        Route::get('/products', 'index')->name('user.products.index');
        Route::get('/products/category/{category}', 'productsByCategory')->name('user.products-by-category');
    });

    // User/CartController
    Route::controller(CartController::class)->group(function () {
        Route::get('/my-cart', 'index')->name('user.cart.index');
        Route::post('/cart/add', 'addToCart')->name('user.cart.add-to-cart');
        Route::get('/cart/count', 'getCartCount')->name('user.cart.get-cart-count');
        Route::post('/cart/update', 'updateQuantity')->name('user.cart.update');
        Route::post('/cart/remove', 'removeItem')->name('user.cart.remove');
        Route::get('/cart/checkout', 'checkout')->name('user.cart.checkout');
        Route::post('/cart/place-order', [OrderController::class, 'placeOrder'])->name('user.cart.placeOrder');
        // Route::post('/cart/place-order', 'placeOrder')->name('user.cart.placeOrder');
    });


    Route::controller(OrderController::class)->group(function () {
        Route::get('/my-order', 'index')->name('user.order.index');
    });

});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
