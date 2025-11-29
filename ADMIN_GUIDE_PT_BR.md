# 📖 GUIA DE USO - PAINEL ADMINISTRATIVO

## 🔐 Acesso ao Painel

Para acessar o painel administrativo, você precisa estar logado com uma conta de **Admin**.

- URL Dashboard: `http://localhost:8000/admin/dashboard`
- URL Usuários: `http://localhost:8000/admin/users`
- URL Logs: `http://localhost:8000/admin/audit-logs`
- URL Configurações: `http://localhost:8000/admin/settings`

---

## 📊 1. DASHBOARD

### Funcionalidade
Visão geral do sistema com estatísticas importantes.

### Métricas Exibidas
- **Total de Usuários**: Mostra total + ativos + inativos
- **Administradores**: Quantidade de admins ativos
- **Usuários Regulares**: Quantidade de usuários comuns ativos
- **Restaurantes**: Total de restaurantes cadastrados
- **Pedidos Hoje**: Quantidade de pedidos criados hoje

### Tabelas
- **Usuários Recentes**: 5 últimos usuários criados
- **Usuários por Restaurante**: Distribuição de usuários

---

## 👥 2. GERENCIAMENTO DE USUÁRIOS

### Funcionalidades Principais

#### Criar Novo Usuário
1. Clique em "+ Novo Usuário"
2. Preencha os campos:
   - **Nome**: Nome completo
   - **Email**: Email único
   - **Senha**: Mínimo 8 caracteres (com confirmação)
   - **Restaurante**: Selecione o restaurante
   - **Papel**: Admin ou Usuário
3. Clique em "Salvar"

#### Editar Usuário
1. Clique no botão "✏️ Editar" na tabela
2. Modifique os dados desejados
3. Opcionalmente altere a senha (deixe em branco para manter)
4. Clique em "Atualizar"

#### Desativar Usuário
1. Clique em "🔒 Desativar" na linha do usuário
2. Confirme a ação
3. O usuário aparecerá com badge "Desativado"

#### Reativar Usuário
1. Localize o usuário desativado (aparece com fundo cinza)
2. Clique em "✅ Reativar"
3. Confirme a ação

#### Deletar Permanentemente
1. Localize o usuário desativado
2. Clique em "🗑️ Deletar"
3. Confirme (AVISO: Não pode ser desfeito!)
4. O usuário será removido completamente

### Filtros Disponíveis

**Buscar por:** Nome ou Email
```
Digite "João" ou "joao@email.com"
```

**Filtro por Restaurante:** Selecione qual restaurante
```
Mostra apenas usuários daquele restaurante
```

**Filtro por Papel:** Admin ou Usuário
```
Filtra por tipo de acesso
```

**Filtro por Status:** Ativo ou Inativo
```
Ativo = usuários que podem acessar
Inativo = usuários desativados
```

**Limpar Filtros:** Link "✕ Limpar filtros"
```
Volta a exibição normal de todos os usuários
```

---

## 📋 3. LOGS DE AUDITORIA

### O que é Auditoria?
Registro de todas as ações realizadas no painel administrativo.

### Informações Exibidas
- **Data/Hora**: Exato momento da ação
- **Usuário**: Quem realizou a ação
- **Ação**: Tipo (Criação, Atualização, Desativação, etc)
- **Modelo**: Qual objeto foi afetado (User, Restaurante, etc)
- **ID**: ID do objeto afetado
- **IP**: Endereço IP da pessoa que fez a ação
- **Mudanças**: Detalhes do que foi alterado

### Tipos de Ação
- **Criação**: Novo item criado
- **Atualização**: Item modificado
- **Desativação**: Item soft deleted
- **Reativação**: Item restaurado
- **Deleção Perm.**: Item permanentemente deletado
- **Login**: Usuário entrou no sistema
- **Logout**: Usuário saiu do sistema

