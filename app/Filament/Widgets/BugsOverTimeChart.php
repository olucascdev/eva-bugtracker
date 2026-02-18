<?php

namespace App\Filament\Widgets;

use App\Models\Bug;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class BugsOverTimeChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Entrada vs Saída (Últimos dias)';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $companyId = $this->filters['company_id'] ?? null;
        $period = $this->filters['period'] ?? 30;
        $start = now()->subDays($period);
        $end = now();

        $dates = [];
        $dataCreated = [];
        $dataResolved = [];

        // Fetch aggregated data
        $createdCounts = Bug::query()
            ->when($companyId, fn (Builder $q) => $q->where('company_id', $companyId))
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw("to_char(created_at, 'YYYY-MM-DD') as date, count(*) as count")
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $resolvedCounts = Bug::query()
            ->when($companyId, fn (Builder $q) => $q->where('company_id', $companyId))
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$start, $end])
            ->selectRaw("to_char(completed_at, 'YYYY-MM-DD') as date, count(*) as count")
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Fill gaps
        $period = \Carbon\CarbonPeriod::create($start, $end);

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $dates[] = $date->format('d/m');
            $dataCreated[] = $createdCounts[$dateString] ?? 0;
            $dataResolved[] = $resolvedCounts[$dateString] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Criados',
                    'data' => $dataCreated,
                    'borderColor' => '#EF4444',
                    'fill' => false,
                ],
                [
                    'label' => 'Resolvidos',
                    'data' => $dataResolved,
                    'borderColor' => '#10B981',
                    'fill' => false,
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
