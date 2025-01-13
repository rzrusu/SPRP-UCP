<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\MapController;
use Livewire\Volt\Volt;

Route::get('/', function () {
    // check if user is logged in
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('index');


Volt::route('/updates', 'pages.release-notes.index')->name('updates');
Volt::route('/updates/{slug}', 'pages.release-notes.view')->name('update.view');

Volt::route('/vote', 'pages.vote.index')->name('vote');
Volt::route('/vote/{slug}', 'pages.vote.view')->name('vote.view');

Volt::route('/connections', 'pages.connections.index')->name('connections');

Volt::route('/characters', 'pages.characters.index')
    ->middleware(['auth', 'verified'])
    ->name('characters');

Volt::route('/characters/create', 'pages.characters.create')->name('characters.create');
//Volt::route('/characters/{}', 'pages.release-notes.view')->name('update.view');

Route::get('dashboard', [IndexController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Volt::route('/account', 'profile')
    ->middleware(['auth'])
    ->name('account');

Volt::route('/adminrecord', 'pages.adminrecord.index')
    ->middleware(['auth', 'verified'])
    ->name('adminrecord');

Route::get('/map', [MapController::class, 'index'])->name('map');

require __DIR__.'/auth.php';
