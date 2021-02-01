<?php

declare(strict_types=1);

namespace GarbuzIvan\LaravelAuthApi\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeEmail extends Model
{
    use HasFactory;

    protected $table = 'auth_api_email_code';

    /**
     * @var array<string> $fillable
     */
    protected $fillable = [
        'email',
        'code',
        'pass',
        'use',
    ];

    /**
     * Get a list of unused codes sent by email
     *
     * @param $query
     * @return mixed
     */
    public function scopeListNoUse($query)
    {
        return $query->where('user', 0);
    }
}
