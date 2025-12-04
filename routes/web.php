<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MagazineController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Models\Category;
use App\Models\Order;
use App\Models\Magazine;

// route utama langsung ke index Magazine / home
Route::get('/', [MagazineController::class, 'home'])->name('home');

Route::get('/detail/{id}', [MagazineController::class, 'show'])->name('detail.show');

// Show all books for a category (public)
Route::get('/category/{id}/books', [MagazineController::class, 'booksByCategory'])->name('category.books');


// show all book
Route::get('/allbook', [MagazineController::class, 'allBooks'])->name('allbook');
// Show all magazine
Route::get('/allmagazine', [MagazineController::class, 'allMagazines'])->name('allmagazine');
// show all catalog
Route::get('/catalog', [MagazineController::class, 'allCatalog'])->name('catalog');

// ========== CART ROUTES ==========
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'showCart'])->name('cart');
Route::delete('/cart/{magazine_id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
Route::post('/cart/update/{magazine_id}', [CartController::class, 'updateQuantity'])->name('cart.update');

// ========== CHECKOUT ROUTES ==========
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CartController::class, 'showCheckout'])->name('checkout');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/order/confirmation/{order_id}', [CartController::class, 'orderConfirmation'])->name('order.confirmation');
    Route::get('/order/{order_id}/pdf', [CartController::class, 'downloadOrderPdf'])->name('order.pdf');
});


Route::middleware('isGuest')->group(function () {
    Route::get('/login', function () {

        return view('login');
    })->name('login');
    Route::post('/login', [UserController::class, 'loginAuth'])->name('login.auth');

    Route::get('/signUp', function () {

        return view('signUp');
    })->name('signUp');
    Route::post('/signUp', [UserController::class, 'signUp'])->name('sign_up.add');
});

Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('isAdmin')->prefix('/admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // ========== USERS ==========
    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', function () {
            return view('admin.user.create');
        })->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('delete');

        Route::get('/export', [UserController::class, 'export'])->name('export');
        Route::get('/trash', [UserController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [UserController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/datatables', [UserController::class, 'datatables'])->name('datatables');
    });

    // ========== MAGAZINES ==========
    Route::prefix('/magazines')->name('magazines.')->group(function () {
        Route::get('/', [MagazineController::class, 'index'])->name('index');
        Route::get('/create', function () {
            return view('admin.magazine.create', ['categories' => Category::all()]);
        })->name('create');
        Route::post('/store', [MagazineController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MagazineController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MagazineController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [MagazineController::class, 'destroy'])->name('delete');

        Route::get('/export', [MagazineController::class, 'export'])->name('export');
        Route::get('/trash', [MagazineController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [MagazineController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [MagazineController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/datatables/{type}', [MagazineController::class, 'datatables'])->name('datatables');
    });

    // ========== BOOKS ==========
    Route::prefix('/books')->name('books.')->group(function () {
        Route::get('/', [MagazineController::class, 'bookIndex'])->name('index');
        Route::get('/create', function () {
            return view('admin.book.create', ['categories' => Category::all()]);
        })->name('create');
        Route::post('/store', [MagazineController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MagazineController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MagazineController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [MagazineController::class, 'destroy'])->name('delete');

        Route::get('/export', [MagazineController::class, 'export'])->name('export');
        Route::get('/trash', [MagazineController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [MagazineController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [MagazineController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/datatables/{type}', [MagazineController::class, 'datatables'])->name('datatables');
    });

    // ========== PROMOS ==========
    Route::prefix('/promos')->name('promos.')->group(function () {
        Route::get('/', [PromoController::class, 'index'])->name('index');
        Route::get('/create', [PromoController::class, 'create'])->name('create');
        Route::post('/store', [PromoController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PromoController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PromoController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [PromoController::class, 'destroy'])->name('delete');
        Route::put('/activated/{id}', [PromoController::class, 'activated'])->name('activated');
        Route::get('/export', [PromoController::class, 'export'])->name('export');
        Route::get('/trash', [PromoController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [PromoController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [PromoController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/datatables', [PromoController::class, 'datatables'])->name('datatables');
    });

    // ========== CATEGORIES ==========
    // category
    Route::prefix('/categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [CategoryController::class, 'destroy'])->name('delete');

        // Export Excel
        Route::get('/export', [CategoryController::class, 'export'])->name('export');

        // Trash
        Route::get('/trash', [CategoryController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [CategoryController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [CategoryController::class, 'deletePermanent'])->name('delete_permanent');

        Route::get('/datatables', [CategoryController::class, 'datatables'])->name('datatables');
    });

    // ========== ORDERS ==========
    Route::prefix('/orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/show/{id}', [OrderController::class, 'show'])->name('show');
        Route::put('/update/{id}', [OrderController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [OrderController::class, 'destroy'])->name('destroy');
        // Export Excel
        Route::get('/export', [OrderController::class, 'export'])->name('export');

        // Trash
        Route::get('/trash', [OrderController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [OrderController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [OrderController::class, 'deletePermanent'])->name('delete_permanent');

        Route::get('/datatables', [OrderController::class, 'datatables'])->name('datatables');
    });
});
