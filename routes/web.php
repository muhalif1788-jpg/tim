<?php
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DetailTransaksiController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Customer\DashboardCustomer;
use App\Http\Controllers\Customer\DashboardCustomerController;
use App\Http\Controllers\Customer\ProductsController as CustomerProdukController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\OrderController;  
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// ==================== AUTH ROUTES - HARUS PALING ATAS ====================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
    ->name('password.request');

// Kirim email reset password
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->name('password.email');

// Form reset password (dengan token)
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])
    ->name('password.reset');

// Proses reset password
Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.update');

// routes/web.php
Route::get('/', [HomeController::class, 'index'])->name('app');
Route::get('/products', [CustomerProdukController::class, 'index'])->name('customer.products.index');
Route::get('/products/{id}', [CustomerProdukController::class, 'show'])->name('customer.products.show');
Route::get('/products/search', [CustomerProdukController::class, 'search'])->name('customer.products.search');


Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/dashboard', [DashboardCustomerController::class, 'index'])->name('customer.dashboard');
// Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/clear/', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('customer.checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('customer.checkout.store');
    Route::get('/checkout/payment', [CheckoutController::class, 'payment'])->name('customer.checkout.payment');
    Route::any('/checkout/finish/{orderId}', [CheckoutController::class, 'finish'])->name('customer.checkout.finish');
    Route::get('/checkout/error', [CheckoutController::class, 'error'])->name('customer.checkout.error');
    Route::get('/checkout/pending', [CheckoutController::class, 'pending'])->name('customer.checkout.pending');
    Route::get('/checkout/invoice/{orderId}', [CheckoutController::class, 'invoice'])->name('customer.checkout.invoice');
    Route::get('/tentang', function () {return view('tentang-kami.index');})->name('tentang');
    Route::get('/kontak', function () {return view('customer.kontak.index'); })->name('kontak');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    
    // Password Routes
    Route::get('/password/change', [ProfileController::class, 'showChangePassword'])->name('password.change');
    Route::put('/password/change', [ProfileController::class, 'updatePassword'])->name('password.update');
    
    // Orders Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/orders/{id}/track', [OrderController::class, 'track'])->name('orders.track');
    Route::delete('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    
    // Export PDF
    Route::get('/orders/{id}/export-pdf', function($id) {
        $transaction = \App\Models\Transaksi::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        // Logika export PDF bisa ditambahkan di sini
        return redirect()->back()->with('info', 'Fitur export PDF akan segera tersedia');
    })->name('orders.export-pdf');
});


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
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transaksi/{transaksi}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::delete('/transaksi/{transaksi}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::post('/transaksi/{transaksi}/update-status', [TransactionController::class, 'updateStatus'])->name('transactions.update-status');
    
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
    Route::post('/transaksi/{transaksi}/finalize', [TransactionController::class, 'finalize'])->name('transaksi.finalize');
});