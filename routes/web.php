<?php

use App\Http\Controllers\AirportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;
use App\Models\Airport;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/transactions', [ProfileController::class, 'history'])->name('profile.transactions');
});

Route::get('/', [FlightController::class, 'index'])->name('flights.index');
Route::get('/flights', [FlightController::class, 'index'])->name('flights.index');
Route::post('/search', [FlightController::class, 'search'])->name('flights.search');
Route::post('/flights/pay', [FlightController::class, 'pay'])->name('flights.pay');
Route::get('/flights/pay/success', [FlightController::class, 'paySuccess'])->name('flights.pay.success');

Route::resource('airports', AirportController::class);

// Airport suggestions for autocomplete
Route::get('/airport-suggest', [AirportController::class, 'suggest'])->name('airports.suggest');

require __DIR__.'/auth.php';
