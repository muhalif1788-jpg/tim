<?php
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DetailTransaksiController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Customer\ProductsController as CustomerProdukController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;  
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// ==================== AUTH ROUTES - HARUS PALING ATAS ====================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// routes/web.php
Route::get('/', [HomeController::class, 'index'])->name('app');

// ==================== CUSTOMER ROUTES ====================
Route::get('/products', [CustomerProdukController::class, 'index'])->name('customer.products.index');
Route::get('/products/{id}', [CustomerProdukController::class, 'show'])->name('customer.products.show');
Route::get('/products/search', [CustomerProdukController::class, 'search'])->name('customer.products.search');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
Route::put('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/cart/clear/', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/invoice/{invoiceNumber}', [CheckoutController::class, 'invoice'])->name('checkout.invoice');

Route::get('/tentang', function () {
    return view('tentang-kami.index');
})->name('tentang');


// ==================== ADMIN ROUTES ====================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Kategori Routes
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    
    // Produk Routes
    Route::get('/produk', [AdminProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [AdminProdukController::class, 'create'])->name('produk.create');
    Route::post('/produk', [AdminProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{produk}', [AdminProdukController::class, 'show'])->name('produk.show');
    Route::get('/produk/{produk}/edit', [AdminProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{produk}', [AdminProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{produk}', [AdminProdukController::class, 'destroy'])->name('produk.destroy');
    
    // Transaksi Routes
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::delete('/transaksi/{transaksi}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    
    // User Routes
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    // Detail Transaksi Routes
    Route::post('/transaksi/{id_transaksi}/detail', [DetailTransaksiController::class, 'store'])->name('detail.store');
    Route::put('/detail/{id_detail}', [DetailTransaksiController::class, 'update'])->name('detail.update');
    Route::delete('/detail/{id_detail}', [DetailTransaksiController::class, 'destroy'])->name('detail.destroy');

    // Finalize Transaksi
    Route::post('/transaksi/{transaksi}/finalize', [TransaksiController::class, 'finalize'])->name('transaksi.finalize');
});