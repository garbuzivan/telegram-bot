<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;

abstract class AbstractCommand
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $description;

    /**
     * @param $request
     * @param Closure $next
     */
    abstract public function handler($request, Closure $next);
}
