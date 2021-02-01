<?php

use Illuminate\Support\Facades\Route;

Route::get('/tgwebhook', '\GarbuzIvan\TelegramBot\Controllers\Web@webhook');
