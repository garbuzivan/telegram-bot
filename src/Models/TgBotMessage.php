<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TgBotMessage extends Model
{
    use HasFactory;

    protected $table = 'gi_tb_messages';

    /**
     * @var array<string> $fillable
     */
    protected $fillable = [
        'update_id',
        'message_id',
        'from_id',
        'chat_id',
        'chat_title',
        'date',
        'reply_message_id',
        'reply_from_id',
        'text',
        'json',
    ];

}
