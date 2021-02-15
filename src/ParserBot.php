<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot;

use Carbon\Carbon;
use GarbuzIvan\TelegramBot\Models\TgBotChat;
use GarbuzIvan\TelegramBot\Models\TgBotChatAdmin;
use GarbuzIvan\TelegramBot\Models\TgBotChatUsers;
use GarbuzIvan\TelegramBot\Models\TgBotMessage;
use GarbuzIvan\TelegramBot\Models\TgBotUser;
use GarbuzIvan\TelegramBot\Models\TgBotUserRename;
use GarbuzIvan\TelegramBot\Models\TgBotUserTitle;
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
                    : 'ЛС: ' . TgSession::getUser()->fullname();
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

    public function updateAdmin()
    {
        if (!isset(TgSession::getChat()->chat_id) || TgSession::getChat()->chat_id > 0) {
            return false;
        }
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

    /**
     *
     */
    public function updateChatUser()
    {
        if (is_null(TgSession::getUser())) {
            return false;
        }
        $chats = [];
        $chatsDB = TgSession::getUser()->chats;
        foreach ($chatsDB as $chat) {
            $chats[$chat->chat_id] = $chat;
        }
        if (!isset($chats[TgSession::getChat()->chat_id])) {
            TgBotChatUsers::create([
                'chat_id' => TgSession::getChat()->chat_id,
                'user_id' => TgSession::getUser()->tg_id,
            ]);
        }
    }

    /**
     *
     */
    public function newChatUser()
    {
        $userID = TgSession::getParam('message.new_chat_member.id');
        if (is_null($userID)) {
            return false;
        }
        $chatID = TgSession::getParam('message.chat.id');

        $insert = [
            'id' => $userID,
            'is_bot' => TgSession::getParam('message.new_chat_member.is_bot'),
            'username' => TgSession::getParam('message.new_chat_member.username'),
            'first_name' => TgSession::getParam('message.new_chat_member.first_name'),
            'last_name' => TgSession::getParam('message.new_chat_member.last_name'),
        ];
        $user = $this->firstOrCreate($insert);
        TgBotChatUsers::create([
            'chat_id' => $chatID,
            'user_id' => $userID,
        ]);
        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => '<b>' . $user->link() . "</b> теперь с нами в чате! \xF0\x9F\x8E\x89",
        ]);

        $friendID = TgSession::getParam('message.from.id');
        if (!is_null($friendID)) {
            TgBotUser::where('tg_id', $friendID)
                ->update([
                    'friend' => DB::raw('friend+1'),
                    'last_time' => Carbon::now()
                ]);
        }

    }

    /**
     * Пользователь покинул чат
     */
    public function deleteChatUser()
    {
        $userID = TgSession::getParam('message.left_chat_member.id');
        if (is_null($userID)) {
            return false;
        }
        $chatID = TgSession::getParam('message.chat.id');

        TgBotChatUsers::where('chat_id', $chatID)->where('user_id', $userID)->delete();
        $user = TgBotUser::where('tg_id', $userID)->first();

        if (is_null($user)) {
            return false;
        }

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => '<b>' . $user->link() . "</b> покинул(а) чат! \xF0\x9F\x8F\x83",
        ]);
    }


    /**
     * Пользователь переименовал себя
     */
    public function userRename()
    {
        if (
            TgSession::getUser()->first_name != TgSession::getParam('message.from.first_name')
            || TgSession::getUser()->last_name != TgSession::getParam('message.from.last_name')
        ) {
            $oldName = TgSession::getUser()->link();
            TgBotUserRename::create([
                'user_id' => TgSession::getUser()->tg_id,
                'name' => TgSession::getUser()->fullname(),
            ]);
            TgBotUser::where('tg_id', TgSession::getUser()->tg_id)->update([
                'first_name' => TgSession::getParam('message.from.first_name'),
                'last_name' => TgSession::getParam('message.from.last_name'),
            ]);
            TgSession::setUser(TgBotUser::where('id', TgSession::getUser()->id)->first());
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => $oldName . ' изменил(а) имя на ' . TgSession::getUser()->link(),
            ]);
        }
    }
}
