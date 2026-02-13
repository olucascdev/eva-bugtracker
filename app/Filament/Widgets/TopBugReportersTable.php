<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class TopBugReportersTable extends BaseWidget
{
    use InteractsWithPageFilters;
    
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;
    protected static ?string $heading = 'Principais Reportadores';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->withCount(['reportedBugs' => function (Builder $query) {
                        $companyId = $this->filters['company_id'] ?? null;
                        $period = $this->filters['period'] ?? 30;
                        $start = now()->subDays($period);
                        $end = now();
                        
                        $query
                            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
                            ->when($start, fn($q) => $q->whereDate('created_at', '>=', $start))
                            ->when($end, fn($q) => $q->whereDate('created_at', '<=', $end));
                    }])
                    ->orderByDesc('reported_bugs_count')
                    ->take(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Usuário'),
                Tables\Columns\TextColumn::make('company.name')->label('Empresa'),
                Tables\Columns\TextColumn::make('reported_bugs_count')->label('Bugs Reportados')->alignCenter(),
            ])
            ->paginated(false);
    }
}
