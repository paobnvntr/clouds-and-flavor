<?php

use App\Http\Controllers\Admin\AddOnController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Staff\POSController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');

// });

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/landing-page-shop', [WelcomeController::class, 'shop'])->name('landing-page-shop');
Route::get('/contact', [WelcomeController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
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
        Route::post('admin/products/update-stock', 'updateStock')->name('admin.products.update_stock');
    });

    Route::controller(AdminOrderController::class)->group(function () {
        Route::get('admin/all-order', 'index')->name('admin.orders.index');
        Route::get('admin/pending-order', 'pendingOrder')->name('admin.orders.pending');
        Route::get('admin/completed-order', 'completedOrder')->name('admin.orders.completed');
    
        Route::get('admin/pos/all-order', 'posAllOrder')->name('admin.orders.pos.index');
        Route::get('admin/pos/pending-order', 'posPendingOrder')->name('admin.orders.pos.pending');
        Route::get('admin/pos/completed-order', 'posCompletedOrder')->name('admin.orders.pos.completed');

        Route::put('admin/orders/{id}/online-complete', 'OnlinecompleteOrder')->name('admin.orders.online-complete');
        Route::put('admin/orders/{id}/complete', 'completeOrder')->name('admin.orders.complete');
    });

    Route::prefix('admin')->group(function () {
        Route::resource('addons', AddOnController::class);
    });

    Route::controller(AdminController::class)->group(function () {
        //User routes
        Route::get('admin/user-list', 'userList')->name('admin.user.index');
        Route::get('admin/user/create', 'userCreate')->name('admin.user.create');
        Route::post('admin/user/store', 'userStore')->name('admin.user.store');
        Route::get('admin/user/edit/{id}', 'userEdit')->name('admin.user.edit');
        Route::patch('admin/user/update/{id}', 'userUpdate')->name('admin.user.update');
        Route::delete('admin/user/delete/{id}', 'userDestroy')->name('admin.user.destroy');

        //Staff routes
        Route::get('admin/staff-list', 'staffList')->name('admin.staff.index');
        Route::get('admin/staff/create', 'staffCreate')->name('admin.staff.create');
        Route::post('admin/staff/store', 'staffStore')->name('admin.staff.store');
        Route::get('admin/staff/edit/{id}', 'staffEdit')->name('admin.staff.edit');
        Route::patch('admin/staff/update/{id}', 'staffUpdate')->name('admin.staff.update');
        Route::delete('admin/staff/delete/{id}', 'staffDestroy')->name('admin.staff.destroy');

        Route::get('admin/total-earnings', 'showTotalEarnings')->name('admin.total_earnings');
    
        Route::get('admin/contact-us', 'contact')->name('admin.contact.index');
        Route::delete('/admin/messages/{id}', 'destroy')->name('admin.messages.destroy');
    });

    Route::controller(VoucherController::class)->group(function () {
        Route::get('admin/vouchers', 'index')->name('admin.vouchers.index');
        Route::get('admin/vouchers/create', 'create')->name('admin.vouchers.create');
        Route::post('admin/vouchers/store', 'store')->name('admin.vouchers.store');
        Route::get('admin/vouchers/edit/{id}', 'edit')->name('admin.vouchers.edit');
        Route::patch('admin/vouchers/{id}', 'update')->name('admin.vouchers.update');
        Route::delete('admin/vouchers/delete/{id}', 'destroy')->name('admin.vouchers.destroy');
    });
    


});

//Group Staff routes role = 1
Route::middleware(['auth', 'verified', 'role:staff'])->group(function () {
    Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');

    Route::controller(POSController::class)->group(function () {
        Route::get('/staff/pos', 'index')->name('staff.pos.index');
        Route::get('/staff/pos/filter-products', 'filterProducts')->name('staff.pos.filterProducts');
        Route::get('/staff/pos/order-success', 'orderSuccess')->name('staff.pos.order-success');
        Route::post('/staff/pos/add-to-cart', 'addToCart')->name('staff.pos.addToCart');
        Route::get('/staff/pos/cart-items', 'getCartItems')->name('staff.pos.getCartItems');
        Route::post('/staff/checkout', 'showCheckoutPage')->name('staff.pos.checkout');
        // Route::post('/staff/place-order', 'placeOrder')->name('staff.pos.placeOrder');
        Route::post('/staff/pos/place-order', 'placeOrder')->name('staff.pos.placeOrder');
        Route::get('/staff/orders/{type}/{id}', 'getOrderDetails');
        Route::post('/pos/update-cart-item', 'updateCartItem')->name('staff.pos.updateCartItem');
        Route::post('/pos/remove-cart-item', 'removeCartItem')->name('staff.pos.removeCartItem');
        Route::get('/staff/order-success', 'orderSuccess')->name('staff.pos.orderSuccess');
    });
    Route::get('/staff/order-dashboard', [StaffController::class, 'dashboard'])->name('staff.orders.dashboard');
    Route::get('/staff/orders', [StaffController::class, 'orderList'])->name('staff.orders.index');
    Route::get('/staff/pos/orders', [StaffController::class, 'posOrderList'])->name('staff.orders.pos.posOrder');
    Route::get('/staff/pending-orders', [StaffController::class, 'pendingList'])->name('staff.orders.pending-order');
    Route::get('/staff/completed-orders', [StaffController::class, 'completedList'])->name('staff.orders.completed-order');
    Route::get('/staff/online-pending', [StaffController::class, 'onlinePending'])->name('staff.orders.online-pending');
    Route::get('/staff/pos/pending-orders', [StaffController::class, 'posPending'])->name('staff.orders.pos.pos-pending');
    Route::get('/staff/pos/completed-orders', [StaffController::class, 'posCompleted'])->name('staff.orders.pos.pos-completed');
    Route::get('/staff/deliver-or-pickup', [StaffController::class, 'dORp'])->name('staff.orders.deliver-or-pickup');
    Route::get('/staff/deliver-or-pickup-completed', [StaffController::class, 'dORpCompleted'])->name('staff.orders.deliver-or-pickup-completed');
    Route::post('/staff/orders/complete', [StaffController::class, 'completeOrder'])->name('staff.orders.complete');
    Route::post('/staff/posorders/complete', [StaffController::class, 'completePosOrder'])->name('staff.orders.pos-complete');
    Route::post('/staff/orders/complete/{id}', [StaffController::class, 'dORpComplete'])->name('staff.orders.dORpcomplete');
    Route::put('/staff/orders/{id}/online-complete', [StaffController::class, 'OnlinecompleteOrder'])->name('staff.orders.online-complete');

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
        Route::get('/dashboard', 'home')->name('dashboard');
        Route::get('/products/category/{categoryId}', 'productsByCategory')->name('user.products-by-category');
        Route::get('/products/{id}/details', 'productsDetails')->name('user.products.product-details');
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
        Route::post('/cart/remove-voucher', 'removeVoucher')->name('user.cart.remove-voucher');
        Route::post('/cart/apply-voucher', 'applyVoucher')->name('user.cart.apply-voucher');
        Route::get('/cart/get-totals', 'getTotals')->name('user.cart.get-totals');
        Route::get('/products/{product}/addons', 'getAddons')->name('products.addons');
        // Route::post('/cart/place-order', 'placeOrder')->name('user.cart.placeOrder');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::get('/my-order', 'index')->name('user.order.index');
        Route::post('/orders/pay', 'payOrder')->name('user.orders.pay');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';