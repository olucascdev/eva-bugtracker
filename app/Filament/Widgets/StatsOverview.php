<?php

namespace App\Filament\Widgets;

use App\Enums\BugStatusEnum;
use App\Models\Bug;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class StatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $companyId = $this->filters['company_id'] ?? null;
        $period = $this->filters['period'] ?? 30;
        $start = now()->subDays($period);
        $end = now();

        $baseQuery = Bug::query()
            ->when($companyId, fn (Builder $q) => $q->where('company_id', $companyId))
            ->when($start, fn (Builder $q) => $q->whereDate('created_at', '>=', $start))
            ->when($end, fn (Builder $q) => $q->whereDate('created_at', '<=', $end));

        $totalOpen = (clone $baseQuery)
            ->whereHas('status', fn ($q) => $q->whereNotIn('slug', [BugStatusEnum::RESOLVIDO->value, BugStatusEnum::FECHADO->value]))
            ->count();

        $totalResolved = (clone $baseQuery)
            ->whereHas('status', fn ($q) => $q->where('slug', BugStatusEnum::RESOLVIDO->value))
            ->count();

        $total = (clone $baseQuery)->count();
        $resolutionRate = $total > 0 ? round(($totalResolved / $total) * 100, 1) : 0;

        $overdue = (clone $baseQuery)
            ->whereNotNull('estimated_completion_at')
            ->whereNull('completed_at')
            ->where('estimated_completion_at', '<', now())
            ->whereHas('status', fn ($q) => $q->whereNotIn('slug', [BugStatusEnum::RESOLVIDO->value, BugStatusEnum::FECHADO->value]))
            ->count();

        $avgSeconds = (clone $baseQuery)
            ->whereNotNull('completed_at')
            ->selectRaw('AVG(EXTRACT(EPOCH FROM (completed_at - created_at))) as avg_seconds')
            ->value('avg_seconds');

        $mttr = '-';
        if ($avgSeconds) {
            $days = $avgSeconds / 86400;
            if ($days < 1) {
                $hours = $avgSeconds / 3600;
                $mttr = round($hours, 1).' horas';
            } else {
                $mttr = round($days, 1).' dias';
            }
        }

        return [
            Stat::make('Total Bugs', $total)
                ->description('Recebidos no período')
                ->descriptionIcon('heroicon-m-inbox')
                ->color('gray'),

            Stat::make('Bugs Abertos', $totalOpen)
                ->description('Aguardando resolução')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),

            Stat::make('Resolvidos', $totalResolved)
                ->description('Concluídos no período')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Taxa de Resolução', $resolutionRate.'%')
                ->description('Do total reportado')
                ->color($resolutionRate > 80 ? 'success' : ($resolutionRate > 50 ? 'warning' : 'danger')),

            Stat::make('Atrasados (SLA)', $overdue)
                ->description('Passaram do prazo estimado')
                ->descriptionIcon('heroicon-m-clock')
                ->color($overdue > 0 ? 'danger' : 'success'),

            Stat::make('MTTR', $mttr)
                ->description('Tempo Médio Resolução')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),
        ];
    }
}
