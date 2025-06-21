<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Group extends Model
{
    protected $table = 'groups';

    protected $fillable = [
        'name',
        'responsible_user_id',
        'institution_id',
        'education_level',
        'code',
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

    /**
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($group) {
            $group->code = self::generateUniqueCode();
        });
    }

    /**
     * @return string
     */
    public static function generateUniqueCode(): string
    {
        $maxAttempts = 10;
        $code = '';

        do {
            $randomPart = Str::upper(Str::random(4));

            $numericPart = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

            $code = $randomPart . $numericPart;
        } while (self::codeExists($code) && $maxAttempts-- > 0);

        return $code;
    }

    /**
     * @param string $code
     * @return bool
     */
    private static function codeExists(string $code): bool
    {
        return static::where('code', $code)->exists();
    }
}
