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

    Route::prefix('/messenger')
        ->controller(MessengerController::class)
        ->group(function () {
            Route::get('', 'index')->name('home');
            Route::get('/search', 'search')->name('messenger.search');
            Route::get('/user-info', 'fetchUserInfo')->name('messenger.user-info');
            Route::post('/send-message', 'sendMessage')->name('messenger.send-message');
            Route::get('/fetch-messages', 'fetchMessages')->name('messenger.fetch-messages');
            Route::get('/fetch-contacts', 'fetchContacts')->name('messenger.fetch-contacts');
            Route::get('/update-contact-item', 'updateContactItem')->name('messenger.update-contact-item');
            Route::put('/make-seen', 'makeSeen')->name('messenger.make-seen');
            Route::post('/favorite', 'favorite')->name('messenger.favorite');
            Route::get('/fetch-favorite', 'fetchFavorite')->name('messenger.fetch-favorite');
            Route::delete('/delete-message', 'deleteMessage')->name('messenger.delete-message');
        });
});
