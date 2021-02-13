<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Controllers;

use App\Http\Controllers\Controller;
use GarbuzIvan\TelegramBot\Models\TgBotChat;
use GarbuzIvan\TelegramBot\Models\TgBotUser;
use GarbuzIvan\TelegramBot\TelegramBot;

class Web extends Controller
{
    public function webhook()
    {
        return (new TelegramBot())->webhook();
    }
    public function message()
    {
        return (new TelegramBot())->message();
    }
    public function messageBot()
    {
        return (new TelegramBot())->messageBot();
    }

    public function chats()
    {
        dd(TgBotChat::all());
    }

    public function users()
    {
        dd(public_path(), TgBotUser::all());
    }
}
