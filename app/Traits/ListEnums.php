<?php

namespace App\Traits;

trait ListEnums
{
    /**
     * @return array<int, array{
     *     value: string,
     *     label: string
     * }>
     */
    public static function list(): array
    {
        return array_map(
            fn(self $e) => [
                'key' => $e->value,
                'label' => method_exists($e, 'label') ? $e->label() : $e->name,
            ],
            self::cases()
        );
    }

    /**
     * Retorna um map de value => label.
     *
     * @return array<string, string>
     */
    public static function listLabels(): array
    {
        $list = [];
        foreach (self::cases() as $e) {
            $list[$e->value] = method_exists($e, 'label') ? $e->label() : $e->name;
        }

        return $list;
    }
}
