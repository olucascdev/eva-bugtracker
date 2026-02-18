<?php

namespace App\Filament\Client\Widgets;

use App\Enums\BugStatusEnum;
use App\Models\Bug;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClientOverviewStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalBugs = Bug::count();
        $openBugs = Bug::whereHas('status', function ($query) {
            $query->whereIn('slug', [
                BugStatusEnum::REPORTADO->value,
                BugStatusEnum::EM_ANALISE->value,
                BugStatusEnum::EM_DESENVOLVIMENTO->value,
                BugStatusEnum::AGUARDANDO_TESTE->value,
            ]);
        })->count();

        $resolvedBugs = Bug::whereHas('status', function ($query) {
            $query->whereIn('slug', [
                BugStatusEnum::RESOLVIDO->value,
                BugStatusEnum::FECHADO->value,
            ]);
        })->count();

        $resolutionRate = $totalBugs > 0 ? ($resolvedBugs / $totalBugs) * 100 : 0;

        return [
            Stat::make('Total de Bugs', $totalBugs)
                ->description('Total de bugs registrados')
                ->descriptionIcon('heroicon-m-bug-ant')
                ->color('primary'),

            Stat::make('Bugs Abertos', $openBugs)
                ->description('Bugs em andamento')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Bugs Resolvidos', $resolvedBugs)
                ->description('Bugs concluídos')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Taxa de Resolução', number_format($resolutionRate, 1).'%')
                ->description('Percentual de bugs resolvidos')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
        ];
    }
}
