<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;
use GarbuzIvan\TelegramBot\Dict;
use GarbuzIvan\TelegramBot\Models\TgBotUser;
use GarbuzIvan\TelegramBot\TgSession;

class Who extends AbstractCommand
{
    /**
     * @var string Command Name
     */
    public string $name = "!кто";

    /**
     * @var string Command Description
     */
    public string $description = "Ян кто";

    /**
     * @inheritdoc
     */
    public function handler($request, Closure $next)
    {
        if (
            isset($request['Who'])
            || !in_array(TgSession::getCall(), ['/who', '!кто', '!янкто', 'кто'])
            || is_null(TgSession::getCallParam())
            || mb_strlen(trim(TgSession::getCallParam())) == 0
        ) {
            return $next($request);
        }

        $user = TgSession::getChat()->users->random();
        $userLink = TgBotUser::where('tg_id', $user->user_id)->first()->link();
        $text = trim(str_replace('?', null, TgSession::getCallParam()));
        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => $userLink . ' ' . $text . "\n\n\xF0\x9F\x9A\xA9 " . TgSession::getUser()->link(),
        ]);

        TgSession::getApi()->deleteMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'message_id' => TgSession::getParam('message.message_id'),
        ]);

        $request['Who'] = true;
        return $next($request);
    }
}
