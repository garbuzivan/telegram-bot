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
        if (isset($this->config->param['message']['from']['id'])) {
            $from = $this->config->param['message']['from'];
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
        if (isset($this->config->param['message']['reply_to_message']['from']['id'])) {
            $from = $this->config->param['message']['reply_to_message']['from'];
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
        if (isset($this->config->param['message']['chat']['id'])) {
            $chatID = $this->config->param['message']['chat']['id'];
            $chat = TgBotChat::where('chat_id', $chatID)->first();
            if (is_null($chat)) {
                $chatTitle = $chatID < 0 ?
                    $this->config->param['message']['chat']['title']
                    : 'ЛС: ' . TgBotUser::where('id', $chatID)->first()->fullName;
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
        if (isset($this->config->param['message']['message_id'])) {
            $messageId = $this->config->param['message']['message_id'];
            $message = TgBotMessage::where('message_id', $messageId)->first();
            if (is_null($message)) {
                $insert = [
                    'update_id' => $this->config->param['update_id'],
                    'message_id' => $messageId,
                    'from_id' => $this->config->param['message']['from']['id'],
                    'chat_id' => $this->config->param['message']['chat']['id'],
                    'chat_title' => $this->config->param['message']['chat']['title'] ?? null,
                    'date' => $this->config->param['message']['date'] ?? null,
                    'reply_message_id' => $this->config->param['message']['reply_to_message']['message_id'] ?? null,
                    'reply_from_id' => $this->config->param['message']['reply_to_message']['from']['id'] ?? null,
                    'text' => $this->config->param['message']['text'] ?? null,
                    'json' => json_encode($this->config->param),
                ];
                return TgBotChat::create($insert);
            }
        }
        return null;
    }

}