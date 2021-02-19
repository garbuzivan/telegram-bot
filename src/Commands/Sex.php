<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;
use GarbuzIvan\TelegramBot\Dict;
use GarbuzIvan\TelegramBot\Models\TgBotUser;
use GarbuzIvan\TelegramBot\Models\TgBotUserInventory;
use GarbuzIvan\TelegramBot\TgSession;
use Telegram\Bot\FileUpload\InputFile;

class Sex extends AbstractCommand
{
    /**
     * @var string Command Name
     */
    public string $name = "/commands";

    /**
     * @var string Command Description
     */
    public string $description = "Команды !яншалун";

    /**
     * @inheritdoc
     */
    public function handler($request, Closure $next)
    {
        $request = $this->commands($request);
        $request = $this->chpok($request);
        $request = $this->neck($request); // !обнять
        $request = $this->kiss($request); // !поцеловать
        $request = $this->pnut($request); // !пнуть
        $request = $this->sex($request); // !секс
        $request = $this->caress($request); // !ласкать
        $request = $this->send($request); // !послать
        $request = $this->oral($request); // !орал
        $request = $this->kyni($request); // !куни
        $request = $this->blowJob($request); // !минет
        $request = $this->slap($request); // !шлепнуть
        $request = $this->surprise($request); // !сюрприз
        $request = $this->console($request); // !утешить
        $request = $this->drink($request); // !напоить
        $request = $this->wake($request); // !разбудить
        $request = $this->lay($request); // !уложить
        $request = $this->slave($request); // !раб
        $request = $this->dance($request); // !танец
        $request = $this->undress($request); // !раздеть
        $request = $this->execute($request); // !казнить
        $request = $this->couple($request); // !пара
        $request = $this->massage($request); // !массаж
        $request = $this->shved($request); // !швед
        $request = $this->sexM1W2($request); // !жмж
        $request = $this->sexM2W1($request); // !мжм
        $request = $this->compliment($request); // !комплимент
        $request = $this->bite($request); // !укусить
        return $next($request);
    }

