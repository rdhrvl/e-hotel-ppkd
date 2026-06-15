<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\CreatePin;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Dashboard\Home;
use App\Livewire\Dashboard\ListDn;
use App\Livewire\Dashboard\HistoryDn;
use App\Livewire\Dashboard\Notifications;
use App\Livewire\Settings\Index as SettingsIndex;
use App\Livewire\Settings\ChangePassword;
use App\Livewire\Settings\ChangePin;
use App\Livewire\Settings\UploadESign;
use App\Livewire\Settings\UploadEStamp;

// Redirect root
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
});

// Authenticated (PIN not necessarily set yet)
Route::middleware('auth')->group(function () {
    Route::get('/create-pin', CreatePin::class)->name('pin.create');
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});

// Authenticated + PIN required routes
Route::middleware(['auth', 'pin.required'])->group(function () {
    Route::get('/dashboard', Home::class)->name('dashboard');
    Route::get('/delivery-notes', ListDn::class)->name('delivery-notes');
    Route::get('/history', HistoryDn::class)->name('history');
    Route::get('/notifications', Notifications::class)->name('notifications');
    
    // Settings
    Route::get('/settings', SettingsIndex::class)->name('settings');
    Route::get('/settings/password', ChangePassword::class)->name('settings.password');
    Route::get('/settings/pin', ChangePin::class)->name('settings.pin');
    Route::get('/settings/upload-sign', UploadESign::class)->name('settings.upload-sign');
    Route::get('/settings/upload-stamp', UploadEStamp::class)->name('settings.upload-stamp');
});
