<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot;

class Dict
{
    public static function getWherePeople(): array
    {
        return [
            'щеку',
            'шую',
            'нос',
            'губы',
            'лоб',
            'плечо',
            'локоть',
            'кисть',
            'руку',
            'ногу',
            'палец',
            'писюн',
            'член',
            'влагалище',
            'попу',
            'жопу',
            'булку',
            'мизинец',
            'лодошку',
            'пятку',
            'провь',
            'зубы',
            'подбородок',
        ];
    }

    public static function getWhere(): array
    {
        return [
            'Дома',
            'За гаражами',
            'В столовой',
            'В ресторане',
            'В спальне',
            'На подоконнике',
            'На столе',
            'В Москве',
            'В Питере',
            'В Крыму',
            'В Турции',
            'В Италии',
            'В жопе',
            'В пизде',
            'На горе',
            'В море',
            'В открытом океане',
            'В космосе',
            'В пивнухе',
            'В шалаше',
            'В гостинице',
            'В отеле',
            'В приюте для животных',
            'В альпах',
            'На Эльфелевой башне',
            'На пирамиде в Египте',
            'На лошади',
            'В машине',
            'На багажнике автомобиля',
            'На капоте автомобиля',
            'На заднем сидении',
            'В туалете',
            'на балконе',
            'на кухне',
            'на лавочке',
            'на полу',
            'на кровати',
            'на диване',
            'в спальне',
            'в мавзолее',
            'в подъезде',
            'в парке',
            'в кафе',
            'в гостях',
            'в душе',
            'в лифте',
            'в бассейне',
            'в общественом транспорте',
            'на сеновале',
            'на пляже',
            'на съемках порно фильма',
            'на подоконнике',
            'на помойке',
            'вися на турнике',
        ];
    }

    /**
     * @param array $dict
     * @return mixed
     */
    public static function rand(array $dict)
    {
        return trim(mb_strtolower($dict[array_rand($dict)]));
    }
}