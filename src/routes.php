<?php

use Illuminate\Support\Facades\Route;

Route::get('/tgbot', 'GarbuzIvan\TelegramBot\TelegramBot@webhook');
