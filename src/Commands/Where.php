<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;
use GarbuzIvan\TelegramBot\Dict;
use GarbuzIvan\TelegramBot\TgSession;

class Where extends AbstractCommand
{
    /**
     * @var string Command Name
     */
    public string $name = "!где";

    /**
     * @var string Command Description
     */
    public string $description = "Ян где";

    /**
     * @inheritdoc
     */
    public function handler($request, Closure $next)
    {
        if (
            isset($request['Where'])
            || !in_array(TgSession::getCall(), ['/where', '!где', '!янгде', 'где'])
            || is_null(TgSession::getCallParam())
            || mb_strlen(trim(TgSession::getCallParam())) == 0
        ) {
            return $next($request);
        }

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => trim(str_replace('?', null, TgSession::getCallParam())) .
                ' ' . Dict::rand(Dict::getWhere()) ."\n\n\xF0\x9F\x9A\xA9" . TgSession::getUser()->link(),
        ]);

        TgSession::getApi()->deleteMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'message_id' => TgSession::getParam('message.message_id'),
        ]);

        $request['Where'] = true;
        return $next($request);
    }
}
