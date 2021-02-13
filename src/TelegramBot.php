<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot;

use GarbuzIvan\TelegramBot\Models\TgBotChat;
use GarbuzIvan\TelegramBot\Models\TgBotUser;
use Illuminate\Pipeline\Pipeline;

class TelegramBot
{
    /**
     * @var Configuration $config
     */
    protected Configuration $config;

    /**
     * @var ?TgBotUser
     */
    public ?TgBotUser $userFrom = null;

    /**
     * @var ?TgBotUser
     */
    public ?TgBotUser $userReply = null;

    /**
     * @var ?TgBotChat
     */
    public ?TgBotChat $chat = null;

    /**
     * @var null
     */
    public $message = null;

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

    public function webhook()
    {
        TgSession::setApi($this->config);
        $request = null;
        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => json_encode(TgSession::$config->getCommands()),
        ]);
        return app(Pipeline::class)
            ->send($request)
            ->via('handler')
            ->through(TgSession::$config->getCommands())
            ->thenReturn();
    }
}
