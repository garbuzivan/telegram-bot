<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;
use GarbuzIvan\TelegramBot\TgSession;

class Start extends AbstractCommand
{
    /**
     * @var string Command Name
     */
    public string $name = "/start";

    /**
     * @var string Command Description
     */
    public string $description = "Список команд";

    /**
     * @inheritdoc
     */
    public function handle($request, Closure $next)
    {
        if(TgSession::setParam('')) {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => 'Император ЯН приветствует тебя',
            ]);

            $response = '';
            $commands = TgSession::getCommands();
            foreach ($commands as $name => $description) {
                $response .= sprintf('/%s - %s' . PHP_EOL, $name, $description);
            }
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => $response,
            ]);
        }
        return $next($request);
    }
}
