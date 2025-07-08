<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    protected $table = 'levels';

    protected $fillable = [
        'name',
        'order'
    ];

    /**
     * @return HasMany
     */
    public function helpFlags(): HasMany
    {
        return $this->hasMany(HelpFlag::class);
    }
}
