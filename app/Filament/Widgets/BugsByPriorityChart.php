<?php

namespace App\Filament\Widgets;

use App\Models\Bug;
use App\Models\BugPriority;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class BugsByPriorityChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Bugs por Prioridade';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $companyId = $this->filters['company_id'] ?? null;
        $period = $this->filters['period'] ?? 30;
        $start = now()->subDays($period);
        $end = now();
        
        $priorities = BugPriority::orderByDesc('level')->get();
        
        $labels = [];
        $data = [];
        $colors = [];
        
        foreach ($priorities as $priority) {
             $count = Bug::query()
                ->where('bug_priority_id', $priority->id)
                ->when($companyId, fn(Builder $q) => $q->where('company_id', $companyId))
                ->when($start, fn(Builder $q) => $q->whereDate('created_at', '>=', $start))
                ->when($end, fn(Builder $q) => $q->whereDate('created_at', '<=', $end))
                ->count();
            
            $labels[] = $priority->name;
            $data[] = $count;
            $colors[] = $priority->color;
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
        return 'bar';
    }
}
