<?php

namespace App\Core\Guid;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class GuidModel extends Model
{

    protected $primaryKey = 'guid';

    protected $table = 'guids';

    protected $fillable = [
        'value', 'assigned_to', 'status', 'created_at'
    ];

    public $incrementing = false;

    protected $dates = [
        'created_at'
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
        return $query->where('created_at', '<=', Carbon::now()->subDays($days)->toDateTimeString());
    }

    /**
     * @param $query
     */
    public function scopeNonAssigned($query)
    {
        return $query->where('status', '=', Guid::GUID_STATUS_ISSUED);
    }
}
