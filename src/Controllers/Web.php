<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Controllers;

use App\Http\Controllers\Controller;

class Web extends Controller
{
    public function webhook()
    {
        dd(request());
    }
}
