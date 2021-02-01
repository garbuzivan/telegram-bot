<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Controllers;

use App\Http\Controllers\Controller;
use GarbuzIvan\TelegramBot\TelegramBot;

class Web extends Controller
{
    public function webhook()
    {
        (new TelegramBot())->webhook();
    }
    public function message()
    {
        (new TelegramBot())->message();
    }
    public function messageBot()
    {
        (new TelegramBot())->messageBot();
    }
}
