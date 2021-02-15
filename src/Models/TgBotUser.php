<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot\Models;

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
    public function fullname()
    {
        return trim(ucwords($this->first_name . ' ' . $this->last_name));
    }

    public function link()
    {
        return '<a href="tg://user?id=' . $this->tg_id . '">' . $this->fullname() . '</a>';
    }

    public function getSex()
    {
        return !is_null($this->sex) && mb_strlen($this->sex) > 0 ? $this->sex : 'Пол можно установить командой !парень или !девушка';
    }

    public function timer()
    {
        return $this->belongsTo('\GarbuzIvan\TelegramBot\Models\TgBotTimer', 'tg_id', 'user_id');
    }

    public function chats()
    {
        return $this->hasMany('\GarbuzIvan\TelegramBot\Models\TgBotChatUsers', 'tg_id', 'user_id');
    }

    public function rename()
    {
        return $this->hasMany('\GarbuzIvan\TelegramBot\Models\TgBotUserRename', 'tg_id', 'user_id');
    }

    public function titles()
    {
        return $this->hasMany('\GarbuzIvan\TelegramBot\Models\TgBotUserTitle', 'tg_id', 'user_id');
    }

    public function getTimer(TgBotUser $user, string $param, string $strtotime = '-6 hours'): bool
    {
        $timerUser = $user->timer;
        foreach ($timerUser as $timer) {
            if ($timer->param != $param) {
                continue;
            }
            if ($timer->created_at->timestamp > strtotime($strtotime)) {
                return false;
            }
            break;
        }
        return true;
    }
}
