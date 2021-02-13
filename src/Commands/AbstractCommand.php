<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Commands;

use Closure;

abstract class AbstractCommand
{
    /**
     * @var
     */
    abstract public $name;

    /**
     * @var
     */
    abstract public $description;

    /**
     * @param $request
     * @param Closure $next
     */
    abstract public function handle($request, Closure $next);
}
