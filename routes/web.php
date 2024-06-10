<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPasswordController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    //Forgot password
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
    Route::post('/forgot-password', [ResetPasswordController::class, 'passwordEmail']);
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'passwordReset'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'passwordUpdate'])->name('password.update');

    //Tickets
    Route::get('ticket/close/{token}',[TicketController::class, 'closeTicket'])->name('close.ticket');
    Route::get('ticket/reset-picture/{token}',[TicketController::class, 'resetPicture'])->name('reset.picture');
    Route::get('ticket/reset-name/{token}',[TicketController::class, 'resetName'])->name('reset.name');
    Route::get('ticket/reset-description/{token}',[TicketController::class, 'resetDescription'])->name('reset.description');
    Route::get('ticket/reset-password/{token}',[TicketController::class, 'resetPassword'])->name('reset.password');
    Route::get('ticket/reset-nickname/{token}',[TicketController::class, 'resetNickname'])->name('reset.nickname');
});
