<?php

namespace App\Models;

use App\Enums\CharacterEnum;
use App\Enums\GenderEnum;
use App\Enums\PerformanceFlagEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Prompts\Progress;
use PHPUnit\TextUI\Help;

class Player extends Model
{
    protected $table = 'players';

    protected $fillable = [
        'name',
        'gender',
        'age',
        'character',
        'group_id',
        'performance_flag'
    ];

    protected $casts = [
        'gender' => GenderEnum::class,
        'character' => CharacterEnum::class,
        'performance_flag' => PerformanceFlagEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * @return HasMany
     */
    public function helpFlags(): HasMany
    {
        return $this->hasMany(HelpFlag::class, 'player_id');
    }

    public function progresses(): hasMany
    {
        return $this->hasMany(PlayerProgress::class, 'player_id');
    }
}
