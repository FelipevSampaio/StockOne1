const { useState } = React;

const App = () => {
    const [activeSection, setActiveSection] = useState('introducao');

    const menuItems = [
        {
            title: 'Introdução',
            id: 'introducao',
            sections: [
                { id: 'acesso', title: 'Acesso ao Painel' },
                { id: 'introducao', title: 'Visão Geral' }
            ]
        },
        {
            title: 'Dashboard',
            id: 'dashboard',
            sections: [
                { id: 'dashboard', title: 'Visão Geral do Dashboard' }
            ]
        },
        {
            title: 'Usuários',
            id: 'usuarios',
            sections: [
                { id: 'usuarios', title: 'Gerenciamento de Usuários' },
                { id: 'criar-usuario', title: 'Criar Novo Usuário' },
                { id: 'editar-usuario', title: 'Editar Usuário' },
                { id: 'desativar-usuario', title: 'Desativar/Reativar' },
                { id: 'deletar-usuario', title: 'Deletar Usuário' },
                { id: 'filtros-usuario', title: 'Filtros e Busca' }
            ]
        },
        {
            title: 'Logs de Auditoria',
            id: 'auditoria',
            sections: [
                { id: 'auditoria', title: 'Visão Geral' },
                { id: 'tipos-acao', title: 'Tipos de Ação' },
                { id: 'filtros-auditoria', title: 'Filtros' }
            ]
        },
        {
            title: 'Configurações',
            id: 'configuracoes',
            sections: [
                { id: 'configuracoes', title: 'Visão Geral' },
                { id: 'informacoes-gerais', title: 'Informações Gerais' },
                { id: 'seguranca', title: 'Segurança' },
                { id: 'retencao-dados', title: 'Retenção de Dados' }
            ]
        },
        {
            title: 'Dicas e Boas Práticas',
            id: 'dicas',
            sections: [
                { id: 'dicas', title: 'Dicas Gerais' },
                { id: 'seguranca-dicas', title: 'Segurança' },
                { id: 'manutencao-dicas', title: 'Manutenção' },
                { id: 'performance-dicas', title: 'Performance' }
            ]
        },
        {
            title: 'Solução de Problemas',
            id: 'solucao-problemas',
            sections: [
                { id: 'solucao-problemas', title: 'Problemas Comuns' }
            ]
        }
    ];

    const renderContent = () => {
        switch(activeSection) {
            case 'introducao':
                return <Introducao />;
            case 'acesso':
                return <AcessoPainel />;
            case 'dashboard':
                return <Dashboard />;
            case 'usuarios':
                return <GerenciamentoUsuarios />;
            case 'criar-usuario':
                return <CriarUsuario />;
            case 'editar-usuario':
                return <EditarUsuario />;
            case 'desativar-usuario':
                return <DesativarUsuario />;
            case 'deletar-usuario':
                return <DeletarUsuario />;
            case 'filtros-usuario':
                return <FiltrosUsuario />;
            case 'auditoria':
                return <Auditoria />;
            case 'tipos-acao':
                return <TiposAcao />;
            case 'filtros-auditoria':
                return <FiltrosAuditoria />;
            case 'configuracoes':
                return <Configuracoes />;
            case 'informacoes-gerais':
                return <InformacoesGerais />;
            case 'seguranca':
                return <Seguranca />;
            case 'retencao-dados':
                return <RetencaoDados />;
            case 'dicas':
                return <Dicas />;
            case 'seguranca-dicas':
                return <DicasSeguranca />;
            case 'manutencao-dicas':
                return <DicasManutencao />;
            case 'performance-dicas':
                return <DicasPerformance />;
            case 'solucao-problemas':
                return <SolucaoProblemas />;
            default:
                return <Introducao />;
        }
    };

    return (
        <div className="app-container">
            <Sidebar menuItems={menuItems} activeSection={activeSection} setActiveSection={setActiveSection} />
            <div className="main-content">
                {renderContent()}
            </div>
        </div>
    );
};

