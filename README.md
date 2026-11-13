# Sistema de Automação de Rotas de Atendimento

Sistema web e CLI em PHP + MySQL para controle recorrente de rotas de clientes com ajuste automático de dias úteis (finais de semana e meses curtos).

## Funcionalidades
- Cadastro de clientes com **dia fixo mensal** (1 a 31).
- Regra flexível para fins de semana (`PROXIMA_SEGUNDA`, `SEXTA_ANTERIOR` ou `MANTER`).
- Geração automática de agenda mensal sem duplicidade (`UNIQUE KEY` por cliente/mês).
- Painel diário para acompanhamento e baixa de atendimentos (`Pendente` / `Concluído`).
- Script CLI (`cron/disparar_alertas.php`) para envio de lembretes diários.

## Estrutura de Pastas
- `config/` — Conexão PDO e carregamento de variáveis de ambiente.
- `controllers/` — Regras de negócio (`ClienteController` e `RotaController`).
- `includes/` — Funções auxiliares de data e componentes HTML.
- `public/` — Arquivos acessíveis pelo servidor web (`index.php`, `clientes.php`, `assets/`).
- `cron/` — Rotinas agendadas via linha de comando.

## Como Configurar e Rodar

1. **Banco de Dados:** Crie um banco chamado `sistema_rotas` no MySQL e execute o script SQL com as tabelas `clientes` e `atendimentos`.
2. **Ambiente:** Copie ou crie o arquivo `.env` na raiz com as credenciais do seu MySQL:
   ```env
   DB_HOST=127.0.0.1
   DB_NAME=sistema_rotas
   DB_USER=root
   DB_PASS=
   DB_CHARSET=utf8mb4