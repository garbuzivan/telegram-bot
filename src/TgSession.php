<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot;

use GarbuzIvan\TelegramBot\Models\TgBotChat;
use GarbuzIvan\TelegramBot\Models\TgBotUser;

class TgSession
{
    /**
     * @var Configuration $config
     */
    public static Configuration $config;

    /**
     * @var TgApiBot
     */
    protected static TgApiBot $telegram;

    /**
     * @var
     */
    protected static $param;

    /**
     * @var TgBotUser|null
     */
    protected static ?TgBotUser $user;

    /**
     * @var TgBotUser|null
     */
    protected static ?TgBotUser $userReply;

    /**
     * @var TgBotChat|null
     */
    protected static ?TgBotChat $chat;

    /**
     * @param Configuration $config
     */
    public static function setApi(Configuration $config): void
    {
        self::$config = $config;
        self::$telegram = new TgApiBot($config->getToken());
        self::setParam(self::$telegram->getWebhookUpdate());
        self::getApi()->sendMessage([
            'chat_id' => self::getParam('message.chat.id'),
            'text' => 'test ' . json_encode(self::getParam())
        ]);
        //self::parserWebHook();
    }

    /**
     * @return TgApiBot
     */
    public static function getApi(): TgApiBot
    {
        return self::$telegram;
    }

    /**
     * @param $param
     */
    public static function setParam($param): void
    {
        self::$param = $param;
    }

    /**
     * @param string|null $path
     * @return mixed
     */
    public static function getParam(?string $path = null)
    {
        if (is_null($path)) {
            return self::$param;
        }
        $path = explode('.', $path);
        $value = self::$param;
        foreach ($path as $dir) {
            if (isset($value[$dir])) {
                $value = $value[$dir];
            } else {
                return null;
            }
        }
        return $value;
    }

    /**
     * @param TgBotUser|null $user
     */
    public static function setUser(?TgBotUser $user): void
    {
        self::$user = $user;
    }

    /**
     * @return TgBotUser|null
     */
    public static function getUser(): ?TgBotUser
    {
        return self::$user;
    }

    /**
     * @param TgBotUser|null $userReply
     */
    public static function setUserReply(?TgBotUser $userReply): void
    {
        self::$user = $userReply;
    }

    /**
     * @return TgBotUser|null
     */
    public static function getUserReply(): ?TgBotUser
    {
        return self::$userReply;
    }

    /**
     * @param TgBotChat|null $chat
     */
    public static function setChat(?TgBotChat $chat): void
    {
        self::$chat = $chat;
    }

    /**
     * @return TgBotChat|null
     */
    public static function getChat(): ?TgBotChat
    {
        return self::$chat;
    }

    /**
     *
     */
    protected static function parserWebHook(): void
    {
        file_put_contents(public_path('tgg.txt'), json_encode(self::getParam()) . "\n", 8);
        file_put_contents(public_path('tgg.txt'), json_encode(self::getParam('message.from')) . "\n\n", 8);
        $parser = new ParserBot();
        self::setUser($parser->getUser(self::getParam('message.from')));
        self::setUserReply($parser->getUser(self::getParam('message.reply_to_message.from')));

        self::getApi()->sendMessage([
            'chat_id' => self::getParam('message.chat.id'),
            'text' => json_encode(self::getParam())
        ]);
        self::setChat($parser->getChat());
        $parser->newMessage(self::getParam('message'), self::getParam('update_id'));
    }
}
