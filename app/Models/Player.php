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
        'institution_id'
    ];

    protected $casts = [
        'gender' => GenderEnum::class,
        'age' => CharacterEnum::class
    ];

    /**
     * @return BelongsTo
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
