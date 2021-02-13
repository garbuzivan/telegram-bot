<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Tests;

use GarbuzIvan\TelegramBot\TgSession;
use PHPUnit\Framework\TestCase;

class TgSessionTest extends TestCase
{
    public function testGetParam()
    {
        $param = [
            'test',
            'test2' => [
                'v1',
                'v2' => [
                    'b1',
                    'b2' => 'bot',
                    'b3',
                ],
                'v3',
            ],
            'test3',
        ];
        TgSession::setParam($param);
        $this->assertIsBool(strcmp(json_encode($param), json_encode(TgSession::getParam())) == 0);
        $this->assertIsBool(TgSession::getParam('test2.v2.b2') == 'bot');
    }
}
