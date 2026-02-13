<?php

namespace App\Filament\Resources\Bugs\Schemas;

use Filament\Schemas\Schema;

class BugInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Detalhes do Bug')
                    ->columns(2)
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('title')
                            ->label('Título')
                            ->columnSpanFull(),
                        \Filament\Infolists\Components\TextEntry::make('description')
                            ->label('Descrição')
                            ->html()
                            ->columnSpanFull(),
                        \Filament\Infolists\Components\TextEntry::make('expected_behavior')
                            ->label('Comportamento Esperado')
                            ->columnSpanFull(),
                        \Filament\Infolists\Components\TextEntry::make('conversation_link')
                            ->label('Link da Conversa')
                            ->url(fn ($state) => $state, true),
                    ]),
                \Filament\Schemas\Components\Section::make('Status e Responsáveis')
                    ->columns(2)
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('company.name')
                            ->label('Empresa'),
                        \Filament\Infolists\Components\TextEntry::make('status.name')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state) => match ($state) {
                                'Reportado' => 'danger',
                                'Em Análise' => 'warning',
                                'Em Desenvolvimento' => 'info',
                                'Aguardando Teste' => 'primary',
                                'Resolvido' => 'success',
                                'Fechado' => 'gray',
                                default => 'gray',
                            }),
                        \Filament\Infolists\Components\TextEntry::make('priority.name')
                            ->label('Prioridade')
                            ->badge()
                            ->color(fn (string $state) => match ($state) {
                                'Crítica' => 'danger',
                                'Alta' => 'warning',
                                'Média' => 'info',
                                'Baixa' => 'success',
                                'Mínima' => 'gray',
                                default => 'gray',
                            }),
                        \Filament\Infolists\Components\TextEntry::make('reportedBy.name')
                            ->label('Reportado por'),
                        \Filament\Infolists\Components\TextEntry::make('assignedTo.name')
                            ->label('Atribuído a'),
                    ]),
                \Filament\Schemas\Components\Section::make('Datas')
                    ->columns(2)
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('error_datetime')
                            ->label('Data do Erro')
                            ->dateTime('d/m/Y H:i'),
                        \Filament\Infolists\Components\TextEntry::make('opened_at')
                            ->label('Aberto em')
                            ->dateTime('d/m/Y H:i'),
                        \Filament\Infolists\Components\TextEntry::make('estimated_completion_at')
                            ->label('Previsão')
                            ->dateTime('d/m/Y H:i'),
                        \Filament\Infolists\Components\TextEntry::make('completed_at')
                            ->label('Concluído em')
                            ->dateTime('d/m/Y H:i'),
                    ]),
                 \Filament\Schemas\Components\Section::make('Métricas')
                    ->columns(3)
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('total_interactions')
                            ->label('Total Interações'),
                        \Filament\Infolists\Components\TextEntry::make('error_interactions')
                            ->label('Erros'),
                        \Filament\Infolists\Components\TextEntry::make('ai_accuracy_rate')
                            ->label('Assertividade IA')
                            ->suffix('%'),
                    ]),
            ]);
    }
}
