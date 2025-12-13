# Manual StockOne

Manual interativo do sistema StockOne desenvolvido em React.

## Como Usar

### Opção 1: Usando npm (Recomendado)

```bash
npm start
```

Isso iniciará um servidor HTTP na porta 8080. Acesse: `http://localhost:8080`

### Opção 2: Servidor Local Manual

Para garantir que o JSX seja processado corretamente, use um servidor HTTP local:

```bash
# Usando Python 3
python3 -m http.server 8080

# Ou usando PHP
php -S localhost:8080

# Ou usando Node.js (http-server)
npx http-server -p 8080
```

Depois acesse: `http://localhost:8080`

### Opção 3: Abrir Diretamente

Você pode tentar abrir o `index.html` diretamente no navegador, mas algumas funcionalidades podem não funcionar devido a restrições CORS.

### Navegação

1. Abra o manual no navegador
2. Use o menu lateral para navegar entre as seções
3. O conteúdo será exibido na área principal

## Estrutura

- `index.html` - Arquivo principal HTML
- `app.jsx` - Aplicação React com todos os componentes
- `styles.css` - Estilos CSS com paleta de cores do projeto
- `README.md` - Este arquivo

## Características

- Menu lateral fixo para navegação
- Paleta de cores do projeto (vermelho, cinza, azul, verde)
- Design responsivo
- Sem dependências externas (usa React via CDN)
- Não interfere com o sistema principal

## Navegação

O manual está organizado em três módulos principais:

### 1. Módulo Público (Sem Autenticação)
- Login e Autenticação
- Menu Público
- Carrinho de Compras
- Finalizar Pedido (Checkout)

### 2. Painel Administrativo (Admin)
- Dashboard Administrativo
- Gestão de Usuários
- Gestão de Restaurantes
- Estoque Inteligente
- Logs de Auditoria
- Relatórios
- Configurações
- Funcionários
- Mesas e Reservas
- Pedidos de Delivery
- Controle de Desperdícios
- Gestão de Cardápio
- Notificações
- Busca Global

### 3. Módulo Restaurante (Usuário Autenticado)
- Dashboard do Restaurante
- Gestão de Insumos
- Gestão de Cardápio
- Gestão de Pedidos
- Gestão de Estoque
- Gestão de Alertas
- Compras e Sugestões
- Gestão de Receitas
- Fila de Produção

## Paleta de Cores

O manual utiliza a mesma paleta de cores do sistema:

- **Vermelho Primário:** #dc2626 (red-600)
- **Vermelho Escuro:** #b91c1c (red-700)
- **Azul:** #2563eb (blue-600)
- **Verde:** #16a34a (green-600)
- **Amarelo:** #f59e0b (amber-500)
- **Cinza Escuro:** #1f2937 (gray-800)
- **Cinza Médio:** #374151 (gray-700)

