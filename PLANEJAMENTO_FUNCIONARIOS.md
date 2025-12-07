# Planejamento: Gestão de Funcionários

## Funcionalidades principais

1. Cadastro, edição e exclusão de funcionários
   - Nome, e-mail, telefone, cargo, status (ativo/inativo), restaurante associado
2. Perfis e permissões
   - Tipos: Administrador, Gerente, Atendente, Cozinha
   - Permissões: acesso a módulos conforme perfil
3. Controle de acesso
   - Login individual
   - Logs de acesso e ações
4. Status e escalas
   - Marcação de ativo/inativo
   - Registro de escalas/horários
5. Histórico de atividades
   - Auditoria de ações importantes (pedidos, estoque, etc)
6. Relatórios
   - Desempenho, presença, produtividade
7. Integração com folha de pagamento (opcional)
   - Exportação de dados para RH/contabilidade

## Estrutura sugerida
- Modelos: Funcionario, Cargo, Permissao, Escala, LogFuncionario
- Telas: Listagem, cadastro/edição, perfil, relatórios
- Rotas protegidas por middleware de permissão

## Observações
- Aproveitar o modelo User já existente, estendendo para funcionários
- Utilizar policies para controle de acesso
- Registrar logs de ações sensíveis

---
Esse escopo pode ser expandido conforme necessidade do negócio.
