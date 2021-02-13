<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

class Help extends Start
{
    /**
     * @var string Command Name
     */
    public string $name = "/help";

    /**
     * @var string Command Description
     */
    public string $description = "Помощник";
}
