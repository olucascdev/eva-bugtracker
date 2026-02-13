# Enums - Bug Tracker EVA

Documentação dos Enums criados para Status e Prioridades.

---

## BugStatusEnum

**Path**: `app/Enums/BugStatusEnum.php`

### Cases

```php
BugStatusEnum::REPORTADO           // 'reportado'
BugStatusEnum::EM_ANALISE          // 'em-analise'
BugStatusEnum::EM_DESENVOLVIMENTO  // 'em-desenvolvimento'
BugStatusEnum::AGUARDANDO_TESTE    // 'aguardando-teste'
BugStatusEnum::RESOLVIDO           // 'resolvido'
BugStatusEnum::FECHADO             // 'fechado'
```

### Métodos

#### `label(): string`

Retorna o nome legível do status.

```php
BugStatusEnum::REPORTADO->label(); // "Reportado"
BugStatusEnum::EM_ANALISE->label(); // "Em Análise"
```

#### `color(): string`

Retorna a cor hexadecimal do status.

```php
BugStatusEnum::REPORTADO->color(); // "#EF4444" (vermelho)
BugStatusEnum::RESOLVIDO->color(); // "#10B981" (verde)
```

#### `order(): int`

Retorna a ordem de exibição (1-6).

```php
BugStatusEnum::REPORTADO->order(); // 1
BugStatusEnum::FECHADO->order(); // 6
```

#### `isDefault(): bool`

Verifica se é o status padrão.

```php
BugStatusEnum::REPORTADO->isDefault(); // true
BugStatusEnum::EM_ANALISE->isDefault(); // false
```

#### `isCompleted(): bool`

Verifica se o status indica conclusão.

```php
BugStatusEnum::RESOLVIDO->isCompleted(); // true
BugStatusEnum::FECHADO->isCompleted(); // true
BugStatusEnum::EM_ANALISE->isCompleted(); // false
```

#### `options(): array` (static)

Retorna array para uso em selects.

```php
BugStatusEnum::options();
// [
//     'reportado' => 'Reportado',
//     'em-analise' => 'Em Análise',
//     ...
// ]
```

---

## BugPriorityEnum

**Path**: `app/Enums/BugPriorityEnum.php`

### Cases

```php
BugPriorityEnum::CRITICA  // 'critica'
BugPriorityEnum::ALTA     // 'alta'
BugPriorityEnum::MEDIA    // 'media'
BugPriorityEnum::BAIXA    // 'baixa'
BugPriorityEnum::MINIMA   // 'minima'
```

### Métodos

#### `label(): string`

Retorna o nome legível da prioridade.

```php
BugPriorityEnum::CRITICA->label(); // "Crítica"
BugPriorityEnum::MEDIA->label(); // "Média"
```

#### `color(): string`

Retorna a cor hexadecimal da prioridade.

```php
BugPriorityEnum::CRITICA->color(); // "#DC2626" (vermelho escuro)
BugPriorityEnum::BAIXA->color(); // "#10B981" (verde)
```

#### `level(): int`

Retorna o nível de prioridade (1-5).

```php
BugPriorityEnum::CRITICA->level(); // 5
BugPriorityEnum::MINIMA->level(); // 1
```

#### `isCritical(): bool`

Verifica se é prioridade crítica.

```php
BugPriorityEnum::CRITICA->isCritical(); // true
BugPriorityEnum::ALTA->isCritical(); // false
```

#### `isHighPriority(): bool`

Verifica se é prioridade alta ou crítica.

```php
BugPriorityEnum::CRITICA->isHighPriority(); // true
BugPriorityEnum::ALTA->isHighPriority(); // true
BugPriorityEnum::MEDIA->isHighPriority(); // false
```

#### `options(): array` (static)

Retorna array para uso em selects.

```php
BugPriorityEnum::options();
// [
//     'critica' => 'Crítica',
//     'alta' => 'Alta',
//     ...
// ]
```

#### `sortedByLevel(): array` (static)

Retorna cases ordenados por nível (maior → menor).

```php
BugPriorityEnum::sortedByLevel();
// [CRITICA, ALTA, MEDIA, BAIXA, MINIMA]
```

---

## Exemplos de Uso

### Em Filament Forms

```php
use App\Enums\BugStatusEnum;
use App\Enums\BugPriorityEnum;
use Filament\Forms\Components\Select;

Select::make('bug_status_id')
    ->label('Status')
    ->options(BugStatusEnum::options())
    ->required(),

Select::make('bug_priority_id')
    ->label('Prioridade')
    ->options(BugPriorityEnum::options())
    ->required(),
```

### Em Queries

```php
use App\Enums\BugStatusEnum;

// Buscar bugs resolvidos
$resolvedBugs = Bug::whereHas('status', function ($query) {
    $query->where('slug', BugStatusEnum::RESOLVIDO->value);
})->get();

// Buscar bugs com status de conclusão
$completedBugs = Bug::whereHas('status', function ($query) {
    $query->whereIn('slug', [
        BugStatusEnum::RESOLVIDO->value,
        BugStatusEnum::FECHADO->value,
    ]);
})->get();
```

### Em Blade/Livewire

```blade
@php
    $status = BugStatusEnum::from($bug->status->slug);
@endphp

<span style="color: {{ $status->color() }}">
    {{ $status->label() }}
</span>

@if($status->isCompleted())
    <span class="badge">Concluído</span>
@endif
```

### Em Controllers/Actions

```php
use App\Enums\BugStatusEnum;
use App\Enums\BugPriorityEnum;

// Criar bug com status padrão
$bug = Bug::create([
    'title' => 'Erro no login',
    'bug_status_id' => BugStatus::where('slug', BugStatusEnum::REPORTADO->value)->first()->id,
    'bug_priority_id' => BugPriority::where('slug', BugPriorityEnum::CRITICA->value)->first()->id,
    // ...
]);

// Verificar se bug está completo
if (BugStatusEnum::from($bug->status->slug)->isCompleted()) {
    // Enviar notificação
}
```

---

## Benefícios dos Enums

✅ **Type Safety**: IDE autocomplete e validação em tempo de compilação  
✅ **Centralização**: Todos os valores em um único lugar  
✅ **Métodos Helper**: Lógica reutilizável (isCompleted, isHighPriority)  
✅ **Consistência**: Cores e labels sempre sincronizados  
✅ **Refatoração Segura**: Mudanças propagam automaticamente

---

## Integração com Seeders

Os seeders agora usam os Enums como fonte única de verdade:

```php
// BugStatusSeeder.php
foreach (BugStatusEnum::cases() as $status) {
    DB::table('bug_statuses')->insert([
        'name' => $status->label(),
        'slug' => $status->value,
        'color' => $status->color(),
        'order' => $status->order(),
        'is_default' => $status->isDefault(),
    ]);
}
```

Isso garante que:

- Não há duplicação de dados
- Mudanças no Enum refletem automaticamente no seed
- Cores e labels sempre consistentes
