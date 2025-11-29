# 🗺️ MAPA DE FUNCIONALIDADES DO PAINEL ADMINISTRATIVO

```
PAINEL ADMINISTRATIVO STOCKONE
│
├─ 📊 DASHBOARD (admin/dashboard)
│  ├─ 5 Cards de Estatísticas
│  │  ├─ Total de Usuários (com breakdown ativo/inativo)
│  │  ├─ Administradores
│  │  ├─ Usuários Regulares
│  │  ├─ Restaurantes
│  │  └─ Pedidos Hoje
│  ├─ Tabela: Usuários Recentes
│  └─ Gráfico: Usuários por Restaurante
│
├─ 👥 GERENCIAMENTO DE USUÁRIOS (admin/users)
│  ├─ LISTAR
│  │  ├─ Tabela com todos os usuários (ativo + inativos)
│  │  ├─ 4 Filtros + Busca
│  │  │  ├─ Buscar por nome/email
│  │  │  ├─ Filtro por restaurante
│  │  │  ├─ Filtro por papel (Admin/User)
│  │  │  └─ Filtro por status (Ativo/Inativo)
│  │  ├─ Paginação (15 por página)
│  │  └─ Ações por linha:
│  │     ├─ Ativo: ✏️ Editar + 🔒 Desativar
│  │     └─ Inativo: ✅ Reativar + 🗑️ Deletar
│  ├─ CRIAR
│  │  └─ Formulário com validação
│  │     ├─ Nome (obrigatório)
│  │     ├─ Email (único, obrigatório)
│  │     ├─ Senha (min 8 chars, confirmação)
│  │     ├─ Restaurante (select)
│  │     └─ Papel (Admin/User)
│  ├─ EDITAR
│  │  └─ Formulário pré-preenchido
│  │     ├─ Todos os campos acima
│  │     └─ Senha (opcional)
│  ├─ SOFT DELETE (desativar)
│  │  ├─ Marca como deleted_at no DB
│  │  ├─ Usuário não pode mais logar
│  │  └─ Dados preservados para auditoria
│  ├─ RESTORE (reativar)
│  │  ├─ Remove marca de deleted_at
│  │  └─ Usuário pode logar novamente
│  └─ FORCE DELETE (deletar permanentemente)
│     ├─ Remove registro completamente
│     ├─ AÇÃO IRREVERSÍVEL
│     └─ Registrado na auditoria
│
├─ 📋 LOGS DE AUDITORIA (admin/audit-logs)
│  ├─ TABELA DE LOGS
│  │  ├─ Data/Hora (down to second)
│  │  ├─ Usuário (quem fez)
│  │  ├─ Ação (create/update/delete/restore/force_delete/login/logout)
│  │  ├─ Modelo (User/Restaurante/Setting)
│  │  ├─ ID do Objeto
│  │  ├─ IP Address
│  │  └─ Mudanças (expandível com detalhes JSON)
│  └─ FILTROS
│     ├─ Por ação (7 tipos)
│     ├─ Por modelo
│     ├─ Por usuário
│     └─ Por data
│
├─ ⚙️ CONFIGURAÇÕES (admin/settings)
│  ├─ INFORMAÇÕES GERAIS
│  │  ├─ Nome da Aplicação
│  │  ├─ Email de Contato
│  │  ├─ Máximo de Usuários
│  │  └─ Itens por Página (5-100)
│  ├─ SEGURANÇA
│  │  ├─ Permitir Registro de Usuários (checkbox)
│  │  └─ Modo de Manutenção (checkbox)
│  └─ RETENÇÃO DE DADOS
│     └─ Dias para manter logs (7-365)
│
└─ 🏪 RESTAURANTES (admin/restaurantes)
   ├─ CRUD Completo
   ├─ Listar
   ├─ Criar
   ├─ Editar
   └─ Deletar
```

---

## 🔄 FLUXO DE DADOS

```
┌─────────────────────────────────────────────────────────────┐
│                        USUÁRIO (Admin)                       │
└────────────────────────┬────────────────────────────────────┘
                         │
                    ┌────▼─────┐
                    │  Acessa  │
                    │   URL    │
                    └────┬─────┘
                         │
        ┌────────────────┼────────────────┐
        │                │                │
   Dashboard         Users List       Audit Logs
        │                │                │
        │          ┌──────▼──────┐        │
        │          │ UserController        │
        │          ├─ index()   │         │
        │          │ create()   │         │
        │          │ store()    │         │
        │          │ edit()     │         │
        │          │ update()   │         │
        │          │ destroy()  │         │
        │          │ restore()  │         │
        │          │ forceDelete()        │
        │          └──────┬──────┘        │
        │                 │               │
        │          ┌──────▼──────────┐    │
        │          │ AuditLog::log() │    │
        │          │ (Auto registra) │    │
        │          └──────┬──────────┘    │
        │                 │               │
        │          ┌──────▼──────┐        │
        │          │   Database  │        │
        │          │  Users      │        │
        │          │  AuditLogs  │        │
        │          │  Settings   │        │
        │          └─────────────┘        │
        │                                 │
        └────────────────┬────────────────┘
                         │
                    ┌────▼─────┐
                    │  View     │
                    │ Rendered  │
                    └──────────┘
```

