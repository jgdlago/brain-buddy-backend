<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpFlag extends Model
{
    protected $table = 'player_help_flags';

    public $timestamps = false;

    protected $fillable = [
        'player_id',
        'trigger_date',
        'level_id',
    ];

    /**
     * @return BelongsTo
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    /**
     * @return BelongsTo
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}

