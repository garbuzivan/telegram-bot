<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;
use GarbuzIvan\TelegramBot\Models\TgBotChat;
use GarbuzIvan\TelegramBot\Models\TgBotChatAdmin;
use GarbuzIvan\TelegramBot\Models\TgBotChatUsers;
use GarbuzIvan\TelegramBot\Models\TgBotUser;
use GarbuzIvan\TelegramBot\Models\TgBotUserTitle;
use GarbuzIvan\TelegramBot\TgSession;
use Illuminate\Support\Facades\DB;
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
        $request = $this->active($request);
        $request = $this->like($request);
        $request = $this->dislike($request);
        $request = $this->setBan($request);
        $request = $this->ban($request);
        $request = $this->title($request);
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

        $titles = null;
        $titlesUser = TgSession::getUser()->titles()->orderBy('created_at', 'DESC')->take(5)->get();
        foreach ($titlesUser as $title) {
            $titles .= "\n\xF0\x9F\x91\x89 " . $title->title . ' - ' . $title->created_at->format('d.m.Y');
        }

        $renames = null;
        $renamesUser = TgSession::getUser()->rename()->orderBy('created_at', 'DESC')->take(5)->get();
        foreach ($renamesUser as $rename) {
            $renames .= "\n\xF0\x9F\x91\x89 " . $rename->name . ' - ' . $rename->created_at->format('d.m.Y');
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
        $text .= "\n\n<b>Звания:</b>" . $titles;
        $text .= "\n\n<b>Последние имена:</b>" . $renames;
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

    /**
     * @param $request
     * @return mixed
     * @throws TelegramSDKException
     */
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

    /**
     * @param $request
     * @return mixed
     * @throws TelegramSDKException
     */
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

    /**
     * @param $request
     * @return mixed
     * @throws TelegramSDKException
     */
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

    /**
     * @param $request
     * @return mixed
     * @throws TelegramSDKException
     */
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

    /**
     * @param $request
     * @return mixed
     * @throws TelegramSDKException
     */
    private function like($request)
    {
        if (
            isset($request['like'])
            || is_null(TgSession::getUserReply())
            || is_null(TgSession::getParam('message.text'))
            || mb_strlen(trim(TgSession::getParam('message.text'))) == 0
        ) {
            return $request;
        }
        $text = TgSession::getParam('message.text');
        if (
            preg_match("~(\u{1F60D}|\u{2764}|\u{1F381}|\u{1F37B}|\u{1F37A}|\u{1F382}|\u{1F444}|\u{1F445}|\u{1F48B}|\u{1F48C}|\u{1F48F}|\u{1F493}|\u{1F494}|\u{1F495}|\u{1F496}|\u{1F497}|\u{1F498}|\u{1F499}|\u{1F49A}|\u{1F49B}|\u{1F49C}|\u{1F49D}|\u{1F49E}|\u{1F49F})~ui", $text)
            || preg_match("~\u{1F44D}~u", $text)
            || trim($text) == '+'
        ) {
            TgBotUser::where('tg_id', TgSession::getUserReply()->tg_id)->update([
                'like' => DB::raw('`like`+1')
            ]);
            $m = TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => TgSession::getUser()->link() . " \xF0\x9F\x91\x8D повысил(а) рейтинг  " . TgSession::getUserReply()->link() . "!",
            ]);
            sleep(3);
            TgSession::getApi()->deleteMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'message_id' => $m['message_id'],
            ]);
            $request['like'] = true;
        }
        return $request;
    }

    /**
     * @param $request
     * @return mixed
     * @throws TelegramSDKException
     */
    private function dislike($request)
    {
        if (
            isset($request['dislike'])
            || is_null(TgSession::getUserReply())
            || is_null(TgSession::getParam('message.text'))
            || mb_strlen(trim(TgSession::getParam('message.text'))) == 0
        ) {
            return $request;
        }
        $text = TgSession::getParam('message.text');
        if (
            preg_match("~\u{1F44E}~u", $text)
            || trim($text) == '-'
        ) {
            TgBotUser::where('tg_id', TgSession::getUserReply()->tg_id)->update([
                'dislike' => DB::raw('`dislike`+1')
            ]);
            $m = TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => TgSession::getUser()->link() . " \xF0\x9F\x91\x8E понизил(а) рейтинг  " . TgSession::getUserReply()->link() . "!",
            ]);
            sleep(3);
            TgSession::getApi()->deleteMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'message_id' => $m['message_id'],
            ]);
            $request['dislike'] = true;
        }
        return $request;
    }

    /**
     * @param $request
     * @return mixed
     * @throws TelegramSDKException
     */
    private function active($request)
    {
        $activeList = [
            '/chatactive' => 1,
            '/chatdeactive' => 0,
            '!играю' => 1,
            '!неиграю' => 0,
            'играю' => 1,
            'неиграю' => 0,
        ];
        if (isset($request['setSex']) || !in_array(TgSession::getCall(), array_keys($activeList))) {
            return $request;
        }

        $active = $activeList[TgSession::getCall()];
        TgBotChatUsers::where('user_id', TgSession::getUser()->id)
            ->where('chat_id', TgSession::getParam('message.chat.id'))
            ->update(['active' => $active]);

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => "\xF0\x9F\x90\x92 " . TgSession::getUser()->link() .
                ' приказал боту <b>' . ($active == 0 ? 'не звать' : 'звать') . " через зазывалу!</b> \xF0\x9F\x98\x9C!",
        ]);

        TgSession::getApi()->deleteMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'message_id' => TgSession::getParam('message.message_id'),
        ]);

        $request['active'] = true;
        return $request;
    }

    /**
     * @param $request
     * @return mixed
     * @throws TelegramSDKException
     */
    private function setBan($request)
    {
        $banList = [
            '/ban' => 1,
            '/unban' => 0,
            '!бан' => 1,
            '!разбан' => 0,
            'бан' => 1,
            'разбан' => 0,
        ];
        if (
            isset($request['setBan'])
            || !in_array(TgSession::getCall(), array_keys($banList))
            || is_null(TgSession::getUserReply())
        ) {
            return $request;
        }

        $admins = [];
        $adminsChat = TgBotChatAdmin::where('chat_id', TgSession::getParam('message.chat.id'))->get();
        foreach ($adminsChat as $admin) {
            $admins[$admin->user_id] = $admin;
        }
        if (!isset($admins[TgSession::getUser()->tg_id]) || isset($admins[TgSession::getUserReply()->tg_id])) {
            return $request;
        }

        $ban = $banList[TgSession::getCall()];
        TgBotChatUsers::where('user_id', TgSession::getUser()->id)
            ->where('chat_id', TgSession::getParam('message.chat.id'))
            ->update(['ban' => $ban]);

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => "\xF0\x9F\x90\x92 " . TgSession::getUser()->link() .
                ' <b>' . ($ban == 0 ? 'разблокировал' : 'наказал') .
                TgSession::getUserReply()->link() . "!</b> \xF0\x9F\x98\x9C!",
        ]);

        TgSession::getApi()->deleteMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'message_id' => TgSession::getParam('message.message_id'),
        ]);

        $request['setBan'] = true;
        return $request;
    }

    /**
     * @param $request
     * @return mixed
     * @throws TelegramSDKException
     */
    private function ban($request)
    {
        $users = TgSession::getChat()->users;
        foreach ($users as $user) {
            if ($user->user_id != TgSession::getUser()->tg_id) {
                continue;
            }
            if ($user->ban == 0) {
                return $request;
            }
            $admins = [];
            $adminsChat = TgBotChatAdmin::where('chat_id', TgSession::getParam('message.chat.id'))->get();
            foreach ($adminsChat as $admin) {
                $admins[$admin->user_id] = $admin;
            }
            if (isset($admins[$user->user_id])) {
                return $request;
            }
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => TgSession::getUser()->link() . " хочет извиниться и признает власть \xF0\x9F\x91\x91 и могущество \xF0\x9F\x91\xBF АДМИНОВ. \nРазбанить может любой админ, командой !разбан",
            ]);
            sleep(5);
            TgSession::getApi()->deleteMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'message_id' => TgSession::getParam('message.message_id'),
            ]);
            $request['ban'] = true;
        }
        return $request;
    }

    /**
     * @param $request
     * @return false
     * @throws TelegramSDKException
     */
    private function title($request)
    {
        if (
            isset($request['title'])
            || !in_array(TgSession::getCall(), ['/title', '!звание'])
            || TgSession::getChat()->chat_id > 0
            || is_null(TgSession::getCallParam())
            || mb_strlen(trim(TgSession::getCallParam())) == 0
        ) {
            return $request;
        }

        $users = [];
        $usersChat = TgSession::getChat()->users;
        foreach ($usersChat as $user) {
            if (!TgSession::getTimer($user->info, 'title', 6)) {
                continue;
            }
            $users[$user->tg_id] = $user;
        }

        if (count($users) == 0) {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => "\xE2\x9B\x94 Звания невозможно выдавать пользователю чаще, чем раз в 6 часов!",
            ]);
            return false;
        }

        $userRand = $users[array_rand($users)];

        TgBotUserTitle::create([
            'user_id' => $userRand->user_id,
            'title' => TgSession::getCallParam(),
        ]);

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => "\xE2\x9C\x85 " . $userRand->info->link() . " присвоено звание " . TgSession::getCallParam() . "!",
        ]);

        TgSession::getApi()->deleteMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'message_id' => TgSession::getParam('message.message_id'),
        ]);

        $request['title'] = true;
        return $request;
    }
}
