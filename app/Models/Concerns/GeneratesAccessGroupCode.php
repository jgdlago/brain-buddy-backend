<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait GeneratesAccessGroupCode
{
    /**
     * @return void
     */
    protected static function bootGeneratesAccessGroupCode(): void
    {
        static::creating(function ($model) {
            if (empty($model->access_code)) {
                $model->access_code = $model->generateUniqueCode();
            }
        });
    }

    /**
     * @return string
     */
    public function generateUniqueCode(): string
    {
        $maxAttempts = 10;
        $codeLength = 6;

        do {
            $code = Str::upper(Str::random($codeLength));
        } while (static::codeExists($code) && $maxAttempts-- > 0);

        return $maxAttempts > 0
            ? $code
            : $this->createFallbackCode();
    }

    /**
     * @return string
     */
    private function createFallbackCode(): string
    {
        return Str::upper(Str::random(8));
    }

    /**
     * @param string $code
     * @return bool
     */
    private static function codeExists(string $code): bool
    {
        return static::where('access_code', $code)->exists();
    }
}