const Sidebar = ({ menuItems, activeSection, setActiveSection }) => {
    const handleNavClick = (sectionId) => {
        setActiveSection(sectionId);
    };

    return (
        <div className="sidebar">
            <div className="sidebar-header">
                <h1>Manual StockOne</h1>
            </div>
            <nav className="sidebar-nav">
                {menuItems.map((item) => (
                    <div key={item.id} className="nav-section">
                        <div className="nav-section-title">{item.title}</div>
                        {item.sections.map((section) => (
                            <a
                                key={section.id}
                                className={`nav-item ${activeSection === section.id ? 'active' : ''}`}
                                onClick={() => handleNavClick(section.id)}
                            >
                                {section.title}
                            </a>
                        ))}
                    </div>
                ))}
            </nav>
        </div>
    );
};

const Introducao = () => (
    <div>
        <div className="content-header">
            <h1>Guia de Uso - Painel Administrativo</h1>
            <p>Bem-vindo ao manual completo do sistema StockOne</p>
        </div>
        <div className="content-section">
            <h2>Visão Geral</h2>
            <p>
                O StockOne é um sistema completo de gestão para restaurantes que oferece controle 
                de estoque, pedidos, produção e muito mais. Este manual fornece instruções detalhadas 
                sobre como utilizar o painel administrativo do sistema.
            </p>
            <p>
                O painel administrativo permite gerenciar usuários, visualizar logs de auditoria, 
                configurar o sistema e monitorar o desempenho através do dashboard.
            </p>
            <div className="info-card">
                <strong>Importante:</strong> Para acessar o painel administrativo, você precisa estar 
                logado com uma conta de administrador.
            </div>
        </div>
    </div>
);

const AcessoPainel = () => (
    <div>
        <div className="content-header">
            <h1>Acesso ao Painel</h1>
            <p>Como acessar as diferentes áreas do painel administrativo</p>
        </div>
        <div className="content-section">
            <h2>URLs do Sistema</h2>
            <p>Para acessar o painel administrativo, você precisa estar logado com uma conta de <strong>Admin</strong>.</p>
            <ul>
                <li><strong>Dashboard:</strong> <code>http://localhost:8000/admin/dashboard</code></li>
                <li><strong>Usuários:</strong> <code>http://localhost:8000/admin/users</code></li>
                <li><strong>Logs:</strong> <code>http://localhost:8000/admin/audit-logs</code></li>
                <li><strong>Configurações:</strong> <code>http://localhost:8000/admin/settings</code></li>
            </ul>
            <div className="warning-card">
                <strong>Atenção:</strong> Apenas usuários com permissão de administrador podem acessar essas áreas.
            </div>
        </div>
    </div>
);

const Dashboard = () => (
    <div>
        <div className="content-header">
            <h1>Dashboard</h1>
            <p>Visão geral do sistema com estatísticas importantes</p>
        </div>
        <div className="content-section">
            <h2>Funcionalidade</h2>
            <p>
                O dashboard fornece uma visão geral do sistema com estatísticas importantes e informações 
                relevantes para o administrador.
            </p>
            <h3>Métricas Exibidas</h3>
            <ul>
                <li><strong>Total de Usuários:</strong> Mostra total + ativos + inativos</li>
                <li><strong>Administradores:</strong> Quantidade de admins ativos</li>
                <li><strong>Usuários Regulares:</strong> Quantidade de usuários comuns ativos</li>
                <li><strong>Restaurantes:</strong> Total de restaurantes cadastrados</li>
                <li><strong>Pedidos Hoje:</strong> Quantidade de pedidos criados hoje</li>
            </ul>
            <h3>Tabelas</h3>
            <ul>
                <li><strong>Usuários Recentes:</strong> 5 últimos usuários criados</li>
                <li><strong>Usuários por Restaurante:</strong> Distribuição de usuários</li>
            </ul>
        </div>
    </div>
);

