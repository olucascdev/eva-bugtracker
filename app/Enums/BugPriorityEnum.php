<?php

namespace App\Enums;

enum BugPriorityEnum: string
{
    case CRITICA = 'critica';
    case ALTA = 'alta';
    case MEDIA = 'media';
    case BAIXA = 'baixa';
    case MINIMA = 'minima';

    public function label(): string
    {
        return match ($this) {
            self::CRITICA => 'Crítica',
            self::ALTA => 'Alta',
            self::MEDIA => 'Média',
            self::BAIXA => 'Baixa',
            self::MINIMA => 'Mínima',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::CRITICA => '#DC2626',
            self::ALTA => '#F59E0B',
            self::MEDIA => '#3B82F6',
            self::BAIXA => '#10B981',
            self::MINIMA => '#6B7280',
        };
    }

    public function level(): int
    {
        return match ($this) {
            self::CRITICA => 5,
            self::ALTA => 4,
            self::MEDIA => 3,
            self::BAIXA => 2,
            self::MINIMA => 1,
        };
    }

    public function isCritical(): bool
    {
        return $this === self::CRITICA;
    }

    public function isHighPriority(): bool
    {
        return in_array($this, [self::CRITICA, self::ALTA]);
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }

    public static function sortedByLevel(): array
    {
        return collect(self::cases())
            ->sortByDesc(fn($case) => $case->level())
            ->values()
            ->toArray();
    }
}
