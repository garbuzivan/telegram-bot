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
        $text  = null;
        $users = TgSession::getChat()->users();
        foreach($users as $user){
            if($user->active == 0){
                continue;
            }
            $count++;
            if(!is_null($text)){
                $text .= ', ';
            }
            $text .= $user->info->link();
        }
        if(!is_null($text)){
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => $user->link() . ': ' . TgSession::getCallParam() . "\n" . $text,
            ]);
        }
        $request['Rank'] = true;
        return $next($request);
    }
}
