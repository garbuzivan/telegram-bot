<?php

use Illuminate\Support\Facades\Route;

Route::get('/tgwebhook', '\GarbuzIvan\TelegramBot\Controllers\Web@webhook');
Route::post('/tgwebhook', '\GarbuzIvan\TelegramBot\Controllers\Web@webhook');

Route::get('/message', '\GarbuzIvan\TelegramBot\Controllers\Web@message');
Route::get('/botmessage', '\GarbuzIvan\TelegramBot\Controllers\Web@messageBot');
Route::get('/chats', '\GarbuzIvan\TelegramBot\Controllers\Web@chats');
Route::get('/users', '\GarbuzIvan\TelegramBot\Controllers\Web@users');