const GerenciamentoUsuarios = () => (
    <div>
        <div className="content-header">
            <h1>Gerenciamento de Usuários</h1>
            <p>Como gerenciar usuários do sistema</p>
        </div>
        <div className="content-section">
            <h2>Funcionalidades Principais</h2>
            <p>
                O módulo de gerenciamento de usuários permite criar, editar, desativar, reativar e 
                deletar usuários do sistema. Todas as ações são registradas nos logs de auditoria.
            </p>
            <h3>Operações Disponíveis</h3>
            <ul>
                <li>Criar novo usuário</li>
                <li>Editar usuário existente</li>
                <li>Desativar usuário (soft delete)</li>
                <li>Reativar usuário desativado</li>
                <li>Deletar permanentemente</li>
            </ul>
            <div className="info-card">
                <strong>Nota:</strong> Usuários desativados não podem fazer login, mas seus dados 
                são preservados para auditoria.
            </div>
        </div>
    </div>
);

const CriarUsuario = () => (
    <div>
        <div className="content-header">
            <h1>Criar Novo Usuário</h1>
            <p>Passo a passo para criar um novo usuário</p>
        </div>
        <div className="content-section">
            <h2>Processo de Criação</h2>
            <ol>
                <li>Clique em "+ Novo Usuário"</li>
                <li>Preencha os campos obrigatórios:
                    <ul>
                        <li><strong>Nome:</strong> Nome completo</li>
                        <li><strong>Email:</strong> Email único (não pode estar em uso)</li>
                        <li><strong>Senha:</strong> Mínimo 8 caracteres (com confirmação)</li>
                        <li><strong>Restaurante:</strong> Selecione o restaurante</li>
                        <li><strong>Papel:</strong> Admin ou Usuário</li>
                    </ul>
                </li>
                <li>Clique em "Salvar"</li>
            </ol>
            <div className="success-card">
                <strong>Dica:</strong> Certifique-se de que o email não está em uso por outro usuário.
            </div>
        </div>
    </div>
);

const EditarUsuario = () => (
    <div>
        <div className="content-header">
            <h1>Editar Usuário</h1>
            <p>Como modificar dados de um usuário existente</p>
        </div>
        <div className="content-section">
            <h2>Processo de Edição</h2>
            <ol>
                <li>Clique no botão "Editar" na tabela de usuários</li>
                <li>Modifique os dados desejados</li>
                <li>Opcionalmente altere a senha (deixe em branco para manter a atual)</li>
                <li>Clique em "Atualizar"</li>
            </ol>
            <div className="info-card">
                <strong>Importante:</strong> A alteração de senha é opcional. Se deixar o campo 
                em branco, a senha atual será mantida.
            </div>
        </div>
    </div>
);

const DesativarUsuario = () => (
    <div>
        <div className="content-header">
            <h1>Desativar e Reativar Usuário</h1>
            <p>Como desativar e reativar usuários do sistema</p>
        </div>
        <div className="content-section">
            <h2>Desativar Usuário</h2>
            <ol>
                <li>Clique em "Desativar" na linha do usuário</li>
                <li>Confirme a ação</li>
                <li>O usuário aparecerá com badge "Desativado"</li>
            </ol>
            <p>
                Usuários desativados não podem fazer login no sistema, mas seus dados são preservados 
                para fins de auditoria.
            </p>
            <h2>Reativar Usuário</h2>
            <ol>
                <li>Localize o usuário desativado (aparece com fundo cinza)</li>
                <li>Clique em "Reativar"</li>
                <li>Confirme a ação</li>
            </ol>
            <div className="success-card">
                <strong>Nota:</strong> Após reativar, o usuário poderá fazer login novamente normalmente.
            </div>
        </div>
    </div>
);

const DeletarUsuario = () => (
    <div>
        <div className="content-header">
            <h1>Deletar Usuário Permanentemente</h1>
            <p>Atenção: Esta ação é irreversível</p>
        </div>
        <div className="content-section">
            <h2>Processo de Deleção</h2>
            <ol>
                <li>Localize o usuário desativado</li>
                <li>Clique em "Deletar"</li>
                <li>Confirme a ação</li>
                <li>O usuário será removido completamente do sistema</li>
            </ol>
            <div className="danger-card">
                <strong>Atenção:</strong> Esta ação não pode ser desfeita! O usuário será removido 
                permanentemente do banco de dados. Certifique-se de que realmente deseja deletar 
                antes de confirmar.
            </div>
            <p>
                <strong>Importante:</strong> Para deletar um usuário, ele primeiro deve estar desativado. 
                Não é possível deletar usuários ativos diretamente.
            </p>
        </div>
    </div>
);

