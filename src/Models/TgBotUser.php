<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Models;

use DateTime;
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

    protected $appends = [
        'full_name',
        'active_bonus',
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
    public function getFullNameAttribute()
    {
        return ucwords($this->first_name . ' ' . $this->last_name);
    }

    public function bonus(){
        return $this->hasOne('\GarbuzIvan\TelegramBot\Models\TgBotBonus', 'tg_id', 'user_id');
    }

    public function getActiveBonusAttributes()
    {
        if(!isset($this->bonus) || is_null($this->bonus) || !isset($this->bonus->created_at)){
            return false;
        }
//        $first = DateTime::createFromFormat('d.m.Y', '01.01.2016');
//        $second = DateTime::createFromFormat('d.m.Y', '25.12.2015');
//        return $first < $second;
        return true;
    }
}
