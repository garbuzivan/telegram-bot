<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TgBotChatUsers extends Model
{
    use HasFactory;

    protected $table = 'gi_tb_chat_users';

    /**
     * @var array<string> $fillable
     */
    protected $fillable = [
        'chat_id',
        'user_id',
    ];

    public function info()
    {
        return $this->hasOne('\GarbuzIvan\TelegramBot\Models\TgBotUser', 'tg_id', 'user_id');
    }
}