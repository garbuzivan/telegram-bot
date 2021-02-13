<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot;

use Carbon\Carbon;
use GarbuzIvan\TelegramBot\Models\TgBotChat;
use GarbuzIvan\TelegramBot\Models\TgBotChatAdmin;
use GarbuzIvan\TelegramBot\Models\TgBotMessage;
use GarbuzIvan\TelegramBot\Models\TgBotUser;
use Illuminate\Support\Facades\DB;

class ParserBot
{
    /**
     * Create if not exists user
     *
     * @param $user
     * @return TgBotUser|null
     */
    public function getUser(?array $user): ?TgBotUser
    {
        if (!is_null($user) && isset($user['id'])) {
            return $this->firstOrCreate($user);
        }
        return null;
    }

    /**
     * Method get or create user
     *
     * @param $from
     * @return TgBotUser
     */
    public function firstOrCreate($from): TgBotUser
    {
        $user = TgBotUser::where('tg_id', $from['id'])->first();
        if (is_null($user)) {
            $insert = [
                'tg_id' => $from['id'],
                'is_bot' => $from['is_bot'] == false ? 0 : 1,
                'username' => $from['username'] ?? null,
                'first_name' => $from['first_name'] ?? null,
                'last_name' => $from['last_name'] ?? null,
                'title' => 'Новичок',
            ];
            return TgBotUser::create($insert);
        }
        return $user;
    }

    /**
     * @return TgBotChat|null
     */
    public function getChat(): ?TgBotChat
    {
        $chat = TgSession::getParam('message.chat');
        if (isset($chat['id'])) {
            $chatID = $chat['id'];
            $chatDB = TgBotChat::where('chat_id', $chatID)->first();
            if (is_null($chatDB)) {
                $chatTitle = $chatID < 0 ?
                    ($chat['title'] ?? null)
                    : 'ЛС: ' . TgSession::getUser()->fullname;
                $insert = [
                    'chat_id' => $chatID,
                    'chat_title' => $chatTitle,
                ];
                return TgBotChat::create($insert);
            }
            return $chatDB;
        }
        return null;
    }

    /**
     * @param array $message
     * @param int $update_id
     * @return bool
     */
    public function newMessage(array $message, $update_id = 0): bool
    {
        if (isset($message['message_id'])) {
            $messageId = $message['message_id'];
            $messageDB = TgBotMessage::where('message_id', $messageId)->first();
            if (is_null($messageDB)) {
                $insert = [
                    'update_id' => $update_id,
                    'message_id' => $messageId,
                    'from_id' => $message['from']['id'],
                    'chat_id' => $message['chat']['id'],
                    'chat_title' => $message['chat']['title'] ?? null,
                    'date' => $message['date'] ?? 0,
                    'reply_message_id' => $message['reply_to_message']['message_id'] ?? null,
                    'reply_from_id' => $message['reply_to_message']['from']['id'] ?? null,
                    'text' => $message['text'] ?? null,
                    'json' => json_encode($message),
                ];
                TgBotMessage::create($insert);
                // обновляем количество особщений и дату последней активности
                if ($update_id != 0) {
                    TgBotUser::where('tg_id', $message['from']['id'])
                        ->update([
                            'message_count' => DB::raw('message_count+1'),
                            'last_time' => Carbon::now()
                        ]);
                }
            }
        }
        return false;
    }

    public function updateAdmin(): void
    {
        // Обновляем админа не чаще раза в минуту
        if (TgSession::getChat()->updated_at->timestamp < strtotime('-1 minutes')) {
            $chatID = TgSession::getChat()->chat_id;
            $getChatAdministrators = TgSession::getApi()->getChatAdministrators(['chat_id' => $chatID]);
            $admins = [];
            foreach ($getChatAdministrators as $user) {
                if (!isset($user['user']['id']) || is_null($user['user']['id'])) {
                    continue;
                }
                $admins[] = [
                    'chat_id' => $chatID,
                    'user_id' => $user['user']['id'],
                ];
            }
            TgBotChatAdmin::where('chat_id', $chatID)->delete();
            TgBotChatAdmin::insert($admins);
            TgBotChat::where('id', TgSession::getChat()->id)->update(['updated_at' => Carbon::now()]);
        }
    }

}
