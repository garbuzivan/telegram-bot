<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TgBotUserTitle extends Model
{
    use HasFactory;

    protected $table = 'gi_tb_user_title';

    /**
     * @var array<string> $fillable
     */
    protected $fillable = [
        'user_id',
        'title',
    ];
}
