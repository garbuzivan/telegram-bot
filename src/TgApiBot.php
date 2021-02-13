<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot;

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
        $message = parent::sendMessage($params);
        $parser = new ParserBot();
        $parser->newMessage((array)$message, 0);
        file_put_contents(public_path('message.php'), json_encode($message) . "\n\n", 8);
        return $message;
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
    //    /**
    //     * @param array $chatId
    //     * @param $messageId
    //     * @return bool|\Telegram\Bot\Objects\Message|\Telegram\Bot\TelegramResponse
    //     * @throws TelegramSDKException
    //     */
    //    public function deleteMessage($chatId, $messageId)
    //    {
    //        return $this->get('deleteMessage', [
    //            'chat_id' => $chatId,
    //            'message_id' => $messageId,
    //        ]);
    //    }
    //
}
