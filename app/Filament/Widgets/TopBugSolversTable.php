<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopBugSolversTable extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 1;

    protected static ?string $heading = 'Principais Solucionadores';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->withCount(['assignedBugs' => function (Builder $query) {
                        $companyId = $this->filters['company_id'] ?? null;
                        $period = $this->filters['period'] ?? 30;
                        $start = now()->subDays($period);
                        $end = now();

                        $query
                            ->whereNotNull('completed_at')
                            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
                            ->when($start, fn ($q) => $q->whereDate('completed_at', '>=', $start))
                            ->when($end, fn ($q) => $q->whereDate('completed_at', '<=', $end));
                    }])
//                    ->having('assigned_bugs_count', '>', 0)
                    ->orderByDesc('assigned_bugs_count')
                    ->take(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Desenvolvedor'),
                Tables\Columns\TextColumn::make('assigned_bugs_count')
                    ->label('Resolvidos')
                    ->badge()
                    ->color('success')
                    ->alignCenter(),
            ])
            ->paginated(false);
    }
}
