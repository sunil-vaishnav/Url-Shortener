<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::match(['get','post'],'/shorturls', [ShortUrlController::class, 'index'])->name('shorturls');
});

Route::middleware('auth','role:SuperAdmin')->group(function () {
    Route::match(['get','post'],'/companies', [CompanyController::class, 'index'])
    ->name('companies');
    Route::match(['get','post'],'/companies/add', [CompanyController::class, 'add'])
    ->name('companies.add');
     Route::match(['get','post'],'/companies/edit/{id}', [CompanyController::class, 'edit']);
    Route::match(['get','post','delete'],'/companies/destroy/{id}', [CompanyController::class, 'destroy']);

    Route::match(['get','post'],'/invite/admin', [InvitationController::class, 'inviteAdmin'])->name('invite.admin');
});

Route::middleware('auth','role:Admin')->group(function () {
    Route::match(['get','post'],'/invite/member', [InvitationController::class, 'inviteMember'])
    ->name('invite.member');
});

Route::middleware('auth','role:SuperAdmin,Admin')->group(function () {
    Route::match(['get','post'],'/invitations', [InvitationController::class, 'index'])
    ->name('invitations');
});

Route::middleware('auth','role:Admin,Member')->group(function () {
    Route::match(['get','post'],'/shorturls/add', [ShortUrlController::class, 'add'])
    ->name('shorturl.add');
    Route::match(['get','post'],'/shorturls/edit/{id}', [ShortUrlController::class, 'edit']);
    Route::match(['get','post','delete'],'/shorturls/delete/{id}', [ShortUrlController::class, 'delete']);
});



Route::get('/s/{code}', [ShortUrlController::class, 'resolve']);

require __DIR__.'/auth.php';
