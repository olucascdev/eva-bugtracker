<?php

namespace App\Enums;

enum BugStatusEnum: string
{
    case REPORTADO = 'reportado';
    case EM_ANALISE = 'em-analise';
    case EM_DESENVOLVIMENTO = 'em-desenvolvimento';
    case AGUARDANDO_TESTE = 'aguardando-teste';
    case RESOLVIDO = 'resolvido';
    case FECHADO = 'fechado';

    public function label(): string
    {
        return match ($this) {
            self::REPORTADO => 'Reportado',
            self::EM_ANALISE => 'Em Análise',
            self::EM_DESENVOLVIMENTO => 'Em Desenvolvimento',
            self::AGUARDANDO_TESTE => 'Aguardando Teste',
            self::RESOLVIDO => 'Resolvido',
            self::FECHADO => 'Fechado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::REPORTADO => '#EF4444',
            self::EM_ANALISE => '#F59E0B',
            self::EM_DESENVOLVIMENTO => '#3B82F6',
            self::AGUARDANDO_TESTE => '#8B5CF6',
            self::RESOLVIDO => '#10B981',
            self::FECHADO => '#6B7280',
        };
    }

    public function order(): int
    {
        return match ($this) {
            self::REPORTADO => 1,
            self::EM_ANALISE => 2,
            self::EM_DESENVOLVIMENTO => 3,
            self::AGUARDANDO_TESTE => 4,
            self::RESOLVIDO => 5,
            self::FECHADO => 6,
        };
    }

    public function isDefault(): bool
    {
        return $this === self::REPORTADO;
    }

    public function isCompleted(): bool
    {
        return in_array($this, [self::RESOLVIDO, self::FECHADO]);
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
