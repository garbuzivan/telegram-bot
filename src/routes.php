<?php

use Illuminate\Support\Facades\Route;

Route::get('/tgwebhook', '\GarbuzIvan\TelegramBot\Controllers\Web@webhook');
Route::post('/tgwebhook', '\GarbuzIvan\TelegramBot\Controllers\Web@webhook');

Route::post('/message', '\GarbuzIvan\TelegramBot\Controllers\Web@message');
Route::post('/botmessage', '\GarbuzIvan\TelegramBot\Controllers\Web@messageBot');
Route::post('/chats', '\GarbuzIvan\TelegramBot\Controllers\Web@chats');
Route::post('/users', '\GarbuzIvan\TelegramBot\Controllers\Web@users');
