<?php

use Illuminate\Support\Facades\Route;

// ── Auth ─────────────────────────────────────────────────────────────
use App\Http\Controllers\Auth\AuthController;

// ── Public ───────────────────────────────────────────────────────────
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\SearchController;
use App\Http\Controllers\Public\HotelController as PublicHotelController;
use App\Http\Controllers\Public\BookingController as PublicBookingController;
use App\Http\Controllers\Public\PaymentController;

// ── Customer ─────────────────────────────────────────────────────────
use App\Http\Controllers\Customer\DashboardController as CustomerDash;
use App\Http\Controllers\Customer\BookingController as CustomerBooking;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\Customer\ReviewController as CustomerReview;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\NotificationController;

// ── Hotel ─────────────────────────────────────────────────────────────
use App\Http\Controllers\Hotel\DashboardController as HotelDash;
use App\Http\Controllers\Hotel\PropertyController;
use App\Http\Controllers\Hotel\RoomController;
use App\Http\Controllers\Hotel\AvailabilityController;
use App\Http\Controllers\Hotel\BookingController as HotelBooking;
use App\Http\Controllers\Hotel\ReviewController as HotelReview;
use App\Http\Controllers\Hotel\RevenueController;

// ── Admin ─────────────────────────────────────────────────────────────
use App\Http\Controllers\Admin\DashboardController as AdminDash;
use App\Http\Controllers\Admin\HotelController as AdminHotel;
use App\Http\Controllers\Admin\BookingController as AdminBooking;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\OffersController;
use App\Http\Controllers\Admin\ReportsController;

// ═══════════════════════════════════════════════════════════════
//  PUBLIC ROUTES (no auth required)
// ═══════════════════════════════════════════════════════════════

Route::get('/',                         [HomeController::class,   'index'])->name('home');
Route::get('/search',                   [SearchController::class, 'index'])->name('search');
Route::get('/hotels-in/{city}',         [SearchController::class, 'city'])->name('search.city');
Route::get('/cities',                   [SearchController::class, 'cities'])->name('cities');
Route::post('/offers/check',            [SearchController::class, 'checkOffer'])->name('offers.check');

Route::get('/hotel/{slug}',             [PublicHotelController::class, 'show'])->name('hotel.show');
Route::post('/hotel/{hotel}/wishlist',  [PublicHotelController::class, 'toggleWishlist'])->name('hotel.wishlist.toggle');

Route::get('/book/{hotel}/{room}',           [PublicBookingController::class, 'create'])->name('booking.create');
Route::post('/book/{hotel}/{room}',          [PublicBookingController::class, 'store'])->name('booking.store');
Route::get('/booking/{ref}/confirmation',    [PublicBookingController::class, 'confirmation'])->name('booking.confirmation');
Route::get('/track-booking',                 [PublicBookingController::class, 'trackForm'])->name('booking.track');
Route::post('/track-booking',               [PublicBookingController::class, 'track'])->name('booking.track.post');

Route::post('/payment/razorpay/verify',     [PaymentController::class, 'verifyRazorpay'])->name('payment.razorpay.verify');
Route::post('/payment/demo/confirm',         [PaymentController::class, 'confirmDemo'])->name('payment.demo.confirm');
Route::post('/payment/phonepe/{booking}',   [PaymentController::class, 'phonePeCallback'])->name('payment.phonepe.callback');

// Static pages
Route::get('/how-it-works',   fn() => view('pages.how-it-works'))->name('page.how-it-works');
Route::get('/about-us',       fn() => view('pages.about'))->name('page.about');
Route::get('/contact-us',     fn() => view('pages.contact'))->name('page.contact');
Route::post('/contact-us',    fn() => back()->with('success','Message received!'))->name('page.contact.submit');
Route::get('/faq',            fn() => view('pages.faq'))->name('page.faq');
Route::get('/terms',          fn() => view('pages.terms'))->name('page.terms');
Route::get('/privacy',        fn() => view('pages.privacy'))->name('page.privacy');

// Sitemap
Route::get('/sitemap.xml', function () {
    $hotels = \App\Models\Hotel::active()->select('slug','updated_at')->get();
    $cities = \App\Models\Hotel::active()->distinct('city')->pluck('city');
    return response()->view('sitemap.index', compact('hotels','cities'))->header('Content-Type','application/xml');
})->name('sitemap');

// ═══════════════════════════════════════════════════════════════
//  AUTH ROUTES
// ═══════════════════════════════════════════════════════════════

