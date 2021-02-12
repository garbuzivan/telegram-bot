<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot;

use GarbuzIvan\TelegramBot\Models\TgBotChat;
use GarbuzIvan\TelegramBot\Models\TgBotMessage;
use GarbuzIvan\TelegramBot\Models\TgBotUser;

class ParserBot
{
    /**
     * @var Configuration $config
     */
    protected Configuration $config;

    /**
     * Configuration constructor.
     * @param Configuration|null $config
     */
    public function __construct(Configuration $config = null)
    {
        if (is_null($config)) {
            $config = new Configuration();
        }
        if ($config instanceof Configuration) {
            $this->config = $config;
        }
    }

    /**
     * Create if not exists user
     *
     * @return TgBotUser|null
     */
    public function getUserFrom(): ?TgBotUser
    {
        if (request()->has('message.from.id')) {
            $from = request()->input('message.from');
            return $this->firstOrCreate($from);
        }
        return null;
    }

    /**
     * Create if not exists user in reply
     *
     * @return TgBotUser|null
     */
    public function getUserReply(): ?TgBotUser
    {
        if (request()->has('message.reply_to_message.from.id')) {
            $from = request()->input('message.reply_to_message.from');
            return $this->firstOrCreate($from);
        }
        return null;
    }

    /**
     * Method get or create user
     *
     * @param $from
     * @return TgBotUser
     */
    public function firstOrCreate($from): TgBotUser
    {
        $user = TgBotUser::where('tg_id', $from['id'])->first();
        if (is_null($user)) {
            $insert = [
                'tg_id' => $from['id'],
                'is_bot' => $from['is_bot'] == false ? 0 : 1,
                'username' => $from['username'] ?? null,
                'first_name' => $from['first_name'] ?? null,
                'last_name' => $from['last_name'] ?? null,
                'title' => 'Новичок',
            ];
            return TgBotUser::create($insert);
        }
        return $user;
    }

    /**
     * @return TgBotChat|null
     */
    public function getChat(): ?TgBotChat
    {
        if (request()->has('message.chat.id')) {
            $chatID = request()->input('message.chat.id');
            $chat = TgBotChat::where('chat_id', $chatID)->first();
            if (is_null($chat)) {
                $chatTitle = $chatID < 0 ?
                    request()->input('message.chat.title')
                    : TgBotUser::where('id', $chatID)->first()->fullName;
                $insert = [
                    'chat_id' => $chatID,
                    'chat_title' => $chatTitle,
                ];
                return TgBotChat::create($insert);
            }
            return $chat;
        }
        return null;
    }

    public function newMessage()
    {
        if (request()->has('message.message_id')) {
            $messageId = request()->input('message.message_id');
            $message = TgBotMessage::where('message_id', $messageId)->first();
            if (is_null($message)) {
                $insert = [
                    'update_id' => request()->input('update_id'),
                    'message_id' => $messageId,
                    'from_id' => request()->input('message.from.id'),
                    'chat_id' => request()->input('message.chat.id'),
                    'chat_title' => request()->input('message.chat.title') ?? null,
                    'date' => request()->input('message.date') ?? null,
                    'reply_message_id' => request()->input('message.reply_to_message.message_id') ?? null,
                    'reply_from_id' => request()->input('message.reply_to_message.from.id') ?? null,
                    'text' => request()->input('message.text') ?? null,
                    'json' => json_encode(request()->all()),
                ];
                return TgBotChat::create($insert);
            }
        }
        return null;
    }

}
