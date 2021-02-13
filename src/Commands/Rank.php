<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;
use GarbuzIvan\TelegramBot\TgSession;

class Rank extends AbstractCommand
{
    /**
     * @var string Command Name
     */
    public string $name = "/rank";

    /**
     * @var string Command Description
     */
    public string $description = "Посмотреть профиль";

    /**
     * @inheritdoc
     */
    public function handler($request, Closure $next)
    {
        if (isset($request['Rank']) || !in_array(TgSession::getCall(), ['/rank'])) {
            return $next($request);
        }

        $user = TgSession::getUserReply();
        if(is_null($user)){
            $user = TgSession::getUser();
        }

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => $user->link(),
        ]);

        $request['Rank'] = true;
        return $next($request);
    }
}
