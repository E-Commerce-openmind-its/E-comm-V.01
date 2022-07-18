<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivationController;
use App\Http\Controllers\CartController;

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



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

 require __DIR__.'/auth.php';

//home route
Route::get('/', [App\Http\Controllers\HomeController::class,'index'])->name('home');
//activate user account routes
Route::get('/activate/{code}', [App\Http\Controllers\ActivationController::class,'activateUserAccount'])
->name('user.activate');
Route::get('/resend/{email}', [App\Http\Controllers\ActivationController::class,'resendActivationCode'])
->name('code.resend');
//products routes
Route::resource('products', App\Http\Controllers\ProductController::class);
Route::get('products/category/{category}', [App\Http\Controllers\HomeController::class,'getProductByCategory'])->name("category.products");
//cart routes
Route::get('/cart', [App\Http\Controllers\CartController::class,'index'])->name('cart.index');
Route::post('/add/cart/{product}', [App\Http\Controllers\CartController::class,'addProductToCart'])->name('add.cart');
Route::delete('/remove/{product}/cart', [App\Http\Controllers\CartController::class,'removeProductFromCart'])->name('remove.cart');
Route::put('/update/{product}/cart', [App\Http\Controllers\CartController::class,'updateProductOnCart'])->name('update.cart');
//payment routes
Route::get('/handle-payment', [App\Http\Controllers\PaypalPaymentController::class,'handlePayment'])->name('make.payment');
Route::get('/cancel-payment', [App\Http\Controllers\PaypalPaymentController::class,'paymentCancel'])->name('cancel.payment');
Route::get('/payment-success', [App\Http\Controllers\PaypalPaymentController::class,'paymentSuccess'])->name('success.payment');
//Admin routes
Route::get('/admin', [App\Http\Controllers\AdminController::class ,'index'])->name('admin.index');
Route::get('/admin/login', [App\Http\Controllers\AdminController::class ,'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\AdminController::class ,'adminLogin'])->name('admin.login');
Route::get('/admin/logout', [App\Http\Controllers\AdminController::class ,'adminLogout'])->name('admin.logout');
Route::get('/admin/products', [App\Http\Controllers\AdminController::class ,'getProducts'])->name('admin.products');
Route::get('/admin/orders', [App\Http\Controllers\AdminController::class ,'getOrders'])->name('admin.orders');
//orders routes
Route::resource('orders', App\Http\Controllers\OrderController::class);