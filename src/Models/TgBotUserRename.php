<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TgBotUserRename extends Model
{
    use HasFactory;

    protected $table = 'gi_tb_user_rename';

    /**
     * @var array<string> $fillable
     */
    protected $fillable = [
        'user_id',
        'name',
    ];

    public function user()
    {
        return $this->hasOne('\GarbuzIvan\TelegramBot\Models\TgBotUser', 'tg_id', 'user_id');
    }
}
