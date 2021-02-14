<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;
use GarbuzIvan\TelegramBot\TgSession;

class Sex extends AbstractCommand
{
    /**
     * @var string Command Name
     */
    public string $name = "!яншалун";

    /**
     * @var string Command Description
     */
    public string $description = "Команды !яншалун";

    /**
     * @inheritdoc
     */
    public function handler($request, Closure $next)
    {
        if (isset($request['All']) || !in_array(TgSession::getCall(), [$this->name])) {
            return $next($request);
        }

        $user = TgSession::getUserReply();
        if (is_null($user)) {
            $user = TgSession::getUser();
        }

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => $user->link() . ': ' . $this->name . "\n" . TgSession::getCallParam(),
        ]);

        $request['Rank'] = true;
        return $next($request);
    }
}
