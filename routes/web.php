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
use App\Http\Controllers\UserEventController;
use App\Http\Controllers\CompetitionResultController;
use App\Http\Controllers\DashboardAdminController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\EventRegistrationController;

Route::get('/test-telegram', function () {
    Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
        'chat_id' => env('TELEGRAM_CHAT_ID'),
        'text' => '✅ Telegram bot berhasil terhubung!'
    ]);

    return 'OK';
});
Route::post('/telegram/webhook', [TelegramController::class, 'handle']);



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

Route::get('/event', [EventController::class, 'publicIndex'])
    ->name('events');


Route::middleware('auth')->group(function () {
    Route::get('/event/registrasi/{slug}', [EventRegistrationController::class, 'create'])
        ->name('events.register');



    Route::post('/event/registrasi', [EventRegistrationController::class, 'store'])
        ->name('events.register.store');
});

Route::view('/tentang', 'pages.about')->name('about');
Route::view('/kontak', 'pages.contact')->name('contact');

Route::view('/mytransactions', 'pages.transactions')
    ->name('transactions');


Route::view('/user/events', 'pages.my-events')
    ->name('user.events.index');


Route::view('/event/detail', 'pages.competition-detail')
    ->name('events.competition.show');

// =================
//   USER EVENTS
// =================
// Route::middleware('auth')->group(function () {
//     Route::get('/user/events', [UserEventController::class, 'index'])
//         ->name('user.events.index');

//     Route::get('/events/{event}/competition', [UserEventController::class, 'showCompetition'])
//         ->name('events.competition.show');
// });

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

        Route::get('/', [HomeController::class, 'dashboardadmin'])
            ->name('dashboard');


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


        Route::view('/competition/create', 'admin.events.results-create')
            ->name('events.competition.create');

        Route::view('/competition', 'admin.events.results-index')
            ->name('events.competition.index');
        // Hasil kompetisi
        // Route::prefix('events/competition')
        //     ->name('events.competition.')
        //     ->controller(CompetitionResultController::class)
        //     ->group(function () {
        //     Route::get('/', 'index')->name('index');
        //     Route::get('/create', 'create')->name('create');
        //     Route::post('/', 'store')->name('store');
        // });
    
        //penjualan
        Route::get('/laporan/penjualan', [SalesReportController::class, 'index'])
            ->name('reports.sales');
        Route::post('/transactions/{transaction}/verify', [SalesReportController::class, 'verify'])
            ->name('transactions.verify');
        ;


        Route::post('/logout', [UserController::class, 'logout'])
            ->name('logout');
    });