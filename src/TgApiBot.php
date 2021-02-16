<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot;

use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Message as MessageObject;

class TgApiBot extends \Telegram\Bot\Api
{
    /**
     * @param array $params
     * @return MessageObject
     * @throws TelegramSDKException
     */
    public function sendMessage(array $params): MessageObject
    {
        $params['parse_mode'] = $params['parse_mode'] ?? 'HTML';
        try {
            $message = parent::sendMessage($params);
        } catch (TelegramResponseException|TelegramSDKException $e){
            exit();
        }
        $parser = new ParserBot();
        $parser->newMessage($message->toArray(), 0);
        return $message;
    }

    public function deleteMessage(array $params)
    {
        try {
            return parent::deleteMessage($params);
        } catch (TelegramSDKException $e) {
            parent::sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'parse_mode' => 'HTML',
                'text' => 'Боту необходимы права администратора для корректной работы!',
            ]);
        }
        return null;
    }
    //    /**
    //     * @param array $callback_query_id
    //     * @param null $text
    //     * @return array|bool
    //     * @throws TelegramSDKException
    //     */
    //    public function answerCallbackQuery(array $callback_query_id, $text = null)
    //    {
    //        $params = [
    //            'callback_query_id' => $callback_query_id,
    //            'text' => $text,
    //        ];
    //        return  $this->post('answerCallbackQuery', $params)->getDecodedBody();
    //    }
    //
    //    /**
    //     * @param int $chatId
    //     * @return array
    //     * @throws TelegramSDKException
    //     */
    //    public function getChatAdministrators(int $chatId): array
    //    {
    //        if ($chatId > 0) {
    //            return [];
    //        }
    //        $adminsResult = $this->get('getChatAdministrators', ['chat_id' => $chatId])->getDecodedBody();
    //        $admins = [];
    //        foreach ($adminsResult['result'] as $user) {
    //            if (!isset($user['user']['id']) || is_null($user['user']['id'])) {
    //                continue;
    //            }
    //            $admins[] = [
    //                'chat_id' => $chatId,
    //                'user_id' => $user['user']['id'],
    //            ];
    //        }
    //        return $admins;
    //    }
    //
}
