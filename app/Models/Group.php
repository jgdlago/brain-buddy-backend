<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Group extends Model
{
    protected $table = 'groups';

    protected $fillable = [
        'name',
        'responsible_user_id',
        'institution_id'
    ];

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
