<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;
use GarbuzIvan\TelegramBot\TgSession;
use Telegram\Bot\FileUpload\InputFile;

class View extends AbstractCommand
{
    /**
     * @var string Command Name
     */
    public string $name = "!покажи";

    /**
     * @var string Command Description
     */
    public string $description = "Ян покажи";

    /**
     * @inheritdoc
     */
    public function handler($request, Closure $next)
    {
        if (
            isset($request['View'])
            || !in_array(TgSession::getCall(), ['/view', '!покажи', '!янпокажи', 'покажи'])
            || is_null(TgSession::getCallParam())
            || mb_strlen(trim(TgSession::getCallParam())) == 0
        ) {
            return $next($request);
        }

        $fact = trim(TgSession::getCallParam());
        $content = file_get_contents('https://pixabay.com/api/?key=19741666-e0fbf1c991dedc9d161c31f92&q=' . urlencode($fact));
        $content = json_decode($content, true);
        $imgs = $content['hits'];

        if (!is_array($imgs) || count($imgs) == 0) {
            return $next($request);
        }
        $rand = array_rand($imgs);
        $url = $imgs[$rand]['webformatURL'];

        TgSession::getApi()->sendPhoto([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'photo' => InputFile::create($url, $fact),
            'caption' => TgSession::getUser()->fullname() . ': ' . $fact,
        ]);

        TgSession::getApi()->deleteMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'message_id' => TgSession::getParam('message.message_id'),
        ]);

        $request['View'] = true;
        return $next($request);
    }
}
