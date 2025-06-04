<?php

namespace App\Enums;

enum ItemStatus: string
{
    case Available = 'verfügbar';
    case Damaged = 'beschädigt';
    case InRepair = 'in_Reparatur';
    case OnLoan = 'in_Verleih';

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [
                $case->value => str_replace('_', ' ', ucfirst($case->value))
            ])
            ->toArray();
    }

    public static function asSelectArray(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => ucfirst(str_replace('_', ' ', $case->value)),
        ])->toArray();
    }
}
