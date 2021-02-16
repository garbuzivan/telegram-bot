<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot;

use Carbon\Carbon;
use GarbuzIvan\TelegramBot\Models\TgBotChat;
use GarbuzIvan\TelegramBot\Models\TgBotChatUsers;
use GarbuzIvan\TelegramBot\Models\TgBotTimer;
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
    protected static ?TgBotUser $user = null;

    /**
     * @var TgBotUser|null
     */
    protected static ?TgBotUser $userReply = null;

    /**
     * @var TgBotChat|null
     */
    protected static ?TgBotChat $chat = null;

    /**
     * @param Configuration $config
     */
    public static function setApi(Configuration $config): void
    {
        self::$config = $config;
        self::$telegram = new TgApiBot($config->getToken());
        self::setParam(self::$telegram->getWebhookUpdate());
        self::parserWebHook();
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
        self::$userReply = $userReply;
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
        $parser = new ParserBot();
        if (!is_null(self::getParam('message.from'))) {
            self::setUser($parser->getUser(self::getParam('message.from')));
            self::setUserReply($parser->getUser(self::getParam('message.reply_to_message.from')));
            self::setChat($parser->getChat());
            $parser->updateChatUser();
            $parser->newChatUser();
            $parser->deleteChatUser();
            $parser->updateAdmin();
            $parser->userRename();
            $parser->newMessage(self::getParam('message'), self::getParam('update_id'));
        }
    }

    /**
     * @return array
     */
    public static function getCommands(): array
    {
        $commands = [];
        $commandsClass = TgSession::$config->getCommands();
        foreach ($commandsClass as $class) {
            $classNew = new $class;
            $commands[$classNew->name] = $classNew->description;
        }
        return $commands;
    }

    /**
     * @return string
     */
    public static function getCall(): ?string
    {
        $text = self::aliasComand();
        if (is_null($text)) {
            return null;
        }
        $text = str_replace(['/ ', '! '], null, $text);
        $str = explode(' ', $text, 2);
        $comand = mb_strtolower($str[0]);
        $comand = str_replace(self::$config->getBotNames(), null, $comand);
        return $comand;
    }

    /**
     * @return string|null
     */
    public static function getCallParam(): ?string
    {
        $text = self::aliasComand();
        if (is_null($text)) {
            return null;
        }
        $str = explode(' ', $text, 2);
        return $str[1] ?? null;
    }

    /**
     * @param TgBotChatUsers $user
     * @param string $param
     * @param int $strtotime
     * @return bool
     */
    public static function getTimer(TgBotChatUsers $user, string $param, int $strtotime = 6): bool
    {
        $timerUser = TgBotTimer::where('user_id', $user->user_id)->where('created_at', '>', Carbon::now()->subHours($strtotime))->get();
        foreach ($timerUser as $timer) {
            if ($timer->param != $param) {
                continue;
            }
            return false;
        }
        TgBotTimer::where('user_id', $user->user_id)->where('param', $param)->delete();
        return true;
    }

    /**
     * @param string $text
     * @return string
     */
    public static function aliasComand(): ?string
    {
        $text = self::getParam('message.text');
        if (is_null($text)) {
            return null;
        }
        $alias = [
            '! ' => '!',
            '/ ' => '/',
            'ян покажи' => '!покажи',
            'ян где' => '!где',
            'ян кто' => '!кто',
            'ян команды' => '/help',
            '!команды' => '/help',
        ];
        $text = str_replace(array_keys($alias), array_values($alias), mb_strtolower($text));
        return $text;
    }
}
