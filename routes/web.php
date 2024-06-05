<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/audit-trail', function () {
        return view('audittrail');
    })->name('audit-trail');

    Route::get('/party', function () {
        return view('crud');
    })->name('party');

    Route::get('/party/{id}', function($id) {
        return redirect('/dashboard?type=candidate&pid='.$id);
    });

    Route::get('/candidate/{id}', [App\Http\Controllers\Vote::class, 'index'])->name('vote.view');
    Route::get('/candidate/{id}/vote', [App\Http\Controllers\Vote::class, 'vote'])->name('vote.vote');
});

Route::get('/two-factor-challenge', function () {
    return view('two-factor-challenge');
})->name('two-factor.login');


Route::middleware(['two_factor'])->group(function () {
    Route::get('/votes', function () {
        return view('votes');
    })->name('votes');
});

Route::get('/test', function () {
    $cs = new \App\Actions\Voting\CalculateSeats();
    dd($cs->calculateSeats('02-01-2024', '02-25-2024'));
});