### Filtros
- **Por Ação**: Veja apenas um tipo de ação
- **Por Modelo**: Filtre por tipo de objeto
- **Por Usuário**: Veja ações de uma pessoa específica
- **Por Data**: Selecione data inicial

### Visualizar Detalhes
Clique em "Ver detalhes" para expandir as mudanças específicas.

---

## ⚙️ 4. CONFIGURAÇÕES DO SISTEMA

### Informações Gerais

#### Nome da Aplicação
```
Nome que aparece em emails e título do navegador
Exemplo: "StockOne"
```

#### Email de Contato
```
Email usado para comunicações do sistema
Exemplo: "admin@stockone.com"
```

#### Máximo de Usuários
```
Limite de usuários permitidos
Deixe "Ilimitado" para sem restrição
```

#### Itens por Página
```
Quantidade de registros por página nas tabelas
Valores: 5 a 100 (recomendado: 15)
```

### Segurança

#### Permitir Registro de Novos Usuários
```
☐ Desativado: Apenas admin pode criar usuários
☑ Ativado: Usuários podem se autorregistrar (cuidado!)
```

#### Ativar Modo de Manutenção
```
☐ Normal: Sistema funcionando normalmente
☑ Manutenção: Apenas admin pode acessar
(Útil para manutenções importantes)
```

### Retenção de Dados

#### Reter Logs de Auditoria
```
Número de dias para manter registros
Após esse período, logs são automaticamente removidos
Valores: 7 a 365 dias
Exemplo: 90 dias = ~3 meses de histórico
```

---

## 🔍 DICAS E BOAS PRÁTICAS

### Segurança
1. ✅ Sempre crie senhas fortes (min 8 caracteres)
2. ✅ Revise regularmente quem tem acesso Admin
3. ✅ Verifique os logs de auditoria periodicamente
4. ✅ Use modo manutenção antes de atualizações importantes

### Manutenção
1. ✅ Exporte dados importante regularmente
2. ✅ Limpe logs antigos ajustando retenção
3. ✅ Desative em vez de deletar permanentemente
4. ✅ Aproveite o dashboard para monitorar

### Performance
1. ✅ Use filtros para encontrar dados rapidamente
2. ✅ A paginação mostra 15 itens por página
3. ✅ Logs são indexados por data e ação (rápido)

---

## 🆘 SOLUÇÃO DE PROBLEMAS

### "Acesso Negado" ao tentar acessar painel
**Solução:** Você precisa estar logado com uma conta Admin

### Usuário não aparece nos filtros
**Solução:** 
- Verifique o status (ativo/inativo)
- Verifique o restaurante atribuído
- Limpe os filtros e tente novamente

### Não consigo deletar um usuário
**Solução:** 
- Primeiro desative o usuário (🔒 Desativar)
- Depois clique em 🗑️ Deletar para remover permanentemente

### Configurações não salvaram
**Solução:**
- Verifique se todos os campos obrigatórios estão preenchidos
- Veja se há erros de validação em vermelho
- Tente novamente

### Logs estão vazios
**Solução:**
- Logs só aparecem após ações (criar, editar, deletar usuários)
- Verifique os filtros (talvez estejam muito restritivos)
- Limpe filtros clicando em "Limpar filtros"

---

## 📞 SUPORTE

Para problemas ou sugestões:
- Verifique o arquivo ADMIN_PANEL_SUMMARY.md para mais detalhes técnicos
- Consulte os logs de auditoria para rastrear problemas
- Contato: admin@stockone.com

---

## ✅ CHECKLIST DE PRIMEIROS PASSOS

- [ ] Acessar o dashboard e revisar estatísticas
- [ ] Testar filtros de usuários
- [ ] Criar um novo usuário teste
- [ ] Editar um usuário existente
- [ ] Ver logs de auditoria das ações
- [ ] Revisar e ajustar configurações do sistema
- [ ] Desativar um usuário e depois reativar

