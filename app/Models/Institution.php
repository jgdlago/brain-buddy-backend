<?php

namespace App\Models;

use App\Casts\CnpjCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Institution extends Model
{
    protected $table = 'institutions';

    protected $fillable = [
        'name',
        'cnpj',
        'activity_area_id'
    ];

    protected $casts = [
        'cnpj' => CnpjCast::class
    ];

    /**
     * @return HasOne
     */
    public function activityArea(): HasOne
    {
        return $this->hasOne(ActivityArea::class, 'id', 'activity_area_id');
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_institutions', 'institution_id', 'user_id');
    }
}