    /**
     * @param $request
     * @return mixed
     */
    private function commands($request)
    {
        if (
            isset($request['яншалун'])
            || !in_array(TgSession::getCall(), ['/commands', '!команды', '!яншалун', 'яншалун'])
        ) {
            return $request;
        }
        $text = "<b>Список команд ЯнШалун:</b>";
        $text .= "\n<b>@Все ....</b>";
        $text .= "\n<b>Ян покажи ...</b>";
        $text .= "\n<b>Ян кто ...?</b>";
        $text .= "\n<b>Ян где ...?</b>";
        $text .= "\n<b>!чпок</b>";
        $text .= "\n<b>!обнять</b>";
        $text .= "\n<b>!поцеловать</b>";
        $text .= "\n<b>!пнуть</b>";
        $text .= "\n<b>!секс</b>";
        $text .= "\n<b>!ласкать</b>";
        $text .= "\n<b>!послать</b>";
        $text .= "\n<b>!орал</b>";
        $text .= "\n<b>!куни</b>";
        $text .= "\n<b>!минет</b>";
        $text .= "\n<b>!шлепнуть</b>";
        $text .= "\n<b>!сюрприз</b>";
        $text .= "\n<b>!утешить</b>";
        $text .= "\n<b>!напоить</b>";
        $text .= "\n<b>!разбудить</b>";
        $text .= "\n<b>!уложить</b>";
        $text .= "\n<b>!раб</b>";
        $text .= "\n<b>!танец</b>";
        $text .= "\n<b>!раздеть</b> 100 \xF0\x9F\x92\xB8";
        $text .= "\n<b>!казнить</b>";
        $text .= "\n<b>!пара</b>";
        $text .= "\n<b>!массаж</b>";
        $text .= "\n<b>!швед</b>";
        $text .= "\n<b>!жмж</b>";
        $text .= "\n<b>!мжм</b>";
        $text .= "\n<b>!комплимент</b>";
        $text .= "\n<b>!укусить</b>";
        $text .= "\n\n";
        $text .= "\n<b>!парень</b>";
        $text .= "\n<b>!девушка</b>";
        $text .= "\n<b>!писюн</b>";
        $text .= "\n<b>!влагалище</b>";
        $text .= "\n<b>!сиськи</b>";
        $text .= "\n<b>!неиграю</b> - запрещает зазывале отмечать";
        $text .= "\n<b>!играю</b> - разрешает зазывале отмечать";
        $text .= "\n\n";
        $text .= "\n<b>!бан</b> - блокирует отправку сообщений (админам)";
        $text .= "\n<b>!разбан</b> - разблокирует отправку сообщений (админам)";
        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => $text . "\n\n\xF0\x9F\x9A\xA9 " . TgSession::getUser()->link(),
        ]);

        $this->delMessage();
        $request['яншалун'] = true;
        return $request;
    }

    /**
     * @param $request
     * @return mixed
     */
    private function chpok($request)
    {
        if (
            isset($request['chpok'])
            || !in_array(TgSession::getCall(), ['/chpok', '!чпок', 'чпок'])
            || is_null(TgSession::getUserReply())
        ) {
            return $request;
        }
        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => TgSession::getUser()->link() . ' чпокает ' . TgSession::getUserReply()->link() . ' ' .
                Dict::rand(Dict::getWhere()) . '!',
        ]);

        $this->delMessage();
        $request['chpok'] = true;
        return $request;
    }

    /**
     * @param $request
     * @return mixed
     */
    private function neck($request)
    {
        return $this->defCommand(
            'neck',
            ['/neck', '!обнять', 'обнять', 'прижать'],
            [
                'крепко обнимает сейчас',
                'прижалась(ся) к спинке',
                'прыгает на ручки к',
                'обнимает за попу',
                'берет на ручки',
                'обнимает со спины',
                'закрывает глаза лодошками сзади и спрашивает: "Угадай кто?" у',
                'берет на ручки и кружит вокруг себя',
            ],
            $request
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function kiss($request)
    {
        if (
            isset($request['compliment'])
            || !in_array(TgSession::getCall(), ['/kiss', '!поцеловать', 'поцеловать', '!поцелуй', 'поцелуй'])
        ) {
            return $request;
        }

        $arr = Dict::getWherePeople();
        foreach ($arr as $key => $value) {
            $arr[$key] = 'целует в ' . $value;
        }
        $arr = array_merge([
            'целует пуская слюни',
            'целует по французски',
            'целует в засос',
            'целует в шейку',
            'целует в ручку',
            'целует в носик',
            'целует в щечку',
            'целует в животик',
            'целует в носик',
        ], $arr);
        return $this->defCommand(
            'kiss',
            ['/kiss', '!поцеловать', 'поцеловать', '!поцелуй', 'поцелуй'],
            $arr,
            $request
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function pnut($request)
    {
        return $this->defCommand(
            'pnut',
            ['/pnut', '!пнуть', 'пнуть'],
            [
                'пнул в живот',
                'дал подсрачник',
                'толкнул',
                'сделал(а) фингал под глазом',
                'дал подзатыльник',
                'ударил по голове',
            ],
            $request
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function sex($request)
    {
        if (
            isset($request['sex'])
            || !in_array(TgSession::getCall(), ['/sex', '!секс', 'секс'])
            || is_null(TgSession::getUserReply())
        ) {
            return $request;
        }

        // не определен для Юзер1
        if (
        !in_array(TgSession::getUser()->sex, ['девушка', 'парень'])
        ) {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => 'Для занятия сексом необходимо определеить пол для ' . TgSession::getUser()->link() . '!',
            ]);
            $this->delMessage();
            return $request;
        }

        // не определен для Юзер2
        if (!in_array(TgSession::getUserReply()->sex, ['девушка', 'парень'])
        ) {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => 'Для занятия сексом необходимо определеить пол для ' . TgSession::getUserReply()->link() . '!',
            ]);
            $this->delMessage();
            return $request;
        }

        if (TgSession::getUser()->sex != TgSession::getUserReply()->sex) {
            // не определен член Юзер1
            if (
                in_array(TgSession::getUser()->sex, ['парень'])
                && TgSession::getUser()->penis < 3
            ) {
                TgSession::getApi()->sendMessage([
                    'chat_id' => TgSession::getParam('message.chat.id'),
                    'text' => 'Для занятия сексом необходимо определеить размер члена для ' . TgSession::getUser()->link() . '!',
                ]);
                $this->delMessage();
                return $request;
            }

            // не определен влагалища Юзер1
            if (
                in_array(TgSession::getUser()->sex, ['девушка'])
                && TgSession::getUser()->vagina < 3
            ) {
                TgSession::getApi()->sendMessage([
                    'chat_id' => TgSession::getParam('message.chat.id'),
                    'text' => 'Для занятия сексом необходимо определеить размер влагалища для ' . TgSession::getUser()->link() . '!',
                ]);
                $this->delMessage();
                return $request;
            }

            // не определен член Юзер2
            if (
                in_array(TgSession::getUserReply()->sex, ['парень'])
                && TgSession::getUserReply()->penis < 3
            ) {
                TgSession::getApi()->sendMessage([
                    'chat_id' => TgSession::getParam('message.chat.id'),
                    'text' => 'Для занятия сексом необходимо определеить размер члена для ' . TgSession::getUserReply()->link() . '!',
                ]);
                $this->delMessage();
                return $request;
            }

            // не определен влагалища Юзер2
            if (
                in_array(TgSession::getUserReply()->sex, ['девушка'])
                && TgSession::getUserReply()->vagina < 3
            ) {
                TgSession::getApi()->sendMessage([
                    'chat_id' => TgSession::getParam('message.chat.id'),
                    'text' => 'Для занятия сексом необходимо определеить размер влагалища для ' . TgSession::getUserReply()->link() . '!',
                ]);
                $this->delMessage();
                return $request;
            }

            // несовместимость
            $penis = TgSession::getUserReply()->sex == 'девушка' ? TgSession::getUser()->penis : TgSession::getUserReply()->penis;
            $vagina = TgSession::getUserReply()->sex == 'девушка' ? TgSession::getUserReply()->vagina : TgSession::getUser()->vagina;
            if (
                ($penis > $vagina && $penis - $vagina > 5)
                || ($penis < $vagina && $vagina - $penis > 5)
            ) {
                TgSession::getApi()->sendMessage([
                    'chat_id' => TgSession::getParam('message.chat.id'),
                    'text' => TgSession::getUser()->link() . ' и ' . TgSession::getUserReply()->link() .
                        ' не могут заняться сексом, несовместимость размеров половых органов!',
                ]);
                $this->delMessage();
                return $request;
            }
        }

        $url = url('public/media/s/' . rand(1, 70) . '.jpg');

        $text = null;
        if (TgSession::getUser()->sex == TgSession::getUserReply()->sex && TgSession::getUser()->sex == 'парень') {
            $text = 'Два голубка ';
            $url = url('public/media/g/' . rand(1, 6) . '.gif');
        } elseif (TgSession::getUser()->sex == TgSession::getUserReply()->sex && TgSession::getUser()->sex == 'девушка') {
            $text = 'Два лесбухи ';
            $url = url('public/media/l/' . rand(1, 8) . '.gif');
        }
        $text .= TgSession::getUser()->link() . ' и ' . TgSession::getUserReply()->link() . ' занялись сексом ' .
            Dict::rand(Dict::getWhere());

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => $text,
        ]);

        $fact = TgSession::getUser()->fullname() . ' и ' . TgSession::getUserReply()->fullname();

        if (TgSession::getUser()->sex == TgSession::getUserReply()->sex) {
            $file = InputFile::create($url, $fact);
            TgSession::getApi()->sendPhoto([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'photo' => $file,
                'caption' => $fact,
            ]);
        } else {
            $file = InputFile::create($url, $fact);
            TgSession::getApi()->sendPhoto([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'photo' => $file,
                'caption' => $fact,
            ]);
        }


        $this->delMessage();
        $request['chpok'] = true;
        return $request;
    }

    /**
     * @param $request
     * @return mixed
     */
    private function caress($request)
    {
        return $this->defCommand(
            'caress',
            ['/caress', '!ласкать', 'ласкать'],
            [
                'ласкает',
                'гладит',
                'берет за руку',
                'обнимает сзади',
                'делает эротический массаж',
                'делает расслабляющий массаж',
            ],
            $request
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function send($request)
    {
        return $this->defCommand(
            'send',
            ['/send', '!послать', 'послать'],
            [
                'посылает в жопу',
                'посылает нах*й',
                'посылает в эротическое путишествие',
                'послалает в магазин за тампонами',
                'посылает к психатерапевту',
                'посылает к маме',
            ],
            $request,
            '%user% %arr% %userreply%'
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function oral($request)
    {
        if (
            isset($request['oral'])
            || !in_array(TgSession::getCall(), ['/oral', '!орал', 'орал'])
            || is_null(TgSession::getUserReply())
        ) {
            return $request;
        }

        $arr = [
            'занимается оральными ласками с',
            'шалит язычком с',
        ];
        $template = '%user% %arr% %userreply% %where%';
        $template = str_replace('%user%', TgSession::getUser()->link(), $template);
        $template = str_replace('%arr%', $arr[array_rand($arr)], $template);
        $template = str_replace('%userreply%', TgSession::getUserReply()->link(), $template);
        $template = str_replace('%where%', Dict::rand(Dict::getWhere()), $template);

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => $template,
        ]);

        $url = url('public/media/o/' . rand(1, 20) . '.jpg');
        $fact = TgSession::getUser()->fullname() . ' и ' . TgSession::getUserReply()->fullname();
        TgSession::getApi()->sendPhoto([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'photo' => InputFile::create($url, $fact),
            'caption' => $fact,
        ]);

        $this->delMessage();
        $request['oral'] = true;
        return $request;
    }

    /**
     * @param $request
     * @return mixed
     */
    private function kyni($request)
    {
        return $this->defCommand(
            'kyni',
            ['/kyni', '!куни', 'куни', 'кунилингус'],
            [
                'облизывает киску как талое мороженное',
                'язычком прижимает зону вокруг клитора',
                'проникает язычком во влагалище',
                'целует ножки и жадно облизывает',
                'лижет все, что стекает с',
                'язычком перебирает каждую складочку',
                'усадил(а) на лицо',
                'лижет в позе раком схватившись и лаская попку',
                'трахает пальчиками и слизывает с них смазку',
                'уложил(а) на спину, сделал(а) ртом вакуум и ласкает язычком, а руками нежит грудь',
            ],
            $request
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function blowJob($request)
    {
        return $this->defCommand(
            'blowJob',
            ['/blowjob', '!минет', 'минет'],
            [
                'делает глубокий горловой минет',
                'берет член за щеку у',
                'облизывает головку члена',
                'дал(а) кончить в рот',
                'подрочила и слизал(а) сперму у',
                'сосет в ' . rand(5, 10) . ' атмосфер',
            ],
            $request
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function slap($request)
    {
        return $this->defCommand(
            'slap',
            ['/slap', '!шлепнуть', 'шлепнуть'],
            [
                'жмакнул попку',
                'шлепает по попке лодошкой',
                'шлепнул(а) плеткой',
                'держит в руках плетку и приглашает поиграть',
                'шлепнул членом по ноге',
            ],
            $request,
            '%user% %arr% %userreply%'
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function console($request)
    {
        return $this->defCommand(
            'console',
            ['/console', '!утешить', 'утешить'],
            [
                'нежно прижимает к себе и гладит',
                'обнимает и целует',
                'наливает бокал вина',
                'успокаивает как может',
                'утешает интересными разговорами'
            ],
            $request,
            '%user% %arr% %userreply%'
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function drink($request)
    {
        return $this->defCommand(
            'drink',
            ['/drink', '!напоить', 'напоить', '!налить', 'налить', '!споить', 'споить'],
            [
                'наливает водку',
                'наливает пиво',
                'наливает вино',
                'наливает виски',
                'наливает махито',
                'наливает кефир',
                'наливает молоко',
                'наливает минералку',
                'наливает шампанское',
                'наливает швепс',
                'наливает много рома',
                'наливает шампунь в бокал',
                'наливает колу',
                'наливает пепси',
                'наливает фанту',
                'наливает компот',
                'наливает самогонку',
                'добавляет клофелин в напиток',
            ],
            $request,
            '%user% %arr% %userreply%'
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function wake($request)
    {
        return $this->defCommand(
            'wake',
            ['/wake', '!разбудить', 'разбудить'],
            [
                'хочет разбудить',
                'пытается разбудить',
                'будит поцелуем',
                'прыгает под одеялко и будит',
                'будит щекоткой',
                'будит оральным сексом',
                'врубил(а) музыку на всю, чтоб разбудить',
                'скинул(а) с кровати, чтоб разбудить',
            ],
            $request,
            '%user% %arr% %userreply%'
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function lay($request)
    {
        return $this->defCommand(
            'lay',
            ['/lay', '!уложить', 'уложить'],
            [
                'отправляет спать',
                'предлагает уснуть в обнимку',
                'укладывает спать как мамочка',
                'приказал(а) спать',
                'отправил(а) ждать в кровати',
                'отправляет раздеться и ждать в кровате',
            ],
            $request,
            '%user% %arr% %userreply%'
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function slave($request)
    {
        return $this->defCommand(
            'slave',
            ['/slave', '!раб', 'раб'],
            [
                'делает своим рабом',
                'приказывает сделать массаж ножек',
                'прриказывает сделать эротически массаж',
                'приказывает согреть',
                'приказывает стоять рядом апохалом',
                'приказывает лечь в ноги',
                'приказывает ублажать',
                'приказывает принести водички',
                'приказывает раздеться',
                'приказывает развлекать',
                'приказывает приготовить вкусную еду',
            ],
            $request,
            '%user% %arr% %userreply%'
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function dance($request)
    {
        if (
            isset($request['dance'])
            || !in_array(TgSession::getCall(), ['/dance', '!танец', 'танец'])
            || is_null(TgSession::getUserReply())
        ) {
            return $request;
        }

        $arr = [
            'приглашает на танец',
            'общается на языке тела с',
            'пытается соблазнить танцем',
            'устроил дружеские пляски с',
            'по пьяне танцует с',
        ];
        $template = '%user% %arr% %userreply%';
        $template = str_replace('%user%', TgSession::getUser()->link(), $template);
        $template = str_replace('%arr%', $arr[array_rand($arr)], $template);
        $template = str_replace('%userreply%', TgSession::getUserReply()->link(), $template);
        $template = str_replace('%where%', Dict::rand(Dict::getWhere()), $template);

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => $template,
        ]);

        $url = url('public/media/dance/' . rand(1, 23) . '.gif');
        $fact = TgSession::getUser()->fullname() . ' и ' . TgSession::getUserReply()->fullname();
        TgSession::getApi()->sendPhoto([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'photo' => InputFile::create($url, $fact),
            'caption' => $fact,
        ]);

        $this->delMessage();
        $request['dance'] = true;
        return $request;
    }

    /**
     * @param $request
     * @return mixed
     */
    private function execute($request)
    {
        return $this->defCommand(
            'execute',
            ['/execute', '!казнить', 'казнить'],
            [
                'отправляет в АД',
                'кормит крысиным ядом',
                'отравил крысиным ядом',
                'пырнул заточкой',
                'договорился с торговцами и отдал в секс рабство',
                'садит на бутылку',
                'садит на большой член негра',
                'кастрирует',
                'пиздит ногами',
                'рубит голову',
                'усадил(а) на электрический стул',
                'застрелил(а) из пистолета',
                'нанял(а) киллера и убил',
                'нанял(а) три жирные папуаски для группового износилования',
                'отрезал(а) ножом соски',
                'отрезал(а) пилой руки',
                'отрезал(а) пилой ноги',
                'повесил(а) на висилице и лично выбил табуретку из под ног',
                'снял(а) скальп и натянула на футбольный мяч',
                'отрезал(а) член и засунул(а) в жопу обидчику',
            ],
            $request,
            '%user% %arr% %userreply%'
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function massage($request)
    {
        return $this->defCommand(
            'massage',
            ['/massage', '!массаж', 'массаж'],
            [
                'сел(а) верхом на попку и делает массаж',
                'делает тайский массаж',
                'делает рельсы-рельсы шпалы-шпалы ... для',
                'делает эротический массаж',
                'делает расслабляющий массаж',
                'делает медовый массаж',
                'делает баночный массаж',
                'пригласил(а) подругу и делают массаж в 4 руки',
                'пригласил(а) друга и делают массаж в 4 руки',
                'делает анальный массаж',
                'делает вагинальный массаж',
                'делает массаж подбородком',
                'делает массаж языком',
                'делает дружеский массаж',
                'массирует плечи',
                'массирует голову',
                'массирует руки',
                'массирует ноги',
                'массирует попку',
                'делает антицилюлитный массаж',
                'делает антицилюлитный массаж',
                'делает аромамассаж',
                'делает лимфодренажный массаж',
                'делает гавайский массаж',
                'делает классический массаж',
                'делает детский массаж',
                'делает массаж воротниковой зоны',
                'делает массаж ДЖАМУ',
                'делает ХИРОМАССАЖ',
                'делает тиссулярный массаж',
                'делает спортивный массаж',
                'делает массаж для беременных',
                'делает массаж простаты',
                'делает СТОУН массаж'
            ],
            $request
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function compliment($request)
    {
        if (
            isset($request['compliment'])
            || !in_array(TgSession::getCall(), ['/compliment', '!комплимент', 'комплимент'])
        ) {
            return $request;
        }

        $whyBoy = [
            'прошептал на ушко, что %girl% %arr%',
            'отправил цветы и открытку в которой написано, что %girl% %arr%',
            'написал на снегу, что %girl% %arr%',
            'сделал татуировку на которой написал, что %girl% %arr%',
            'приготовил романтический ужин и на блюде выложил, что %girl% %arr%',
            'отправил хор который под окнами поет, что %girl% %arr%',
        ];
        $why = [
            'прошептала на ушко, что %girl% %arr%',
            'позвонила %girl% и сказала %arr%',
            'отправила СМС %girl% с текстом: ты %arr%',
            'сделал татуировку на которой написала, что %girl% %arr%',
            'приготовила романтический ужин и на блюде выложила, что %girl% %arr%',
            'сказала что хочет детей от %girl%',
        ];
        $arrBoy = [
            'прекрасный',
            'милый',
            'замечательный',
            'брутальный',
            'сексуальный',
            'ласковый',
            'заботливый',
            'горячий',
            'верный',
            'сильный',
            'галантный',
            'добрый',
            'бесценный',
            'обаятельный',
            'чуткий',
            'нежный',
            'смелый',
            'умопомрачительный',
            'бесподобный',
            'шикарный',
            'модный',
            'стильный',
            'веселый',
            'пленительный',
            'притягательный',
            'приятный',
            'заводной',
            'понимающий',
            'терпеливый',
            'щедрый',
        ];
        $arr = [
            'красивая',
            'умная',
            'заботливая',
            'привлекательная',
            'сексуальная',
            'добрая',
            'нежная',
            'милая',
            'очаровательная',
            'обворожительная',
            'неповторимая',
            'неописуемая',
            'незабываемая',
            'неотразимая',
            'шикарная',
            'ослепительная',
            'страстная',
            'недоступная',
            'божественная',
            'завораживающая',
            'ангельская',
            'лучезарная',
            'сексапильная',
            'яркая',
            'пушистая',
            'обалденная',
            'сногсшибательная',
            'стройная',
            'обольстительная',
            'кокетливая',
            'утончённая',
            'грациозная',
            'весёлая',
            'энергичная',
            'креативная',
            'стильная',
            'коммуникабельная',
            'тактичная',
            'любвиобильная',
            'романтичная',
            'разносторонняя',
            'сказочная',
            'симпатичная',
            'пылкая',
            'единственная',
            'ласковая',
            'сладенькая',
            'умопомрачительная',
            'желанная',
            'непредсказуемая',
            'загадочная',
            'цветущая',
            'безупречная',
            'гармоничная',
            'отзывчивая',
            'совершенная',
            'лучшая',
            'скромная',
            'изысканная',
            'шаловливая',
            'отпадная',
            'искренная',
            'дружелюбная',
            'понимающая',
            'экстравагантная',
            'мечтательная',
            'ароматная',
            'искромётная',
            'чистолюбивая',
            'манящая',
            'восторженная',
            'бескорыстная',
            'соблазнительная',
            'одурманивающая',
            'жизнерадостная',
            'прелестная',
            'улыбчивая',
            'застенчивая',
            'зажигательная',
            'честная',
            'возбуждающая',
            'чистосердечная',
            'игривая',
            'обаятельная',
            'офигительная',
            'целеустремлённая',
            'дивная',
            'женственная',
            'блаженная',
            'лучезарная',
            'ненаглядная',
            'необходимая',
            'изумительная',
            'сказочная',
            'трогательная',
            'миниатюрная',
            'любимая',
            'самая - самая',
        ];
        if (TgSession::getUser()->sex == 'парень') {
            $why = $whyBoy;
        }
        if (TgSession::getUserReply()->sex == 'парень') {
            $arr = $arrBoy;
        }
        shuffle($arr);
        shuffle($why);
        $user = TgSession::getUser()->link();
        $girl = TgSession::getUserReply()->link();

        $com = $arr[0] . ' и ' . $arr[1];
        $text = $user . ' ' . str_replace(['%girl%', '%arr%'], [$girl, $com], $why[0]) . " \xF0\x9F\x8C\xBA !";
        $listCompliment = [$text];

        return $this->defCommand(
            'compliment',
            ['/compliment', '!комплимент', 'комплимент'],
            $listCompliment,
            $request,
            '%arr%'
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function bite($request)
    {
        return $this->defCommand(
            'bite',
            ['/bite', '!укусить', 'укусить'],
            [
                ' кусает за ' . Dict::rand(Dict::getWherePeople()),
            ],
            $request,
            '%user% %arr% %userreply%'
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function surprise($request)
    {
        return $this->defCommand(
            'surprise',
            ['/surprise', '!сюрприз', 'сюрприз'],
            [
                'дарит крем от целлюлита',
                'дарит вибратор',
                'дарит тетрис',
                'дарит пачку тампонов для',
                'дарит баночку мочи',
                'дарит коробок кала',
                'дарит козюлю из носа',
                'дарит книгу "Техника глубокого минета"',
                'дарит парик',
                'дарит чупа-чупс',
                'дарит бомжа',
                'дарит резиновую женщину',
                'дарит шоколадный пенис',
                'дарит хламидии',
                'дарит крошку хлеба',
                'дарит анальную пробку',
                'дарит бычки сигарет',
                'дарит две гвоздики',
                'дарит кастет',
                'дарит меховые наручники',
                'дарит розу',
                'дарит дошик',
                'дарит бутылку водки',
                'дарит туалетную бумагу',
                'дарит стриптиз',
                'сказал(а) что беременна(ый)',
                'дарит свисток',
                'дарит могильную плиту',
                'дарит мячик',
                'дарит проститутку на час',
                'подложил(а) какашку под дверь',
                'поцеловал(а) в щечку',
                'подарил(а) щенка',
                'подарил(а) котенка',
                'подарил(а) телевизор',
                'предложил(а) руку и сердце',
                'засунул(а) пальчик в попу',
                'подарил(а) мягкого медвежонка',
                'подарил(а) гоночную машинку на радиуправлении',
                'подарил(а) кубик рубика',
                'подарил(а) скакалку',
                'подарил(а) стринги',
                'подарил(а) лифчик',
                'подарил(а) семейные трусы',
                'подарил(а) дилдо',
                'подарил(а) мыло и веревку',
                'подарил(а) набор для ролевой игры в стюардессу и пассажира',
                'подарил(а) набор для ролевой игры в медсестру и больного',
                'подарил(а) набор для ролевой игры в сантехника и беспомощную девушку',
                'подарил(а) набор алкаша',
                'подарил(а) шапку ушанку',
                'подарил(а) набор разноцвестных презервативов',
                'подарил(а) раскраску',
                'подарил(а) журнал опытного анониста',
                'предложил(а) переспать без обязательств',
                'хочет познакомится с родителями',
                'предложил(а) секс втроем',
                'подарил(а) путевку на отдых в Крым',
                'подарил(а) путевку на отдых в Сочи',
                'подарил(а) путевку на отдых в Египет',
                'подарил(а) путевку на отдых в горы',
                'угостил(а) тортиком',
                'затянул(а) в душ',
                'подарил(а) почку',
            ],
            $request,
            '%user% %arr% %userreply%'
        );
    }

    /**
     * @param $request
     * @return mixed
     */
    private function undress($request)
    {
        if (
            isset($request['undress'])
            || !in_array(TgSession::getCall(), ['!снять', 'снять', '!раздеть', 'раздеть', '!ограбить', '!ограбить'])
            || is_null(TgSession::getUserReply())
        ) {
            return $request;
        }

        if (TgSession::getUserReply()->inventory->whereNotIn('inventory_id', [21, 22])->count() == 0) {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => TgSession::getUserReply()->link() .
                    ' ничего не имеет, чтоб раздеть или отобрать! Можно получить в подарок или купить в магазине!',
            ]);
        }

        if (TgSession::getUser()->money < 100) {
            $m = TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => TgSession::getUser()->link() . ' бомж и не может ничего снять или ограбить!',
            ]);
            sleep(5);
            TgSession::getApi()->deleteMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'message_id' => $m['message_id'],
            ]);
            return $request;
        }

        try {
            $item = TgSession::getUserReply()->inventory->whereNotIn('inventory_id', [21, 22])->random();
        } catch (\InvalidArgumentException $e) {
            exit();
        }
        $shopItems = Dict::getShop();

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => TgSession::getUser()->link() . ' снял(а) c ' . TgSession::getUserReply()->link() . ' ' .
                $shopItems[$item->inventory_id]['text'] . " за 100 \xF0\x9F\x92\xB8",
        ]);
        TgBotUser::where('tg_id', TgSession::getUser()->tg_id)->update(['money' => TgSession::getUser()->money - 100]);
        TgBotUserInventory::where('user_id', TgSession::getUserReply()->tg_id)
            ->where('inventory_id', $item->inventory_id)
            ->delete();

        $this->delMessage();
        $request['undress'] = true;
        return $request;
    }

    /**
     * @param $request
     * @return mixed
     */
    private function couple($request)
    {
        if (
            isset($request['couple'])
            || !in_array(TgSession::getCall(), ['/couple', '!пара', 'пара'])
            || TgSession::getChat()->users->count() < 2
        ) {
            return $request;
        }

        $users = TgSession::getChat()->users->random(2);
        $arr = [
            'сегодня воруют сумки у старух',
            'трахали пьяного бомжа',
            'обоссали лифт',
            'нажрались слабительного',
            'провели ночь с геями',
            'продавали наркотики',
            'проиграли все в казино',
            'бегали голышом под дождем',
            'напились самогона',
            'провели ночь с лесбиянками',
            'попробовали дилдо 50 сантиметров',
            'играли в дурака на раздевание',
            'лизали ободок унитаза',
            'украли использованную туалетную бумагу',
            'плавали в луже',
            'намазали пах друг друга жгучим перцем',
            'нюхали носки в обувном магазине',
            'мылись на пляже с мылом',
            'мылись в бассейне с гелем для душа',
            'работали попрошайками на трассе',
            'предлагали интимные услуги за еду',
            'предлагали интимные услуги за деньги',
            'жрали мыло',
            'собирали собачьи какашки по улице',
        ];
        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => $users->first()->info->link() . ' и ' . $users->last()->info->link() . ' ' . $arr[array_rand($arr)] .
                "\n\xF0\x9F\x92\xA3 \xF0\x9F\x91\xAF \xF0\x9F\x91\xA3 \xF0\x9F\x8E\x8E \xF0\x9F\x99\x8F \xF0\x9F\x99\x8C \xF0\x9F\x99\x88 \xF0\x9F\x98\xB9 \xF0\x9F\x98\xB1 \xF0\x9F\x98\x82",
        ]);
        $this->delMessage();
        $request['couple'] = true;
        return $request;
    }

    /**
     * @param $request
     * @return mixed
     */
    private function shved($request)
    {
        if (
            isset($request['shved'])
            || !in_array(TgSession::getCall(), ['/shved', '!швед', 'швед'])
        ) {
            return $request;
        }

        $users = ['b' => [], 'g' => []];
        $collection = TgSession::getChat()->users;
        foreach ($collection as $user) {
            $data = [];
            $data['user_id'] = $user->user_id;
            $data['sex'] = $user->info->sex;
            $data['link'] = $user->info->link();
            if ($user->info->sex == 'парень') {
                $users['b'][] = $data;
            } elseif ($user->info->sex == 'девушка') {
                $users['g'][] = $data;
            }
        }
        $boys = collect($users['b']);
        $girls = collect($users['g']);

        if ($boys->count() < 2 || $girls->count() < 2) {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => 'Для шведской семьи нехватает людей. Необходимо минимум 2 парня и 2 девушки в чате!',
            ]);
            $this->delMessage();
            return $request;
        }

        $boys = $boys->random(2);
        $girls = $girls->random(2);

        $text = "<b>ШВЕДСКАЯ СЕМЬЯ:</b>\n";
        $text .= " \xF0\x9F\x8C\x88 " . $boys->first()['link'] .
            " \xF0\x9F\x92\x98 " . $girls->first()['link'] .
            " \xF0\x9F\x8E\x82 " . $boys->last()['link'] .
            " \xF0\x9F\x92\x95 " . $girls->last()['link'] .
            " \xF0\x9F\x98\x98 ";
        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => $text,
        ]);
        $this->delMessage();
        $request['shved'] = true;
        return $request;
    }

    /**
     * @param $request
     * @return mixed
     */
    private function sexM1W2($request)
    {
        if (
            isset($request['sexM1W2'])
            || !in_array(TgSession::getCall(), ['/mw2', '!жмж', 'жмж'])
        ) {
            return $request;
        }

        $users = ['b' => [], 'g' => []];
        $collection = TgSession::getChat()->users;
        foreach ($collection as $user) {
            $data = [];
            $data['user_id'] = $user->user_id;
            $data['sex'] = $user->info->sex;
            $data['link'] = $user->info->link();
            if ($user->info->sex == 'парень') {
                $users['b'][] = $data;
            } elseif ($user->info->sex == 'девушка') {
                $users['g'][] = $data;
            }
        }
        $boys = collect($users['b']);
        $girls = collect($users['g']);

        if ($boys->count() < 1 || $girls->count() < 2) {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => 'Для ЖМЖ нехватает людей. Необходимо минимум 1 парень и 2 девушки в чате!',
            ]);
            $this->delMessage();
            return $request;
        }

        $boys = $boys->random();
        $girls = $girls->random(2);

        $text = "<b>ЖМЖ занимаются " . Dict::rand(Dict::getWhere()) . ":</b>\n";
        $text .= "\xF0\x9F\x92\x93 " . $girls->first()['link'] .
            " \xF0\x9F\x92\x98 " . $boys['link'] .
            " \xF0\x9F\x92\x95 " . $girls->last()['link'] .
            " \xF0\x9F\x98\x98 ";
        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => $text,
        ]);
        $this->delMessage();
        $request['sexM1W2'] = true;
        return $request;
    }

    /**
     * @param $request
     * @return mixed
     */
    private function sexM2W1($request)
    {
        if (
            isset($request['sexM2W1'])
            || !in_array(TgSession::getCall(), ['/m2w', '!мжм', 'мжм'])
        ) {
            return $request;
        }

        $users = ['b' => [], 'g' => []];
        $collection = TgSession::getChat()->users;
        foreach ($collection as $user) {
            $data = [];
            $data['user_id'] = $user->user_id;
            $data['sex'] = $user->info->sex;
            $data['link'] = $user->info->link();
            if ($user->info->sex == 'парень') {
                $users['b'][] = $data;
            } elseif ($user->info->sex == 'девушка') {
                $users['g'][] = $data;
            }
        }
        $boys = collect($users['b']);
        $girls = collect($users['g']);

        if ($boys->count() < 2 || $girls->count() < 1) {
            TgSession::getApi()->sendMessage([
                'chat_id' => TgSession::getParam('message.chat.id'),
                'text' => 'Для МЖМ нехватает людей. Необходимо минимум 2 парня и 1 девушка в чате!',
            ]);
            $this->delMessage();
            return $request;
        }

        $boys = $boys->random(2);
        $girls = $girls->random();

        $text = "<b>МЖМ занимаются " . Dict::rand(Dict::getWhere()) . ":</b>\n";
        $text .= "\xF0\x9F\x92\x93 " . $boys->first()['link'] .
            " \xF0\x9F\x92\x98 " . $girls['link'] .
            " \xF0\x9F\x92\x95 " . $boys->last()['link'] .
            " \xF0\x9F\x98\x98 ";
        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => $text,
        ]);
        $this->delMessage();
        $request['sexM2W1'] = true;
        return $request;
    }

    /**
     * @param $name
     * @param $commands
     * @param $arr
     * @param $request
     * @param string $template
     * @return mixed
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function defCommand($name, $commands, $arr, $request, $template = '%user% %arr% %userreply% %where%')
    {
        if (
            isset($request[$name])
            || !in_array(TgSession::getCall(), $commands)
            || is_null(TgSession::getUserReply())
        ) {
            return $request;
        }

        $template = str_replace('%user%', TgSession::getUser()->link(), $template);
        $template = str_replace('%arr%', $arr[array_rand($arr)], $template);
        $template = str_replace('%userreply%', TgSession::getUserReply()->link(), $template);
        $template = str_replace('%where%', Dict::rand(Dict::getWhere()), $template);

        TgSession::getApi()->sendMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'text' => $template,
        ]);

        $this->delMessage();
        $request[$name] = true;
        return $request;
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function delMessage()
    {
        TgSession::getApi()->deleteMessage([
            'chat_id' => TgSession::getParam('message.chat.id'),
            'message_id' => TgSession::getParam('message.message_id'),
        ]);
    }
}
