<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Dashboard\RoomList;
use App\Livewire\Dashboard\Bookings;
use App\Livewire\Dashboard\GuestBills;
use App\Livewire\Dashboard\Rooms;
use App\Livewire\Dashboard\Users;
use App\Livewire\Dashboard\Services;
use App\Livewire\Dashboard\Housekeeping;
use App\Livewire\Dashboard\AuditLogs;
use App\Livewire\Dashboard\Guests;
use App\Livewire\Dashboard\Payments;
use App\Livewire\Dashboard\Reports;
use App\Livewire\Dashboard\Settings;
use App\Livewire\Dashboard\CreateBooking;
use App\Livewire\Dashboard\Fnb;
use App\Models\Booking;

// Redirect root
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// ── Guest routes ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');

    // Forgot / Reset Password
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// ── Authenticated routes ──────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');

    // ── HMS Main screens ────────────────────────────────────────────────────
    Route::get('/dashboard', Reports::class)->name('dashboard');
    Route::get('/room-availability', RoomList::class)->name('room-availability');
    Route::get('/booking', CreateBooking::class)->name('booking.create');
    Route::get('/bookings', Bookings::class)->name('bookings');
    Route::get('/guest-bills', GuestBills::class)->name('guest-bills');
    Route::get('/housekeeping', Housekeeping::class)->name('housekeeping');
    Route::get('/guests', Guests::class)->name('guests');
    Route::get('/payments', Payments::class)->name('payments');
    Route::get('/fnb', Fnb::class)->name('fnb');
    Route::get('/reports', Reports::class)->name('reports');
    Route::get('/settings', Settings::class)->name('settings');

    // Receipt Invoice (Printable A4)
    Route::get('/bookings/{booking}/invoice', function (Booking $booking) {
        return view('invoice', [
            'booking' => $booking->load(['room.roomType', 'guestBill', 'bookingItems.service', 'payments']),
        ]);
    })->name('bookings.invoice');

    // ── Admin-only screens ──────────────────────────────────────────────────
    Route::middleware('can:isAdmin')->group(function () {
        Route::get('/rooms', Rooms::class)->name('rooms');
        Route::get('/admin/users', Users::class)->name('admin.users');
        Route::get('/admin/services', Services::class)->name('admin.services');
        Route::get('/admin/audit-logs', AuditLogs::class)->name('audit-logs');
    });
});
