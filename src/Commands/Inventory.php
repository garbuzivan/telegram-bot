<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;
use GarbuzIvan\TelegramBot\TgSession;

class Inventory extends AbstractCommand
{
    /**
     * @var string Command Name
     */
    public string $name = "/inventory";

    /**
     * @var string Command Description
     */
    public string $description = "Инвентарь";

    /**
     * @inheritdoc
     */
    public function handler($request, Closure $next)
    {
        if (isset($request['inventory']) || !in_array(TgSession::getCall(), [$this->name, '!инвентарь', 'инвентарь', '!предметы', 'предметы'])) {
            return $next($request);
        }

        $user = TgSession::getUserReply();
        if (is_null($user)) {
            $user = TgSession::getUser();
        }

        $inventory = null;
        foreach ($user->inventory as $item) {
            $inventory .= "\n\xE2\x9C\x94 " . $item->name;
        }

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => 'Имущество ' . $user->link() . ":" . (is_null($inventory) ? "\n<b>Ничего нет</b>" : $inventory),
        ]);

        TgSession::getApi()->deleteMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'message_id' => TgSession::getParam('message.message_id'),
        ]);

        $request['Rank'] = true;
        return $next($request);
    }
}
