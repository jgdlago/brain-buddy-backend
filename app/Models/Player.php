<?php

namespace App\Models;

use App\Enums\CharacterEnum;
use App\Enums\GenderEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    protected $table = 'players';

    protected $fillable = [
        'name',
        'gender',
        'age',
        'character',
        'group_id'
    ];

    protected $casts = [
        'gender' => GenderEnum::class,
        'character' => CharacterEnum::class
    ];

    /**
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
