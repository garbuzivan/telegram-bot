<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;

abstract class AbstractCommand
{
    /**
     * @var string
     */
    abstract public string $name;

    /**
     * @var string
     */
    abstract public string $description;

    /**
     * @param $request
     * @param Closure $next
     */
    abstract public function handle($request, Closure $next);
}
