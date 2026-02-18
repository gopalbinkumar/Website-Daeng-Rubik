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
use App\Http\Controllers\UserCompetitionController;
use App\Http\Controllers\WeightedScoringController;

use App\Mail\ResetCodeMail;
use Illuminate\Support\Facades\Mail;

Route::get('/test-otp', function () {

    Mail::to('drgnclasher@gmail.com')
        ->send(new ResetCodeMail(123456));

    return 'OTP terkirim!';
});



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
    Route::get('/video', 'videos')->middleware('auth')->name('video');
    Route::get('/modul', 'modules')->middleware('auth')->name('module');
    Route::get('/bookmark', 'bookmarks')->name('bookmark');

});
Route::get(
    '/belajar/bookmark',
    [LearningMaterialController::class, 'bookmarks']
)->name('learn.bookmark');

Route::middleware('auth')->post(
    '/learn/{learningMaterial}/bookmark',
    [LearningMaterialController::class, 'toggleBookmark']
)->name('learn.bookmark.toggle');


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



Route::middleware('auth')->group(function () {

    Route::get('/my-profile', [UserController::class, 'profile'])
        ->name('profile');

    Route::put('/my-profile', [UserController::class, 'updateProfile'])
        ->name('profile.update');

    Route::put('/my-profile/password', [UserController::class, 'updatePassword'])
        ->name('profile.password');

    Route::get('/my-transactions', [TransactionController::class, 'index'])
        ->name('transactions');

    Route::get('/my-competitions',[UserCompetitionController::class, 'index']
        )->name('user.competitions');
});

Route::get(
    '/competition/result/{id}/{slug}',
    [UserCompetitionController::class, 'show']
)->name('events.competition.show');


// Auth Routes
Route::prefix('auth')->name('auth.')->group(function () {

    Route::get('/login', [UserController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login.post');

    Route::get('/register', [UserController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserController::class, 'register'])->name('register.post');

    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    Route::get('/lupa-password', function () {
        return view('auth.forgot-password');
    })->name('forgot');

    Route::post('/forgot-password/send-code', [UserController::class, 'sendResetCode'])
        ->name('forgot.send.code');

    Route::post('/forgot-password/verify-code', [UserController::class, 'checkResetCode'])
        ->name('forgot.verify.code');

    Route::post('/forgot-password/update-password', [UserController::class, 'updateForgotPassword'])
        ->name('forgot.update.password');

});


Route::middleware(['auth', 'admin'])
    ->get(
        '/admin/weighted-scoring',
        [WeightedScoringController::class, 'api']
    )->name('admin.weighted.index');

// =================
//   ADMIN ROUTES
// =================
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /* =====================
         | DASHBOARD
         ===================== */
        Route::get('/', [HomeController::class, 'dashboardadmin'])
            ->name('dashboard');


        /* =====================
         | PRODUK
         ===================== */
        Route::prefix('produk')
            ->controller(ProductController::class)
            ->name('products.')
            ->group(function () {

                Route::get('/', 'adminIndex')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{product}', 'update')->name('update');
                Route::delete('/{product}', 'destroy')->name('destroy');
            });


        /* =====================
         | EVENT
         ===================== */
        Route::prefix('event')
            ->controller(EventController::class)
            ->name('events.')
            ->group(function () {

                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{event}', 'update')->name('update');
                Route::delete('/{event}', 'destroy')->name('destroy');
            });


        /* =====================
         | EVENT — PESERTA KOMPETISI
         ===================== */
        Route::prefix('events/participants')
            ->controller(EventRegistrationController::class)
            ->name('events.participants.')
            ->group(function () {

                Route::get('/', 'adminIndex')->name('index');
                Route::post('/{registration}/accept', 'accept')->name('accept');
                Route::post('/{registration}/reject', 'reject')->name('reject');
                Route::put('/{registration}', 'update')->name('update');
            });


        /* =====================
         | EVENT — HASIL KOMPETISI
         ===================== */
        Route::prefix('events/competition')
            ->controller(CompetitionResultController::class)
            ->name('events.competition.')
            ->group(function () {

                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });

        /* =====================
            | AJAX SUPPORT
            ===================== */
        // autocomplete peserta diterima
        Route::get(
            '/events/{eventId}/accepted-participants',
            [CompetitionResultController::class, 'acceptedParticipants']
        )->name('events.accepted-participants');

        // 🔥 CHECK EXISTING RESULT (CREATE = EDIT)
        Route::get(
            '/events/{event}/competition/check',
            [CompetitionResultController::class, 'check']
        )->name('events.competition.check');

        Route::get(
            '/events/{event}/competition/results',
            [CompetitionResultController::class, 'resultsByCategoryRound']
        )->name('events.competition.results');


        /* =====================
         | PEMBELAJARAN
         ===================== */
        Route::prefix('learn')
            ->controller(LearningMaterialController::class)
            ->name('learn.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{learningMaterial}', 'update')->name('update');
                Route::delete('/{learningMaterial}', 'destroy')->name('destroy');
            });


        /* =====================
         | ADMIN & PENGATURAN
         ===================== */
        Route::view('/pengaturan', 'admin.settings')->name('settings');

        /* ===============================
             ADMIN USER MANAGEMENT
        =================================*/
        // tampilkan semua user
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');
        // hapus user
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy');

        /* =====================
         | LAPORAN & PENJUALAN
         ===================== */
        Route::get('/laporan/penjualan', [SalesReportController::class, 'index'])
            ->name('reports.sales');

        Route::post(
            '/transactions/{transaction}/verify',
            [SalesReportController::class, 'verify']
        )->name('transactions.verify');
        Route::post(
            '/transactions/{transaction}/reject',
            [SalesReportController::class, 'reject']
        )->name('transactions.reject');

        Route::get(
            '/reports/monthly-revenue',
            [SalesReportController::class, 'monthlyRevenueChart']
        )->name('reports.monthly-revenue');


        /* =====================
         | LOGOUT
         ===================== */
        Route::post('/logout', [UserController::class, 'logout'])
            ->name('logout');
    });