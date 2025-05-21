<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use InvalidArgumentException;

class CnpjCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string|null
     */
    public function get(Model $model, string $key, $value, array $attributes): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            $decrypted = Crypt::decryptString($value);
            return $this->formatCnpj($decrypted);
        } catch (DecryptException $e) {
            return null;
        }
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return string|null
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (empty($value)) {
            return null;
        }

        $cleanCnpj = $this->removeMask($value);

        if (!$this->validateCnpj($cleanCnpj)) {
            throw new InvalidArgumentException('O CNPJ fornecido não é válido.');
        }

        return Crypt::encryptString($cleanCnpj);
    }

    /**
     * @param  string  $cnpj
     * @return string
     */
    private function removeMask(string $cnpj): string
    {
        return preg_replace('/[^0-9]/', '', $cnpj);
    }

    /**
     * @param  string  $cnpj
     * @return string
     */
    private function formatCnpj(string $cnpj): string
    {
        $cnpj = $this->removeMask($cnpj);

        if (strlen($cnpj) !== 14) {
            return $cnpj;
        }

        return preg_replace('/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/', '$1.$2.$3/$4-$5', $cnpj);
    }

    /**
     * @param  string  $cnpj
     * @return bool
     */
    private function validateCnpj(string $cnpj): bool
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) != 14) {
            return false;
        }

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        $sum = 0;
        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 12; $i++) {
            $sum += $cnpj[$i] * $weights[$i];
        }

        $remainder = $sum % 11;
        $digit1 = $remainder < 2 ? 0 : 11 - $remainder;

        if ($cnpj[12] != $digit1) {
            return false;
        }

        $sum = 0;
        $weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 13; $i++) {
            $sum += $cnpj[$i] * $weights[$i];
        }

        $remainder = $sum % 11;
        $digit2 = $remainder < 2 ? 0 : 11 - $remainder;

        return $cnpj[13] == $digit2;
    }
}
