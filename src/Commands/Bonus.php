<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Carbon\Carbon;
use Closure;
use GarbuzIvan\TelegramBot\Models\TgBotTimer;
use GarbuzIvan\TelegramBot\Models\TgBotUser;
use GarbuzIvan\TelegramBot\TgSession;

class Bonus extends AbstractCommand
{
    /**
     * @var string Command Name
     */
    public string $name = "/bonus";

    /**
     * @var string Command Description
     */
    public string $description = "Бонус";

    /**
     * @inheritdoc
     */
    public function handler($request, Closure $next)
    {
        $request = $this->bonus($request);
        $request = $this->view($request);
        return $next($request);
    }

    private function view($request)
    {
        if (is_null(TgSession::getUser()) || isset($request['shop']) || !in_array(TgSession::getCall(), ['/bonus', '!бонус', 'бонус'])) {
            return $request;
        }

        $timerUser = TgBotTimer::where('user_id', TgSession::getUser()->tg_id)
            ->where('param', 'bonus')
            ->where('created_at', '>', Carbon::now()->subHours(6))
            ->first();

        if (!is_null($timerUser)) {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => "Бонус можно получить не чаще чем раз в 6 часов.\nВы получили в " .
                    $timerUser->created_at->format('H:i:s'),
            ]);
            return $request;
        }
        TgBotTimer::where('user_id', TgSession::getUser()->tg_id)->where('param', 'bonus')->delete();

        $randMax = rand(3000, 15000);
        $itemInfo = ':' . TgSession::getUser()->tg_id . ':' . $randMax;
        $bombs = collect(range(1, 25))->random(14)->toArray();

        $inline_keyboard = [];
        $lineCount = 0;
        $line = [];
        for ($i = 1; $i <= 25; $i++) {
            $lineCount++;
            $line[] = ['text' => "\xF0\x9F\x8E\x81", 'callback_data' => 'bonus:' . $i . ':' .
                (in_array($i, $bombs) ? 1 : 0) . ':0:3' . $itemInfo];
            if ($lineCount == 5) {
                $inline_keyboard[] = $line;
                $line = [];
                $lineCount = 0;
            }
        }

        $keyboard = array("inline_keyboard" => $inline_keyboard);
        $reply_markup = json_encode($keyboard);
        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => TgSession::getUser()->link() . " ты можешь получить бесплатный денежный бонус на свой счет до " .
                $randMax . " \xF0\x9F\x92\xB0 .\n" .
                "Собери три \xF0\x9F\x8E\x81 и найди \xF0\x9F\x92\xB8 или \xF0\x9F\x92\xA9!",
            'reply_markup' => $reply_markup,
        ]);

        $request['shop'] = true;
        return $request;
    }

    private function bonus($request)
    {
        if (is_null(TgSession::getParam('callback_query.data'))) {
            return $request;
        }
        $param = TgSession::getParam('callback_query.data');
        $param = explode(':', $param);
        if (!isset($param[5]) || $param[0] != 'bonus') {
            return $request;
        }
        if ($param[5] != TgSession::getParam('callback_query.from.id')) {
            $this->callbackMessage('Не раззивай роток на чужой бонус!');
            return $request;
        }
        if ($param[3] == 1) {
            $this->callbackMessage('Вы уже открывали эту ячейку');
            return $request;
        }
        if ($param[4] == 0) {
            TgSession::getApi()->deleteMessage([
                'chat_id' => TgSession::getParam('callback_query.message.chat.id'),
                'message_id' => TgSession::getParam('callback_query.message.message_id'),
            ]);
            return $request;
        }
        if($param[4] == 1 || $param[2] == 1){
            $user = TgBotUser::where('tg_id', $param[5])->first();
            $balansAdd = intval($param[6]/$param[2]);
            TgBotUser::where('tg_id', $param[5])->update(['money' => $user->money + $balansAdd]);

            TgBotTimer::create([
                'user_id' => $param[5],
                'chat_id' => TgSession::getParam('callback_query.message.chat.id'),
                'param' => 'bonus',
            ]);

            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('callback_query.message.chat.id'),
                'text' => $user->link() . " получил за бонус " .
                    number_format($balansAdd, 0, '', ' ') .
                    " \xF0\x9F\x92\xB5",
            ]);
            $this->update($param);
            sleep(5);

            TgSession::getApi()->deleteMessage([
                'chat_id' => TgSession::getParam('callback_query.message.chat.id'),
                'message_id' => TgSession::getParam('callback_query.message.message_id'),
            ]);
            return $request;
        }
        $this->update($param);
        return $request;
    }

    /**
     * @param string $text
     * @return bool
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function callbackMessage(string $text)
    {
        return TgSession::getApi()->answerCallbackQuery([
            'callback_query_id' => TgSession::getParam('callback_query.id'),
            'text' => $text,
        ]);
    }

    /**
     * @param array $param
     */
    private function update(array $param)
    {
        $clickItem = TgSession::getParam('callback_query.data');
        $inline_keyboard = TgSession::getParam('callback_query.message.reply_markup.inline_keyboard');
        foreach ($inline_keyboard as $lineKey => $line) {
            foreach ($line as $itemKey => $item) {
                if ($item['callback_data'] == $clickItem) {
                    $inline_keyboard[$lineKey][$itemKey] = $item;
                    if ($param[2] == 1) {
                        $inline_keyboard[$lineKey][$itemKey]['text'] = "\xF0\x9F\x92\xA9";
                    } else {
                        $inline_keyboard[$lineKey][$itemKey]['text'] = "\xF0\x9F\x92\xB8";
                    }
                    $param[3] = 1;
                    $inline_keyboard[$lineKey][$itemKey]['callback_data'] = implode(':', $param);
                } else {
                    $data = explode(':', $item['callback_data']);
                    $data[4]--;
                    $inline_keyboard[$lineKey][$itemKey]['callback_data'] = implode(':', $data);
                }
            }
        }
        $keyboard = array("inline_keyboard" => $inline_keyboard);
        $reply_markup = json_encode($keyboard);
        TgSession::getApi()->editMessageReplyMarkup([
            'chat_id' => TgSession::getParam('callback_query.message.chat.id'),
            'message_id' => TgSession::getParam('callback_query.message.message_id'),
            'reply_markup' => $reply_markup,
        ]);
    }
}