const FiltrosUsuario = () => (
    <div>
        <div className="content-header">
            <h1>Filtros e Busca de Usuários</h1>
            <p>Como encontrar usuários rapidamente</p>
        </div>
        <div className="content-section">
            <h2>Filtros Disponíveis</h2>
            <h3>Buscar por Nome ou Email</h3>
            <p>Digite o nome ou email do usuário no campo de busca.</p>
            <pre><code>Exemplo: "João" ou "joao@email.com"</code></pre>
            <h3>Filtro por Restaurante</h3>
            <p>Selecione um restaurante específico para ver apenas os usuários daquele restaurante.</p>
            <h3>Filtro por Papel</h3>
            <p>Filtre por tipo de acesso: Admin ou Usuário.</p>
            <h3>Filtro por Status</h3>
            <ul>
                <li><strong>Ativo:</strong> Usuários que podem acessar o sistema</li>
                <li><strong>Inativo:</strong> Usuários desativados</li>
            </ul>
            <h3>Limpar Filtros</h3>
            <p>Clique em "Limpar filtros" para voltar à exibição normal de todos os usuários.</p>
        </div>
    </div>
);

const Auditoria = () => (
    <div>
        <div className="content-header">
            <h1>Logs de Auditoria</h1>
            <p>Registro completo de todas as ações realizadas no sistema</p>
        </div>
        <div className="content-section">
            <h2>O que é Auditoria?</h2>
            <p>
                O sistema de auditoria registra todas as ações realizadas no painel administrativo, 
                fornecendo um histórico completo de mudanças e acessos.
            </p>
            <h3>Informações Exibidas</h3>
            <ul>
                <li><strong>Data/Hora:</strong> Exato momento da ação</li>
                <li><strong>Usuário:</strong> Quem realizou a ação</li>
                <li><strong>Ação:</strong> Tipo (Criação, Atualização, Desativação, etc)</li>
                <li><strong>Modelo:</strong> Qual objeto foi afetado (User, Restaurante, etc)</li>
                <li><strong>ID:</strong> ID do objeto afetado</li>
                <li><strong>IP:</strong> Endereço IP da pessoa que fez a ação</li>
                <li><strong>Mudanças:</strong> Detalhes do que foi alterado</li>
            </ul>
            <div className="info-card">
                <strong>Importante:</strong> Todos os logs são mantidos por um período configurável 
                nas configurações do sistema.
            </div>
        </div>
    </div>
);

const TiposAcao = () => (
    <div>
        <div className="content-header">
            <h1>Tipos de Ação</h1>
            <p>Diferentes tipos de ações registradas nos logs</p>
        </div>
        <div className="content-section">
            <h2>Tipos de Ação Registradas</h2>
            <ul>
                <li><strong>Criação:</strong> Novo item criado</li>
                <li><strong>Atualização:</strong> Item modificado</li>
                <li><strong>Desativação:</strong> Item soft deleted</li>
                <li><strong>Reativação:</strong> Item restaurado</li>
                <li><strong>Deleção Perm.:</strong> Item permanentemente deletado</li>
                <li><strong>Login:</strong> Usuário entrou no sistema</li>
                <li><strong>Logout:</strong> Usuário saiu do sistema</li>
            </ul>
            <h3>Visualizar Detalhes</h3>
            <p>
                Clique em "Ver detalhes" para expandir as mudanças específicas de cada ação, 
                incluindo valores antes e depois das alterações.
            </p>
        </div>
    </div>
);

