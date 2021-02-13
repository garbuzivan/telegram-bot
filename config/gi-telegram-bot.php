<?php

return [

    'token' =>  '',

    // Auth methods
    'commands' => [
        \GarbuzIvan\TelegramBot\Commands\Start::class,
        \GarbuzIvan\TelegramBot\Commands\Help::class,
        \GarbuzIvan\TelegramBot\Commands\Rank::class,
    ],

];
