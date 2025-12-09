# 🛠️ Tecnologias Utilizadas no StockOne

**Última atualização:** Dezembro 2025  
**Versão do Projeto:** Laravel 12

---

## 📋 Índice

- [Backend](#backend)
- [Frontend](#frontend)
- [Banco de Dados](#banco-de-dados)
- [Ferramentas de Build](#ferramentas-de-build)
- [Containerização](#containerização)
- [Testes](#testes)
- [Ferramentas de Desenvolvimento](#ferramentas-de-desenvolvimento)
- [Bibliotecas JavaScript](#bibliotecas-javascript)
- [Serviços e Infraestrutura](#serviços-e-infraestrutura)

---

## 🔧 Backend

### Framework Principal
- **Laravel 12.0** - Framework PHP moderno e robusto
  - ORM Eloquent
  - Sistema de Rotas
  - Middleware
  - Validação de Dados
  - Sistema de Cache
  - Sistema de Fila (Queue)
  - Sistema de Sessão
  - Sistema de Autenticação

### Linguagem
- **PHP 8.2+** - Linguagem de programação backend
  - Type Hints
  - Attributes
  - Enums
  - Match Expressions

### Painel Administrativo
- **Filament 4.1** - Painel administrativo elegante e moderno
  - Resource Management
  - Forms e Tables
  - Widgets
  - Notifications

### Extensões PHP
- **PDO MySQL** - Driver de banco de dados
- **GD Library** - Manipulação de imagens
- **MBString** - Manipulação de strings multibyte
- **OpenSSL** - Criptografia e segurança

---

## 🎨 Frontend

### Framework CSS
- **TailwindCSS 3.4.18** - Framework CSS utility-first
  - Dark Mode (class-based)
  - Responsive Design
  - Custom Theme Configuration

### Framework JavaScript
- **Alpine.js 3.x** - Framework JavaScript reativo e leve
  - CDN Integration
  - Reactive Data
  - Event Handling
  - Component State Management
  - Plugins: `@alpinejs/mask` para máscaras de input

### Templates
- **Blade Templates** - Engine de templates do Laravel
  - Componentes
  - Diretivas customizadas
  - Layouts e Sections
  - Partials

### Bibliotecas de Gráficos
- **ApexCharts** (via CDN) - Biblioteca de gráficos interativos
  - Gráficos de linha
  - Gráficos de pizza
  - Sparklines
  - Gráficos de receita

- **Chart.js 4.4.0** (via CDN) - Biblioteca de gráficos
  - Gráficos de atividade
  - Distribuição de dados
  - Gráficos de ocupação

### HTTP Client
- **Axios 1.11.0** - Cliente HTTP para requisições AJAX

---

## 🗄️ Banco de Dados

### SGBD Suportados
- **MySQL 8.0** - Banco de dados relacional (produção)
- **SQLite** - Banco de dados para desenvolvimento/testes
- **PostgreSQL 13+** - Suporte alternativo (configurável)

### Características
- Migrations do Laravel
- Seeders para dados iniciais
- Factories para testes
- Soft Deletes
- Timestamps automáticos
- Relacionamentos Eloquent

---

## ⚙️ Ferramentas de Build

### Build Tool
- **Vite 7.0.7** - Build tool moderna e rápida
  - Hot Module Replacement (HMR)
  - Code Splitting
  - Asset Optimization

### Plugins Vite
- **Laravel Vite Plugin 2.0.0** - Integração Laravel + Vite
- **TailwindCSS Vite Plugin 4.0.0** - Integração Tailwind + Vite

### Processadores CSS
- **PostCSS 8.5.6** - Processador CSS
- **Autoprefixer 10.4.22** - Adiciona vendor prefixes automaticamente

### Gerenciadores de Dependências
- **Composer 2.6+** - Gerenciador de dependências PHP
- **NPM 9.x+** - Gerenciador de dependências JavaScript
- **Node.js 18.x+** - Runtime JavaScript

---

## 🐳 Containerização

### Docker
- **Docker** - Containerização da aplicação
- **Docker Compose 3.8** - Orquestração de containers

### Containers Utilizados
- **PHP 8.2-FPM** - Container principal da aplicação
- **MySQL 8.0** - Container do banco de dados
- **Redis 7.2** - Container para cache e filas
- **MailHog** - Container para testes de email
- **Adminer** - Interface web para gerenciamento do banco

### Ferramentas Docker
- **Composer 2.6** - Instalado no container PHP
- **Wait-for-it.sh** - Script para aguardar serviços

---

## 🧪 Testes

### Framework de Testes
- **PHPUnit 11.5.3** - Framework de testes PHP
  - Testes Unitários
  - Testes de Feature
  - Testes de Integração

### Ferramentas de Teste
- **Mockery 1.6** - Framework de mocking
- **FakerPHP 1.23** - Geração de dados fake para testes

### Configuração de Testes
- Ambiente de testes isolado
- SQLite em memória para testes
- Cache e sessão em array
- Mail driver em array

---

## 🛠️ Ferramentas de Desenvolvimento

### Code Quality
- **Laravel Pint 1.24** - Code style fixer automático
  - PSR-12 Code Style
  - Auto-fix de formatação

### Debugging e Logging
- **Laravel Pail 1.2.2** - Visualizador de logs em tempo real
- **Laravel Tinker 2.10.1** - REPL interativo para Laravel

### Desenvolvimento Local
- **Laravel Sail 1.41** - Ambiente Docker para desenvolvimento
- **Concurrently 9.0.1** - Execução paralela de comandos
  - Servidor PHP
  - Queue Worker
  - Logs (Pail)
  - Vite Dev Server

### Utilitários
- **Collision 8.6** - Error handler melhorado para CLI

---

## 📦 Bibliotecas JavaScript (CDN)

### Gráficos e Visualizações
- **ApexCharts** - Gráficos interativos avançados
- **Chart.js 4.4.0** - Gráficos simples e responsivos

### JavaScript Reativo
- **Alpine.js 3.x** - Framework JavaScript minimalista
- **@alpinejs/mask 3.x** - Plugin de máscaras para inputs

---

## 🏗️ Serviços e Infraestrutura

### Cache e Sessão
- **Redis 7.2** - Cache e gerenciamento de sessões
- **File System** - Cache alternativo via arquivos

### Filas (Queue)
- **Database Queue** - Sistema de filas usando banco de dados
- **Redis Queue** - Sistema de filas usando Redis (opcional)

### Email
- **SMTP** - Configurável via .env
- **MailHog** - Servidor de email para desenvolvimento

### Armazenamento
- **Local Storage** - Armazenamento de arquivos local
- **Public Disk** - Arquivos públicos
- **Private Disk** - Arquivos privados

---

## 📊 Arquitetura e Padrões

### Padrões de Design
- **MVC (Model-View-Controller)**
- **Repository Pattern** (parcial)
- **Observer Pattern** - Laravel Observers
- **Service Pattern** - Services para lógica de negócio

### Estrutura de Código
- **PSR-4 Autoloading**
- **Namespaces**
- **Type Hints**
- **Return Types**

### Segurança
- **CSRF Protection**
- **XSS Protection**
- **SQL Injection Protection** (via Eloquent)
- **Password Hashing** (bcrypt)
- **Authentication Middleware**
- **Authorization Gates/Policies**

---

## 🔐 Middleware Customizado

- **IsAdmin** - Verificação de permissões de administrador
- **EnsureRestauranteSession** - Garantia de sessão de restaurante
- **RecordUserLogin** - Registro automático de login

---

## 📈 Observers Implementados

- **CompraSugestaoObserver** - Observa mudanças em sugestões de compra
- **DesperdicioObserver** - Observa registros de desperdício
- **EstoqueObserver** - Observa movimentações de estoque
- **FilaProducaoObserver** - Observa fila de produção
- **InsumoObserver** - Observa mudanças em insumos
- **PedidoItemObserver** - Observa itens de pedidos
- **PedidoObserver** - Observa mudanças em pedidos

---

## 🎯 Funcionalidades Especiais

### Sistema de Health Score
- Cálculo automático de pontuação (0-100)
- Histórico de scores
- Análise de ciclo de vida
- Ações recomendadas

### Sistema de Auditoria
- Logs completos de ações
- Rastreamento de IP
- Histórico de mudanças
- Filtros avançados

### Sistema de Notificações
- Notificações em tempo real
- Prioridades
- Marcação de lidas/não lidas
- Filtros por tipo

---

## 📝 Resumo de Versões

| Tecnologia | Versão | Tipo |
|------------|--------|------|
| Laravel | 12.0 | Framework |
| PHP | 8.2+ | Linguagem |
| Filament | 4.1 | Admin Panel |
| TailwindCSS | 3.4.18 | CSS Framework |
| Alpine.js | 3.x | JS Framework |
| Vite | 7.0.7 | Build Tool |
| Node.js | 18.x+ | Runtime |
| MySQL | 8.0 | Database |
| Redis | 7.2 | Cache/Queue |
| PHPUnit | 11.5.3 | Testing |
| Chart.js | 4.4.0 | Charts |
| Docker | Latest | Containerization |

---

## 🚀 Scripts Disponíveis

### Composer
```bash
composer setup      # Instalação completa
composer dev        # Ambiente de desenvolvimento
composer test       # Executar testes
```

### NPM
```bash
npm run dev         # Servidor de desenvolvimento Vite
npm run build       # Build de produção
```

### Artisan
```bash
php artisan serve           # Servidor de desenvolvimento
php artisan migrate         # Executar migrations
php artisan queue:work      # Worker de filas
php artisan pail            # Visualizador de logs
```

---

## 📚 Dependências Principais

### Produção (require)
- `laravel/framework ^12.0`
- `filament/filament ^4.1`
- `laravel/tinker ^2.10.1`

### Desenvolvimento (require-dev)
- `fakerphp/faker ^1.23`
- `laravel/pail ^1.2.2`
- `laravel/pint ^1.24`
- `laravel/sail ^1.41`
- `mockery/mockery ^1.6`
- `nunomaduro/collision ^8.6`
- `phpunit/phpunit ^11.5.3`

### Frontend (devDependencies)
- `@tailwindcss/vite ^4.0.0`
- `autoprefixer ^10.4.22`
- `axios ^1.11.0`
- `concurrently ^9.0.1`
- `laravel-vite-plugin ^2.0.0`
- `postcss ^8.5.6`
- `tailwindcss ^3.4.18`
- `vite ^7.0.7`

---

## ✅ Status das Tecnologias

- ✅ **Backend:** Laravel 12 (estável)
- ✅ **Frontend:** TailwindCSS + Alpine.js (estável)
- ✅ **Admin Panel:** Filament 4.1 (estável)
- ✅ **Build:** Vite 7 (estável)
- ✅ **Database:** MySQL 8.0 / SQLite (estável)
- ✅ **Containerização:** Docker + Docker Compose (estável)
- ✅ **Testes:** PHPUnit 11 (estável)
- ✅ **Code Quality:** Laravel Pint (estável)

---

**Documento gerado automaticamente baseado nos arquivos de configuração do projeto.**

