<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TgBotChat extends Model
{
    use HasFactory;

    protected $table = 'gi_tb_chats';

    /**
     * @var array<string> $fillable
     */
    protected $fillable = [
        'chat_id',
        'chat_title',
    ];

    public function admin(){
        return $this->hasMany('\GarbuzIvan\TelegramBot\Models\TgBotChatAdmin', 'chat_id', 'chat_id');
    }

    public function timer()
    {
        return $this->hasMany('\GarbuzIvan\TelegramBot\Models\TgBotTimer', 'chat_id', 'chat_id');
    }

    public function users()
    {
        return $this->hasMany('\GarbuzIvan\TelegramBot\Models\TgBotChatUsers', 'chat_id', 'chat_id');
    }
}
