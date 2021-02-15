<?php

return [

    'token' =>  '',

    // Auth methods
    'commands' => [
        \GarbuzIvan\TelegramBot\Commands\All::class,
        \GarbuzIvan\TelegramBot\Commands\Bonus::class,
        \GarbuzIvan\TelegramBot\Commands\Help::class,
        \GarbuzIvan\TelegramBot\Commands\Inventory::class,
        \GarbuzIvan\TelegramBot\Commands\Rank::class,
        \GarbuzIvan\TelegramBot\Commands\Sex::class,
        \GarbuzIvan\TelegramBot\Commands\Shop::class,
        \GarbuzIvan\TelegramBot\Commands\Start::class,
        \GarbuzIvan\TelegramBot\Commands\View::class,
        \GarbuzIvan\TelegramBot\Commands\Where::class,
        \GarbuzIvan\TelegramBot\Commands\Who::class,
    ],

    'botnames' => [
        '@yanmegabot',
        '@yansexbot',
    ],

];
