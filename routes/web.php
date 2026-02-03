    <?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\ProductController;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\EventController;
    use App\Http\Controllers\LearningMaterialController;
    use App\Http\Controllers\CartController;
    use App\Http\Controllers\CheckoutController;
    use App\Http\Controllers\TransactionController;
    use App\Http\Controllers\SalesReportController;


    //landing page
    Route::get('/', [HomeController::class, 'home'])->name('home');

    Route::get('/produk', [ProductController::class, 'userIndex'])
        ->name('products');

    //keranjang
    Route::prefix('keranjang')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])
            ->name('index');
        Route::post('/add', [CartController::class, 'add'])
            ->name('add');
        Route::delete('/item/{item}', [CartController::class, 'remove'])
            ->name('remove');
        Route::patch('/item/{item}/qty', [CartController::class, 'updateQuantity'])
            ->name('updateQty');
    });


    //halaman pembelajaran
    Route::prefix('belajar')->name('learn.')->controller(LearningMaterialController::class)->group(function () {

        Route::get('/', 'indexUser')->name('index');
        Route::get('/video', 'videos')->name('video');
        Route::get('/modul', 'modules')->name('module');

    });

    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout')
        ->middleware('auth');
    Route::post('/checkout', [TransactionController::class, 'store'])
        ->name('checkout.store')
        ->middleware('auth');

    Route::view('/event', 'pages.events')->name('events');
    Route::view('/event/daftar', 'pages.event-register')->name('events.register');
    Route::view('/tentang', 'pages.about')->name('about');
    Route::view('/kontak', 'pages.contact')->name('contact');

    Route::view('/transactions', 'pages.transactions')
    ->name('transactions');

    // Auth Routes
    Route::prefix('auth')->name('auth.')->group(function () {

        Route::get('/login', [UserController::class, 'showLogin'])->name('login');
        Route::post('/login', [UserController::class, 'login'])->name('login.post');

        Route::get('/register', [UserController::class, 'showRegister'])->name('register');
        Route::post('/register', [UserController::class, 'register'])->name('register.post');

        Route::post('/logout', [UserController::class, 'logout'])->name('logout');

        Route::view('/lupa-password', 'auth.forgot-password')->name('forgot');
    });



    // =================
    //   ADMIN ROUTES
    // =================
    Route::middleware(['auth', 'admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::view('/', 'admin.dashboard')->name('dashboard');

            //produk
            Route::prefix('produk')
                ->controller(ProductController::class)
                ->group(function () {

                Route::get('/', 'adminIndex')->name('products.index');
                Route::post('/', 'store')->name('products.store');
                Route::put('/{product}', 'update')->name('products.update');
                Route::delete('/{product}', 'destroy')->name('products.destroy');

            });

            //event
            Route::prefix('event')
                ->controller(EventController::class)
                ->group(function () {

                Route::get('/', 'index')->name('events.index');
                Route::post('/', 'store')->name('events.store');
                Route::put('/{event}', 'update')->name('events.update');
                Route::delete('/{event}', 'destroy')->name('events.destroy');

            });

            //pembelajaran
            Route::prefix('learn')
                ->controller(LearningMaterialController::class)
                ->group(function () {

                Route::get('/', 'index')->name('learn.index');
                Route::post('/', 'store')->name('learn.store');
                Route::put('/{learningMaterial}', 'update')->name('learn.update');
                Route::delete('/{learningMaterial}', 'destroy')->name('learn.destroy');

            });

            Route::view('/admin', 'admin.admins.index')->name('admins.index');
            Route::view('/pengaturan', 'admin.settings')->name('settings');

            //penjualan
            Route::get('/laporan/penjualan', [SalesReportController::class, 'index'])
                ->name('reports.sales');
            Route::post('/transactions/{transaction}/verify', [SalesReportController::class, 'verify'])
                ->name('transactions.verify');
            ;


            Route::post('/logout', [UserController::class, 'logout'])
                ->name('logout');
        });