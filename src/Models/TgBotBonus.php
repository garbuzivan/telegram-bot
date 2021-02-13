<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TgBotBonus extends Model
{
    use HasFactory;

    protected $table = 'gi_tb_bonus';

    /**
     * @var array<string> $fillable
     */
    protected $fillable = [
        'user_id',
    ];
}
