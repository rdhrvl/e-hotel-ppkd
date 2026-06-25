<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Dashboard\AuditLogs;
use App\Livewire\Dashboard\Bookings;
use App\Livewire\Dashboard\CreateBooking;
use App\Livewire\Dashboard\Fnb;
use App\Livewire\Dashboard\GuestBills;
use App\Livewire\Dashboard\Guests;
use App\Livewire\Dashboard\Housekeeping;
use App\Livewire\Dashboard\Payments;
use App\Livewire\Dashboard\Reports;
use App\Livewire\Dashboard\RoomList;
use App\Livewire\Dashboard\Rooms;
use App\Livewire\Dashboard\Services;
use App\Livewire\Dashboard\Settings;
use App\Livewire\Dashboard\Users;
use App\Models\Booking;
use Illuminate\Support\Facades\Route;

// Redirect root
Route::get('/', function () {
    return auth()->check() ? redirect()->route(auth()->user()->homeRoute()) : redirect()->route('login');
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

    // Available to every authenticated user
    Route::get('/settings', Settings::class)->name('settings');
    Route::get('/dashboard', Reports::class)->name('dashboard');

    // ── Front desk screens ──────────────────────────────────────────────────
    Route::get('/room-availability', RoomList::class)->middleware('can:view_room_availability')->name('room-availability');
    Route::get('/booking', CreateBooking::class)->middleware('can:view_booking')->name('booking.create');
    Route::get('/bookings', Bookings::class)->middleware('can:view_bookings')->name('bookings');
    Route::get('/guest-bills', GuestBills::class)->middleware('can:view_guest_bills')->name('guest-bills');
    Route::get('/guests', Guests::class)->middleware('can:view_guests')->name('guests');
    Route::get('/payments', Payments::class)->middleware('can:view_payments')->name('payments');

    // Receipt Invoice (Printable A4)
    Route::get('/bookings/{booking}/invoice', function (Booking $booking) {
        return view('invoice', [
            'booking' => $booking->load(['room.roomType', 'guestBill', 'bookingItems.service', 'payments', 'guest']),
        ]);
    })->middleware('can:view_guest_bills')->name('bookings.invoice');

    // Registration Form (Printable A4)
    Route::get('/bookings/{booking}/registration-form', function (Booking $booking) {
        return view('registration-form', [
            'booking' => $booking->load(['room.roomType', 'guestBill', 'guest']),
        ]);
    })->middleware('can:view_bookings')->name('bookings.registration-form');

    // ── Housekeeping screens ────────────────────────────────────────────────
    Route::get('/housekeeping', Housekeeping::class)->middleware('can:view_housekeeping')->name('housekeeping');

    // ── Food & Beverage screens ─────────────────────────────────────────────
    Route::get('/fnb', Fnb::class)->middleware('can:view_fnb')->name('fnb');

    // ── Admin-only screens ──────────────────────────────────────────────────
    Route::get('/reports', Reports::class)->middleware('can:view_users')->name('reports');
    Route::get('/rooms', Rooms::class)->middleware('can:view_rooms')->name('rooms');
    Route::get('/admin/users', Users::class)->middleware('can:view_users')->name('admin.users');
    Route::get('/admin/services', Services::class)->middleware('can:view_services')->name('admin.services');
    Route::get('/admin/audit-logs', AuditLogs::class)->middleware('can:view_audit_logs')->name('audit-logs');
});