const FiltrosAuditoria = () => (
    <div>
        <div className="content-header">
            <h1>Filtros de Auditoria</h1>
            <p>Como filtrar logs de auditoria</p>
        </div>
        <div className="content-section">
            <h2>Filtros Disponíveis</h2>
            <ul>
                <li><strong>Por Ação:</strong> Veja apenas um tipo de ação específica</li>
                <li><strong>Por Modelo:</strong> Filtre por tipo de objeto (User, Restaurante, etc)</li>
                <li><strong>Por Usuário:</strong> Veja ações de uma pessoa específica</li>
                <li><strong>Por Data:</strong> Selecione data inicial para filtrar por período</li>
            </ul>
            <div className="info-card">
                <strong>Dica:</strong> Combine múltiplos filtros para encontrar informações específicas 
                rapidamente.
            </div>
        </div>
    </div>
);

const Configuracoes = () => (
    <div>
        <div className="content-header">
            <h1>Configurações do Sistema</h1>
            <p>Como configurar o sistema StockOne</p>
        </div>
        <div className="content-section">
            <h2>Visão Geral</h2>
            <p>
                As configurações do sistema permitem personalizar diversos aspectos do StockOne, 
                incluindo informações gerais, segurança e retenção de dados.
            </p>
            <p>
                Todas as alterações nas configurações são registradas nos logs de auditoria.
            </p>
        </div>
    </div>
);

const InformacoesGerais = () => (
    <div>
        <div className="content-header">
            <h1>Informações Gerais</h1>
            <p>Configurações básicas do sistema</p>
        </div>
        <div className="content-section">
            <h2>Configurações Disponíveis</h2>
            <h3>Nome da Aplicação</h3>
            <p>Nome que aparece em emails e título do navegador.</p>
            <pre><code>Exemplo: "StockOne"</code></pre>
            <h3>Email de Contato</h3>
            <p>Email usado para comunicações do sistema.</p>
            <pre><code>Exemplo: "admin@stockone.com"</code></pre>
            <h3>Máximo de Usuários</h3>
            <p>Limite de usuários permitidos no sistema. Deixe "Ilimitado" para sem restrição.</p>
            <h3>Itens por Página</h3>
            <p>Quantidade de registros por página nas tabelas.</p>
            <ul>
                <li>Valores: 5 a 100</li>
                <li>Recomendado: 15</li>
            </ul>
        </div>
    </div>
);

const Seguranca = () => (
    <div>
        <div className="content-header">
            <h1>Configurações de Segurança</h1>
            <p>Opções de segurança do sistema</p>
        </div>
        <div className="content-section">
            <h2>Opções de Segurança</h2>
            <h3>Permitir Registro de Novos Usuários</h3>
            <ul>
                <li><strong>Desativado:</strong> Apenas admin pode criar usuários</li>
                <li><strong>Ativado:</strong> Usuários podem se autorregistrar (cuidado!)</li>
            </ul>
            <div className="warning-card">
                <strong>Atenção:</strong> Ativar o registro público pode ser um risco de segurança. 
                Use com cautela.
            </div>
            <h3>Ativar Modo de Manutenção</h3>
            <ul>
                <li><strong>Normal:</strong> Sistema funcionando normalmente</li>
                <li><strong>Manutenção:</strong> Apenas admin pode acessar</li>
            </ul>
            <p>Útil para manutenções importantes do sistema.</p>
        </div>
    </div>
);

const RetencaoDados = () => (
    <div>
        <div className="content-header">
            <h1>Retenção de Dados</h1>
            <p>Configuração de retenção de logs de auditoria</p>
        </div>
        <div className="content-section">
            <h2>Reter Logs de Auditoria</h2>
            <p>
                Número de dias para manter registros de auditoria. Após esse período, logs são 
                automaticamente removidos.
            </p>
            <ul>
                <li>Valores: 7 a 365 dias</li>
                <li>Exemplo: 90 dias = aproximadamente 3 meses de histórico</li>
            </ul>
            <div className="info-card">
                <strong>Recomendação:</strong> Mantenha pelo menos 30 dias de histórico para 
                fins de auditoria e conformidade.
            </div>
        </div>
    </div>
);

const Dicas = () => (
    <div>
        <div className="content-header">
            <h1>Dicas e Boas Práticas</h1>
            <p>Recomendações para uso eficiente do sistema</p>
        </div>
        <div className="content-section">
            <h2>Visão Geral</h2>
            <p>
                Seguir boas práticas ajuda a manter o sistema seguro, organizado e performático. 
                Esta seção contém dicas importantes sobre segurança, manutenção e performance.
            </p>
        </div>
    </div>
);

