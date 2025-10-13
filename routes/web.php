<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Livewire\Settings\Profile;
use App\Http\Livewire\Settings\Password;
use App\Http\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Auth;


Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');

    // Students CRUD
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');

    // Settings Livewire routes
    Route::get('/settings/profile', Profile::class)->name('settings.profile');
    Route::get('/settings/password', Password::class)->name('settings.password');
    Route::get('/settings/appearance', Appearance::class)->name('settings.appearance');
}); // <- closing of middleware group

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
