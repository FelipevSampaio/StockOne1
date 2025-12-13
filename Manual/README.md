# Manual StockOne

Manual interativo do sistema StockOne desenvolvido em React.

## Como Usar

### Opção 1: Servidor Local (Recomendado)

Para garantir que o JSX seja processado corretamente, é recomendado usar um servidor HTTP local:

```bash
# Usando Python 3
python3 -m http.server 8080

# Ou usando PHP
php -S localhost:8080

# Ou usando Node.js (http-server)
npx http-server -p 8080
```

Depois acesse: `http://localhost:8080`

### Opção 2: Abrir Diretamente

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

O manual está organizado nas seguintes seções:

1. **Introdução** - Visão geral e acesso ao painel
2. **Dashboard** - Visão geral do dashboard
3. **Usuários** - Gerenciamento completo de usuários
4. **Logs de Auditoria** - Sistema de auditoria
5. **Configurações** - Configurações do sistema
6. **Dicas e Boas Práticas** - Recomendações de uso
7. **Solução de Problemas** - Problemas comuns e soluções

## Paleta de Cores

O manual utiliza a mesma paleta de cores do sistema:

- **Vermelho Primário:** #dc2626 (red-600)
- **Vermelho Escuro:** #b91c1c (red-700)
- **Azul:** #2563eb (blue-600)
- **Verde:** #16a34a (green-600)
- **Amarelo:** #f59e0b (amber-500)
- **Cinza Escuro:** #1f2937 (gray-800)
- **Cinza Médio:** #374151 (gray-700)

