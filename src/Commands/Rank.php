<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;
use GarbuzIvan\TelegramBot\TgSession;
use Telegram\Bot\Exceptions\TelegramSDKException;

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
        $request = $this->profile($request);
        return $next($request);
    }

    /**
     * @param $request
     * @return mixed
     * @throws TelegramSDKException
     */
    public function profile($request)
    {
        if (isset($request['Rank']) || !in_array(TgSession::getCall(), ['/rank'])) {
            return $request;
        }

        $user = TgSession::getUserReply();
        if (is_null($user)) {
            $user = TgSession::getUser();
        }

        $text = "<b>Имя:</b> " . $user->link();
        $text .= "\n<b>Пол:</b> " . $user->getSex();
        $text .= "\n<b>Баланс:</b> " . number_format($user->money, 0, '', ' ') . " \xF0\x9F\x92\xB0";
        $text .= "\n<b>Сообщений:</b> " . $user->message_count . " \xF0\x9F\x92\xAC";
        $text .= "\n<b>Пригласил(а):</b> " . $user->friend . " \xF0\x9F\x91\xA5";
        if ($user->sex == 'парень') {
            $text .= "\n<b>Член:</b> " . $user->penis . 'см';
        }
        if ($user->sex == 'девушка') {
            $alpha = ['A', 'B', 'C', 'D', 'E', 'F'];
            $text .= "\n<b>Сиськи:</b> " . $user->boobs[0] . $alpha[$user->boobs[1]];
            $text .= "\n<b>Влагалище:</b> " . $user->vagina . 'см';
        }
        $text .= "\n<b>Лайки:</b> " . $user->like . " \xF0\x9F\x91\x8D";
        $text .= "\n<b>Дизлайки:</b> " . $user->dislike . " \xF0\x9F\x91\x8E";
        $text .= "\n\n" . $user->tg_id;
        $text .= "\n\n\xF0\x9F\x98\x8F " . TgSession::getUser()->link() . "\n";

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => $text,
        ]);

        TgSession::getApi()->deleteMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'message_id' => TgSession::getParam('message.message_id'),
        ]);

        $request['Rank'] = true;
        return $request;
    }
}
