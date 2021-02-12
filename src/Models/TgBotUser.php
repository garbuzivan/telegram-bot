<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TgBotUser extends Model
{
    use HasFactory;

    protected $table = 'gi_tb_user';

    /**
     * @var array<string> $fillable
     */
    protected $fillable = [
        'tg_id',
        'is_bot',
        'username',
        'first_name',
        'last_name',
        'title',
    ];

    /**
     * Get a list of unused codes sent by email
     *
     * @param $query
     * @return mixed
     */
    public function scopeNoBot($query)
    {
        return $query->where('is_bot', 0);
    }

    /**
     * @return string
     */
    public function fullName(): string
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }
}
