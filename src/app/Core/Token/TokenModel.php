<?php

namespace App\Core\Token;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TokenModel extends Model
{
    protected $table = 'api_tokens';

    protected $dates = [
        'ends_at'
    ];

    public $timestamps = false;

    /**
     * @param $query
     * @param int $days
     *
     * @return mixed
     */
    public function scopeOlderThan($query, $days)
    {
        return $query->where('ends_at', '<=', Carbon::now()->subDays($days)->toDateTimeString());
    }
}
