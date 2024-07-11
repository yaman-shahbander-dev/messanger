<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\UserProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');

    Route::get('/messenger', [MessengerController::class, 'index'])->name('home');
    Route::get('/messenger/search', [MessengerController::class, 'search'])->name('messenger.search');
    Route::get('/messenger/user-info', [MessengerController::class, 'fetchUserInfo'])->name('messenger.user-info');
    Route::post('/messenger/send-message', [MessengerController::class, 'sendMessage'])->name('messenger.send-message');
    Route::get('/messenger/fetch-messages', [MessengerController::class, 'fetchMessages'])->name('messenger.fetch-messages');
    Route::get('/messenger/fetch-contacts', [MessengerController::class, 'fetchContacts'])->name('messenger.fetch-contacts');
    Route::get('/messenger/update-contact-item', [MessengerController::class, 'updateContactItem'])->name('messenger.update-contact-item');
    Route::put('/messenger/make-seen', [MessengerController::class, 'makeSeen'])->name('messenger.make-seen');
    Route::post('/messenger/favorite', [MessengerController::class, 'favorite'])->name('messenger.favorite');
    Route::get('/messenger/fetch-favorite', [MessengerController::class, 'fetchFavorite'])->name('messenger.fetch-favorite');
});
