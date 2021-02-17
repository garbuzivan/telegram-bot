<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Controllers;

use App\Http\Controllers\Controller;
use GarbuzIvan\TelegramBot\Models\TgBotChat;
use GarbuzIvan\TelegramBot\Models\TgBotChatUsers;
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
        if (!request()->has('chat')) {
            exit();
        }
        $chatID = request()->input('chat');
        $data = [];
        $usersChat = TgBotChatUsers::where('chat_id', $chatID)->get();
        foreach ($usersChat as $user) {
            $userInfo = TgBotUser::where('tg_id', $user->user_id)->first();
            $dataUser = $userInfo->toArray();

            $chats = [];
            $chatsDB = $userInfo->chats;
            foreach ($chatsDB as $chat) {
                $chats[$chat->chat->chat_title] = $chat->chat->toArray();
            }

            $dataUser['chats'] = $chats;
            $data[$userInfo->fullname()] = $dataUser;
        }
        dd($data);
    }

    public function user()
    {
        if (!request()->has('user')) {
            exit();
        }
        $userSearch = '%' . request()->input('user') . '%';
        $userDB = TgBotUser::where('username', 'like', $userSearch)
            ->orWhere('first_name', 'like', $userSearch)
            ->orWhere('last_name', 'like', $userSearch)
            ->get();
        $data = [];
        foreach ($userDB as $userInfo) {
            $dataUser = $userInfo->toArray();
            $chats = [];
            $chatsDB = $userInfo->chats;
            foreach ($chatsDB as $chat) {
                $chats[$chat->chat->chat_title] = $chat->chat->toArray();
            }
            $dataUser['chats'] = $chats;
            $data[$userInfo->fullname()] = $dataUser;
        }
        dd($data);
    }
}
