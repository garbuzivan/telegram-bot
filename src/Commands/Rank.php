<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;
use GarbuzIvan\TelegramBot\Models\TgBotUser;
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
        $request = $this->setSex($request);
        $request = $this->setPenis($request);
        $request = $this->setBoobs($request);
        $request = $this->setVagina($request);
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
            $boobs = $user->boobs > 0 ? str_split((string)$user->boobs, 1) : 0;
            $text .= "\n<b>Сиськи:</b> " . ($user->boobs > 0 ? $boobs[0] . $alpha[$boobs[1]] : 'размер сисек на нащупан');
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

    private function setSex($request)
    {
        $sexList = [
            '/boy' => 'парень',
            '/girl' => 'девушка',
            '!парень' => 'парень',
            '!девушка' => 'девушка',
        ];
        if (isset($request['setSex']) || !in_array(TgSession::getCall(), array_keys($sexList))) {
            return $request;
        }

        $user = TgSession::getUserReply();
        if (is_null($user)) {
            $user = TgSession::getUser();
        }

        TgBotUser::where('id', $user->id)->update(['sex' => $sexList[TgSession::getCall()]]);

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => "\xF0\x9F\x91\x80 Опустив руку в трусики, можно сказать, что " . $user->link() .
                ' <b>' . $sexList[TgSession::getCall()] . "</b> \xF0\x9F\x98\x9C!",
        ]);

        TgSession::getApi()->deleteMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'message_id' => TgSession::getParam('message.message_id'),
        ]);

        $request['setSex'] = true;
        return $request;
    }

    private function setPenis($request)
    {
        if (
            isset($request['setPenis'])
            || !in_array(TgSession::getCall(), ['член', '!член', '!пенис', '!писюн', '/penis'])
        ) {
            return $request;
        }

        if (is_null(TgSession::getUserReply())) {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => "\xF0\x9F\x91\x80 Измерять размер мужского достоинства самому СЕБЕ НЕЛЬЗЯ!",
            ]);
            $request['setPenis'] = false;
            return $request;
        }

        $user = TgSession::getUserReply();

        if ($user->sex != 'парень') {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => "\xF0\x9F\x91\x80 Измерять размер мужского достоинства можно только парням!",
            ]);
            $request['setSex'] = false;
            return $request;
        }

        $rand = rand(3, 25);
        TgBotUser::where('id', $user->id)->update(['penis' => $rand]);

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => "\xF0\x9F\x91\x80 Член у " . $user->link() .
                ' <b>' . $rand . "</b> см. \xF0\x9F\x98\x9C!",
        ]);

        TgSession::getApi()->deleteMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'message_id' => TgSession::getParam('message.message_id'),
        ]);

        $request['setPenis'] = true;
        return $request;
    }

    private function setBoobs($request)
    {
        if (
            isset($request['setSex'])
            || !in_array(TgSession::getCall(), ['сиськи', '!сиськи', '!boobs', '/boobs'])
        ) {
            return $request;
        }

        if (is_null(TgSession::getUserReply())) {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => "\xF0\x9F\x91\x80 Измерять размер сисек самой СЕБЕ НЕЛЬЗЯ!",
            ]);
            $request['setBoobs'] = false;
            return $request;
        }

        $user = TgSession::getUserReply();

        if ($user->sex != 'девушка') {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => "\xF0\x9F\x91\x80 Измерять размер сисек можно только девушкам!",
            ]);
            $request['setBoobs'] = false;
            return $request;
        }

        $alpha = ['A', 'B', 'C', 'D', 'E', 'F'];
        $boobsSize = [10, 11, 12, 21, 22, 23, 31, 32, 33, 34, 42, 43, 44, 45, 53, 54, 55, 64, 65];
        $rand = $boobsSize[array_rand($boobsSize)];
        TgBotUser::where('id', $user->id)->update(['boobs' => $rand]);

        $rand = str_split((string)$rand, 1);
        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => "\xF0\x9F\x91\x80 Размер сисек у " . $user->link() .
                ' <b>' . $rand[0] . $alpha[$rand[1]] . "</b> \xF0\x9F\x98\x9C!",
        ]);

        TgSession::getApi()->deleteMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'message_id' => TgSession::getParam('message.message_id'),
        ]);

        $request['setBoobs'] = true;
        return $request;
    }

    private function setVagina($request)
    {
        if (
            isset($request['setVagina'])
            || !in_array(TgSession::getCall(), ['влагалище', '!влагалище', 'вагина', '!вагина', 'пизда', '!пизда', '!vagina', '/vagina'])
        ) {
            return $request;
        }

        if (is_null(TgSession::getUserReply())) {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => "\xF0\x9F\x91\x80 Измерять размер влагалища самой СЕБЕ НЕЛЬЗЯ!",
            ]);
            $request['setVagina'] = false;
            return $request;
        }

        $user = TgSession::getUserReply();

        if ($user->sex != 'девушка') {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => "\xF0\x9F\x91\x80 Измерять размер влагалища можно только девушкам!",
            ]);
            $request['setVagina'] = false;
            return $request;
        }

        $rand = rand(3, 25);
        TgBotUser::where('id', $user->id)->update(['vagina' => $rand]);

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => "\xF0\x9F\x91\x80 Размер влагалища у " . $user->link() .
                ' <b>' . $rand . "</b> см. \xF0\x9F\x98\x9C!",
        ]);

        TgSession::getApi()->deleteMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'message_id' => TgSession::getParam('message.message_id'),
        ]);

        $request['setVagina'] = true;
        return $request;
    }
}
