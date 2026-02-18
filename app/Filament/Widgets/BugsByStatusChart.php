<?php

namespace App\Filament\Widgets;

use App\Models\Bug;
use App\Models\BugStatus;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class BugsByStatusChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Bugs por Status';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $companyId = $this->filters['company_id'] ?? null;
        $period = $this->filters['period'] ?? 30;
        $start = now()->subDays($period);
        $end = now();

        $statuses = BugStatus::orderBy('order')->get();
        $labels = [];
        $data = [];
        $colors = [];

        foreach ($statuses as $status) {
            $count = Bug::query()
                ->where('bug_status_id', $status->id)
                ->when($companyId, fn (Builder $q) => $q->where('company_id', $companyId))
                ->when($start, fn (Builder $q) => $q->whereDate('created_at', '>=', $start))
                ->when($end, fn (Builder $q) => $q->whereDate('created_at', '<=', $end))
                ->count();

            $labels[] = $status->name;
            $data[] = $count;
            $colors[] = $status->color; // Assuming color is hex or compatible
        }

        return [
            'datasets' => [
                [
                    'label' => 'Bugs',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
