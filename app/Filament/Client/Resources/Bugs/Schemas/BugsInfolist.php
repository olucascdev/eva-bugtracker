<?php

namespace App\Filament\Client\Resources\Bugs\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class BugsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalhes do Bug')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('title')
                            ->label('Título')
                            ->weight('bold')
                            ->columnSpanFull(),
                        TextEntry::make('description')
                            ->label('Descrição Detalhada')
                            ->html()
                            ->columnSpanFull(),
                        TextEntry::make('expected_behavior')
                            ->label('Comportamento Esperado')
                            ->columnSpanFull(),
                        TextEntry::make('conversation_link')
                            ->label('Link da Conversa')
                            ->url(fn ($state) => $state, true)
                            ->openUrlInNewTab(),
                    ]),
                Section::make('Status e Acompanhamento')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('status.name')
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
                        TextEntry::make('priority.name')
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
                        TextEntry::make('assignedTo.name')
                            ->label('Responsável Técnico')
                            ->placeholder('Ainda não atribuído'),
                        TextEntry::make('reportedBy.name')
                            ->label('Reportado por'),
                    ]),
                 Section::make('Datas e Prazos')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('opened_at')
                            ->label('Aberto em')
                            ->dateTime('d/m/Y H:i'),
                        TextEntry::make('estimated_completion_at')
                            ->label('Previsão')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('Em análise'),
                        TextEntry::make('completed_at')
                            ->label('Concluído em')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
