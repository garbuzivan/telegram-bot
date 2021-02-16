<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;
use GarbuzIvan\TelegramBot\Dict;
use GarbuzIvan\TelegramBot\Models\TgBotUserInventory;
use GarbuzIvan\TelegramBot\TgSession;

class Shop extends AbstractCommand
{
    /**
     * @var string Command Name
     */
    public string $name = "/shop";

    /**
     * @var string Command Description
     */
    public string $description = "Магазин";

    /**
     * @inheritdoc
     */
    public function handler($request, Closure $next)
    {
        $request = $this->pay($request);
        $request = $this->view($request);
        return $next($request);
    }

    /**
     * @param $request
     * @return mixed
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function view($request)
    {
        if (isset($request['shop']) || !in_array(TgSession::getCall(), ['/shop', '!магазин', 'магазин'])) {
            return $request;
        }

        $itemInfo = ':' . TgSession::getUser()->tg_id;

        $inline_keyboard = [];
        $lineCount = 0;
        $line = [];
        foreach (Dict::getShop() as $itemID => $item) {
            $lineCount++;
            $line[] = ['text' => $item['text'] . " \xF0\x9F\x92\xB5 " . $item['price'], 'callback_data' => 'shop:' . $itemID . $itemInfo];
            if ($lineCount == 2 || in_array($itemID, [21, 22])) {
                $inline_keyboard[] = $line;
                $line = [];
                $lineCount = 0;
            }
        }
        $inline_keyboard[] = [['text' => "\xE2\x9D\x8C Закрыть магазин \xE2\x9D\x8C", 'callback_data' => 'exit' . $itemInfo]];

        $keyboard = array("inline_keyboard" => $inline_keyboard);
        $reply_markup = json_encode($keyboard);
        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => "Магазин\n\xF0\x9F\x92\xB0 Баланс " . TgSession::getUser()->link() . " " .
                number_format(TgSession::getUser()->money, 0, '', ' ') .
                " \xF0\x9F\x92\xB5",
            'reply_markup' => $reply_markup,
        ]);

        $request['shop'] = true;
        return $request;
    }

    /**
     * @param $request
     * @return mixed
     */
    private function pay($request)
    {
        if (is_null(TgSession::getParam('callback_query.data'))) {
            return $request;
        }
        $param = TgSession::getParam('callback_query.data');
        $param = explode(':', $param);
        if (!isset($param[1]) || $param[0] != 'shop') {
            return $request;
        }
        $shopItems = Dict::getShop();
        if(!isset($shopItems[$param[1]])){
            return $request;
        }
        TgBotUserInventory::create([
            'user_id' => TgSession::getParam('callback_query.from.id'),
            'inventory_id' => $param[1],
            'name' => $shopItems[$param[1]]['text'],
        ]);
        TgSession::getApi()->answerCallbackQuery([
            'callback_query_id' => TgSession::getParam('callback_query.id'),
            'text' => $shopItems[$param[1]]['title'],
        ]);
        return $request;
    }
}
