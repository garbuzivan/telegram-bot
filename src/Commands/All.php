<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;
use GarbuzIvan\TelegramBot\TgSession;

class All extends AbstractCommand
{
    /**
     * @var string Command Name
     */
    public string $name = "/all";

    /**
     * @var string Command Description
     */
    public string $description = "Позвать всех";

    /**
     * @inheritdoc
     */
    public function handler($request, Closure $next)
    {
        if (isset($request['All']) || !in_array(TgSession::getCall(), [$this->name, "@все"])) {
            return $next($request);
        }

        $count = 0;
        $text = null;
        $users = TgSession::getChat()->users;

        foreach ($users as $user) {
            if ($user->active == 0 || TgSession::getUser()->tg_id == $user->user_id) {
                continue;
            }
            $count++;
            if (!is_null($text)) {
                $text .= ', ';
            }
            $text .= $user->info->link();
            if ($count > 10) {
                $this->send($text);
                $count = 0;
                $text = null;
            }
        }
        $this->send($text);

        TgSession::getApi()->deleteMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'message_id' => TgSession::getParam('message.message_id'),
        ]);

        $request['Rank'] = true;
        return $next($request);
    }

    public function send(string $text = null)
    {
        if (!is_null($text)) {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => TgSession::getUser()->link() . ': <b>' . TgSession::getCallParam() . "</b>\n" . $text,
            ]);
        }
    }
}
