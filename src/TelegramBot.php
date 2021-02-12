<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot;

use GarbuzIvan\TelegramBot\Models\TgBotChat;
use GarbuzIvan\TelegramBot\Models\TgBotUser;
use phpDocumentor\Reflection\File;

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
        $this->config->telegram = new TgApiBot($this->config->getToken());
        $this->config->param = $this->config->telegram->getWebhookUpdate();
        file_put_contents(public_path('tg.tmp'), json_encode($this->config->param) . "\n\n", FILE_APPEND);
        file_put_contents('tg.tmp', json_encode($this->config->param) . "\n\n", FILE_APPEND);
        exit();
        $parser = new ParserBot($this->config);
        $this->userFrom = $parser->getUserFrom();
        $this->userReply = $parser->getUserReply();
        $this->chat = $parser->getChat();
        $this->config->telegram->sendMessage(['chat_id' => $this->config->param['message']['chat']['id'], 'text' => "Отправьте текстовое сообщение."]);
        $this->config->telegram->sendMessage(['chat_id' => $this->config->param['message']['chat']['id'], 'text' => json_encode($this->config->param)]);
        $this->message = $parser->newMessage();
    }

    public function message()
    {

    }

    public function messageBot()
    {

    }
}
