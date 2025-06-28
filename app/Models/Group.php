<?php

namespace App\Models;

use App\Models\Concerns\GeneratesAccessGroupCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use GeneratesAccessGroupCode;

    protected $table = 'groups';

    protected $fillable = [
        'name',
        'responsible_user_id',
        'institution_id',
        'education_level',
        'access_code',
    ];

    /**
     * @return HasMany
     */
    public function players(): HasMany
    {
        return $this->hasMany(Player::class, 'group_id');
    }

    /**
     * @return BelongsTo
     */
    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