const DicasSeguranca = () => (
    <div>
        <div className="content-header">
            <h1>Dicas de Segurança</h1>
            <p>Boas práticas para manter o sistema seguro</p>
        </div>
        <div className="content-section">
            <h2>Recomendações de Segurança</h2>
            <ul>
                <li>Sempre crie senhas fortes (mínimo 8 caracteres)</li>
                <li>Revise regularmente quem tem acesso Admin</li>
                <li>Verifique os logs de auditoria periodicamente</li>
                <li>Use modo manutenção antes de atualizações importantes</li>
            </ul>
            <div className="success-card">
                <strong>Dica:</strong> Realize auditorias regulares de usuários com permissões 
                administrativas para garantir que apenas pessoas autorizadas tenham acesso.
            </div>
        </div>
    </div>
);

const DicasManutencao = () => (
    <div>
        <div className="content-header">
            <h1>Dicas de Manutenção</h1>
            <p>Como manter o sistema organizado e funcionando bem</p>
        </div>
        <div className="content-section">
            <h2>Recomendações de Manutenção</h2>
            <ul>
                <li>Exporte dados importantes regularmente</li>
                <li>Limpe logs antigos ajustando retenção</li>
                <li>Desative em vez de deletar permanentemente</li>
                <li>Aproveite o dashboard para monitorar</li>
            </ul>
            <div className="info-card">
                <strong>Nota:</strong> Desativar usuários em vez de deletá-los permite manter 
                histórico e facilita reativação se necessário.
            </div>
        </div>
    </div>
);

const DicasPerformance = () => (
    <div>
        <div className="content-header">
            <h1>Dicas de Performance</h1>
            <p>Como otimizar o uso do sistema</p>
        </div>
        <div className="content-section">
            <h2>Recomendações de Performance</h2>
            <ul>
                <li>Use filtros para encontrar dados rapidamente</li>
                <li>A paginação mostra 15 itens por página (configurável)</li>
                <li>Logs são indexados por data e ação (rápido)</li>
            </ul>
            <div className="info-card">
                <strong>Dica:</strong> Utilize os filtros disponíveis em vez de percorrer grandes 
                listas manualmente. Isso melhora significativamente a performance.
            </div>
        </div>
    </div>
);

const SolucaoProblemas = () => (
    <div>
        <div className="content-header">
            <h1>Solução de Problemas</h1>
            <p>Resolução de problemas comuns</p>
        </div>
        <div className="content-section">
            <h2>Problemas Comuns e Soluções</h2>
            <h3>"Acesso Negado" ao tentar acessar painel</h3>
            <p><strong>Solução:</strong> Você precisa estar logado com uma conta Admin.</p>
            <h3>Usuário não aparece nos filtros</h3>
            <p><strong>Solução:</strong></p>
            <ul>
                <li>Verifique o status (ativo/inativo)</li>
                <li>Verifique o restaurante atribuído</li>
                <li>Limpe os filtros e tente novamente</li>
            </ul>
            <h3>Não consigo deletar um usuário</h3>
            <p><strong>Solução:</strong></p>
            <ul>
                <li>Primeiro desative o usuário</li>
                <li>Depois clique em Deletar para remover permanentemente</li>
            </ul>
            <h3>Configurações não salvaram</h3>
            <p><strong>Solução:</strong></p>
            <ul>
                <li>Verifique se todos os campos obrigatórios estão preenchidos</li>
                <li>Veja se há erros de validação em vermelho</li>
                <li>Tente novamente</li>
            </ul>
            <h3>Logs estão vazios</h3>
            <p><strong>Solução:</strong></p>
            <ul>
                <li>Logs só aparecem após ações (criar, editar, deletar usuários)</li>
                <li>Verifique os filtros (talvez estejam muito restritivos)</li>
                <li>Limpe filtros clicando em "Limpar filtros"</li>
            </ul>
        </div>
    </div>
);

ReactDOM.render(<App />, document.getElementById('root'));

