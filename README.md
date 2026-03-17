# BugTracker EVA

Sistema web para registro e acompanhamento de bugs, com separação entre painel interno da EVA e painel do cliente.

## O que é

O projeto centraliza o ciclo de vida de bugs reportados por clientes, permitindo abertura, triagem, acompanhamento, atribuição e análise operacional.

## Funcionalidades

- cadastro e acompanhamento de bugs;
- classificação por status e prioridade;
- atribuição de responsáveis internos;
- controle de prazo e data de conclusão;
- observações e orientações temporárias;
- dashboard com métricas e gráficos;
- ranking de principais reportadores e solucionadores;
- gestão de usuários, empresas e perfis;
- painel exclusivo para clientes;
- gestão de membros da equipe do cliente;
- segregação de dados por empresa;
- histórico de alterações dos bugs;
- redirecionamento automático por perfil após login.

## Perfis de acesso

- `admin`
- `support`
- `client-admin`
- `client-user`

## Painéis

- `/eva`: operação interna e administração;
- `/client`: acompanhamento e abertura de bugs pelo cliente.

## Entidades principais

- `Company`
- `User`
- `Role`
- `Bug`
- `BugStatus`
- `BugPriority`
- `BugLog`
- `BugAttachment`

## Stack

- PHP 8.5
- Laravel 12
- Filament 5
- Livewire 4
- PostgreSQL
- Redis
- MinIO
- Mailpit
- Tailwind CSS 4
- Vite
- Pest 4
- Laravel Sail

## Ambiente local

Serviços usados no desenvolvimento:

- aplicação Laravel;
- PostgreSQL;
- Redis;
- MinIO;
- Mailpit.

## Como rodar

```bash
cp .env.example .env
vendor/bin/sail up -d
vendor/bin/sail composer install
vendor/bin/sail artisan key:generate --no-interaction
vendor/bin/sail artisan migrate --seed --no-interaction
vendor/bin/sail npm install
vendor/bin/sail npm run dev
```

## Seed inicial

O projeto cria dados básicos para:

- funções;
- status de bug;
- prioridades;
- empresa base;
- usuário administrador.

Fallback padrão do admin:

- e-mail: `admin@admin.com`
- senha: `password`

## Estrutura resumida

```text
app/Filament        # painéis, recursos e widgets
app/Models          # entidades do domínio
app/Policies        # regras de acesso
app/Observers       # histórico automático
database/migrations # estrutura do banco
database/seeders    # carga inicial
tests/Feature       # testes principais
compose.yaml        # ambiente local com Sail
```

## Observação

O projeto já está bem estruturado para uso como bug tracker multiempresa. O principal ponto técnico visível hoje é a necessidade de revisar a configuração de anexos com MinIO para garantir alinhamento entre modelo e disco configurado.