---

## 📊 BANCO DE DADOS

```
DATABASE TABLES
│
├─ users (existente + alterado)
│  ├─ id
│  ├─ name
│  ├─ email
│  ├─ password
│  ├─ restaurante_id (FK)
│  ├─ role (admin/user)
│  ├─ deleted_at (NEW - Soft Delete)
│  ├─ remember_token
│  ├─ created_at
│  └─ updated_at
│
├─ audit_logs (NEW)
│  ├─ id
│  ├─ user_id (FK - quem fez)
│  ├─ action (string - tipo de ação)
│  ├─ model (string - User/Restaurante)
│  ├─ model_id (int - ID do objeto)
│  ├─ changes (JSON - antes/depois)
│  ├─ ip_address (string)
│  ├─ user_agent (string)
│  ├─ created_at
│  └─ updated_at
│
└─ settings (NEW)
   ├─ id
   ├─ key (unique)
   ├─ value (text)
   ├─ type (string/boolean/integer/array)
   ├─ description
   ├─ created_at
   └─ updated_at
```

---

## 🎯 RELACIONAMENTOS

```
User (Model)
├─ hasMany(AuditLog)
└─ belongsTo(Restaurante)

AuditLog (Model)
└─ belongsTo(User)

Setting (Model)
└─ Sem relacionamentos (Key-Value)

Restaurante (Model)
├─ hasMany(User)
└─ (presumido - já existia)
```

---

## 🔐 AUTENTICAÇÃO E AUTORIZAÇÃO

```
Todos os endpoints (/admin/*) requerem:
├─ Usuário autenticado (middleware 'auth')
└─ Usuário com role = 'admin' (checkAdmin() no controller)

Erros:
├─ 403 (Forbidden): Não é admin
├─ 401 (Unauthorized): Não está logado
└─ 404 (Not Found): Usuário/recurso não existe
```

---

## 📈 FUNCIONALIDADES AVANÇADAS

```
SOFT DELETE CHAIN
├─ User::withTrashed() - inclui inativos
├─ User::whereNull('deleted_at') - apenas ativos
└─ User::whereNotNull('deleted_at') - apenas inativos

AUDITORIA AUTOMÁTICA
├─ AuditLog::log() chamado automaticamente
├─ Captura IP address
├─ Captura user agent
└─ Rastreia antes/depois de mudanças

CONFIGURAÇÕES DINÂMICAS
├─ Setting::get('key') - recupera valor
└─ Setting::set('key', 'value') - salva valor
```

---

## 🚀 PERFORMANCE

```
Queries Otimizadas:
├─ Índices em audit_logs (action, model, created_at)
├─ Foreign keys com constraints
├─ Eager loading com ->with('relacionamento')
└─ Paginação reduz data transfer

Cache:
├─ Config cache ✅ (php artisan config:cache)
└─ Route cache (opcional)
```

---

## 📦 ESTRUTURA DE ARQUIVOS

```
app/
├─ Http/Controllers/
│  ├─ UserAdminController.php (MODIFICADO)
│  ├─ DashboardAdminController.php (NEW)
│  ├─ AuditLogController.php (NEW)
│  └─ SettingsController.php (NEW)
│
└─ Models/
   ├─ User.php (MODIFICADO - Adicionado SoftDeletes)
   ├─ AuditLog.php (NEW)
   └─ Setting.php (NEW)

database/
└─ migrations/
   ├─ 2025_11_27_134329_add_soft_delete_to_users_table.php (NEW)
   ├─ 2025_11_27_134615_create_audit_logs_table.php (NEW)
   └─ 2025_11_27_134820_create_settings_table.php (NEW)

resources/views/admin/
├─ users/
│  └─ index.blade.php (MODIFICADO)
├─ dashboard.blade.php (NEW)
├─ audit-logs.blade.php (NEW)
└─ settings.blade.php (NEW)

routes/
└─ web.php (MODIFICADO - Novas rotas admin)
```

