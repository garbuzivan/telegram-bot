<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TgBotUserInventory extends Model
{
    use HasFactory;

    protected $table = 'gi_tb_user_inventory';

    /**
     * @var array<string> $fillable
     */
    protected $fillable = [
        'user_id',
        'inventory_id',
        'name',
    ];
}