Route::middleware('guest')->group(function () {
    Route::get('/login',                  [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login/email',           [AuthController::class, 'emailLogin'])->name('auth.email.login');
    Route::get('/register',              [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register',             [AuthController::class, 'register'])->name('auth.register');
    Route::post('/auth/otp/send',        [AuthController::class, 'sendOtp'])->name('auth.otp.send');
    Route::post('/auth/otp/verify',      [AuthController::class, 'verifyOtp'])->name('auth.otp.verify');
    Route::get('/auth/google',           [AuthController::class, 'googleRedirect'])->name('auth.google');
    Route::get('/auth/google/callback',  [AuthController::class, 'googleCallback'])->name('auth.google.callback');
    Route::get('/list-hotel',            [AuthController::class, 'hotelRegisterForm'])->name('hotel.register');
    Route::post('/list-hotel',           [AuthController::class, 'hotelRegister'])->name('hotel.register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ═══════════════════════════════════════════════════════════════
//  CUSTOMER PANEL — /customer/*
// ═══════════════════════════════════════════════════════════════

Route::middleware(['auth','role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard',                    [CustomerDash::class,    'index'])->name('dashboard');
    Route::get('/bookings',                     [CustomerBooking::class, 'index'])->name('bookings');
    Route::get('/bookings/{ref}',               [CustomerBooking::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{ref}/cancel',       [CustomerBooking::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{ref}/pdf',           [CustomerBooking::class, 'downloadPdf'])->name('bookings.pdf');
    Route::post('/bookings/{ref}/review',       [CustomerReview::class,  'store'])->name('reviews.store');
    Route::get('/wishlist',                     [WishlistController::class, 'index'])->name('wishlist');
    Route::delete('/wishlist/{hotel}',          [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/profile',                      [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile',                     [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/notifications',                [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read',     [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all',      [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::get('/notifications/count',          [NotificationController::class, 'count'])->name('notifications.count');
});

// ═══════════════════════════════════════════════════════════════
//  HOTEL OWNER PANEL — /hotel/*
// ═══════════════════════════════════════════════════════════════

Route::middleware(['auth','role:hotel_owner'])->prefix('hotel')->name('hotel.')->group(function () {
    Route::get('/dashboard',                                [HotelDash::class,       'index'])->name('dashboard');
    Route::get('/properties',                               [PropertyController::class,'index'])->name('properties');
    Route::get('/properties/create',                        [PropertyController::class,'create'])->name('properties.create');
    Route::post('/properties',                              [PropertyController::class,'store'])->name('properties.store');
    Route::get('/properties/{id}/edit',                     [PropertyController::class,'edit'])->name('properties.edit');
    Route::put('/properties/{id}',                          [PropertyController::class,'update'])->name('properties.update');
    Route::get('/properties/{id}/rooms',                    [RoomController::class,  'index'])->name('rooms');
    Route::post('/properties/{id}/rooms',                   [RoomController::class,  'store'])->name('rooms.store');
    Route::put('/properties/{hid}/rooms/{id}',              [RoomController::class,  'update'])->name('rooms.update');
    Route::delete('/properties/{hid}/rooms/{id}',           [RoomController::class,  'destroy'])->name('rooms.destroy');
    Route::get('/properties/{hid}/rooms/{id}/availability', [AvailabilityController::class,'index'])->name('availability');
    Route::post('/properties/{hid}/rooms/{id}/availability',[AvailabilityController::class,'toggle'])->name('availability.toggle');
    Route::get('/bookings',                                 [HotelBooking::class,    'index'])->name('bookings');
    Route::get('/bookings/{id}',                            [HotelBooking::class,    'show'])->name('bookings.show');
    Route::post('/bookings/{id}/accept',                    [HotelBooking::class,    'accept'])->name('bookings.accept');
    Route::post('/bookings/{id}/reject',                    [HotelBooking::class,    'reject'])->name('bookings.reject');
    Route::post('/bookings/{id}/checkin',                   [HotelBooking::class,    'checkIn'])->name('bookings.checkin');
    Route::post('/bookings/{id}/complete',                  [HotelBooking::class,    'complete'])->name('bookings.complete');
    Route::post('/bookings/{id}/noshow',                    [HotelBooking::class,    'noShow'])->name('bookings.noshow');
    Route::get('/reviews',                                  [HotelReview::class,     'index'])->name('reviews');
    Route::post('/reviews/{id}/reply',                      [HotelReview::class,     'reply'])->name('reviews.reply');
    Route::get('/revenue',                                  [RevenueController::class,'index'])->name('revenue');
});

// ═══════════════════════════════════════════════════════════════
//  ADMIN PANEL — /admin/*
// ═══════════════════════════════════════════════════════════════

Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',               [AdminDash::class,    'index'])->name('dashboard');
    Route::get('/hotels',                  [AdminHotel::class,   'index'])->name('hotels');
    Route::post('/hotels/{id}/approve',    [AdminHotel::class,   'approve'])->name('hotels.approve');
    Route::post('/hotels/{id}/reject',     [AdminHotel::class,   'reject'])->name('hotels.reject');
    Route::patch('/hotels/{id}',           [AdminHotel::class,   'update'])->name('hotels.update');
    Route::get('/bookings',                [AdminBooking::class, 'index'])->name('bookings');
    Route::patch('/bookings/{id}/status',  [AdminBooking::class, 'updateStatus'])->name('bookings.status');
    Route::get('/users',                   [UserController::class,'index'])->name('users');
    Route::patch('/users/{id}/status',     [UserController::class,'updateStatus'])->name('users.status');
    Route::post('/users/hotel-owner',      [UserController::class,'createHotelOwner'])->name('users.hotel-owner');
    Route::get('/settings',                [SettingsController::class,'index'])->name('settings');
    Route::post('/settings',               [SettingsController::class,'update'])->name('settings.update');
    Route::get('/offers',                  [OffersController::class,'index'])->name('offers');
    Route::post('/offers',                 [OffersController::class,'store'])->name('offers.store');
    Route::patch('/offers/{id}',           [OffersController::class,'update'])->name('offers.update');
    Route::delete('/offers/{id}',          [OffersController::class,'destroy'])->name('offers.destroy');
    Route::get('/reports',                 [ReportsController::class,'index'])->name('reports');
});
