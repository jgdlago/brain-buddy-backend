<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerProgress extends Model
{
    protected $table = 'player_progress';

    protected $fillable = [
        'player_id',
        'total_correct',
        'total_wrong',
        'total_attempts',
        'completed',
        'completion_date',
    ];

    protected $casts = [
        'completion_date' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
