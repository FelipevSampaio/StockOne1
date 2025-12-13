const { useState } = React;

const App = () => {
    const [activeSection, setActiveSection] = useState('introducao');

    const menuItems = [
        {
            title: 'Introdução',
            id: 'introducao',
            sections: [
                { id: 'introducao', title: 'Visão Geral do Sistema' },
                { id: 'modulos', title: 'Módulos do Sistema' }
            ]
        },
        {
            title: 'Módulo Público',
            id: 'publico',
            sections: [
                { id: 'login', title: 'Login e Autenticação' },
                { id: 'menu-publico', title: 'Menu Público' },
                { id: 'carrinho', title: 'Carrinho de Compras' },
                { id: 'checkout', title: 'Finalizar Pedido' }
            ]
        },
        {
            title: 'Painel Administrativo',
            id: 'admin',
            sections: [
                { id: 'admin-dashboard', title: 'Dashboard Administrativo' },
                { id: 'admin-usuarios', title: 'Gestão de Usuários' },
                { id: 'admin-restaurantes', title: 'Gestão de Restaurantes' },
                { id: 'admin-estoque-inteligente', title: 'Estoque Inteligente' },
                { id: 'admin-auditoria', title: 'Logs de Auditoria' },
                { id: 'admin-relatorios', title: 'Relatórios' },
                { id: 'admin-configuracoes', title: 'Configurações' },
                { id: 'admin-funcionarios', title: 'Funcionários' },
                { id: 'admin-mesas', title: 'Mesas e Reservas' },
                { id: 'admin-delivery', title: 'Pedidos de Delivery' },
                { id: 'admin-desperdicios', title: 'Controle de Desperdícios' },
                { id: 'admin-cardapio', title: 'Gestão de Cardápio' },
                { id: 'admin-notificacoes', title: 'Notificações' },
                { id: 'admin-busca', title: 'Busca Global' }
            ]
        },
        {
            title: 'Módulo Restaurante',
            id: 'restaurante',
            sections: [
                { id: 'restaurante-dashboard', title: 'Dashboard do Restaurante' },
                { id: 'restaurante-insumos', title: 'Gestão de Insumos' },
                { id: 'restaurante-cardapio', title: 'Gestão de Cardápio' },
                { id: 'restaurante-pedidos', title: 'Gestão de Pedidos' },
                { id: 'restaurante-estoque', title: 'Gestão de Estoque' },
                { id: 'restaurante-alertas', title: 'Gestão de Alertas' },
                { id: 'restaurante-compras', title: 'Compras e Sugestões' },
                { id: 'restaurante-receitas', title: 'Gestão de Receitas' },
                { id: 'restaurante-fila', title: 'Fila de Produção' }
            ]
        }
    ];

    const renderContent = () => {
        switch(activeSection) {
            case 'introducao':
                return <Introducao />;
            case 'modulos':
                return <Modulos />;
            case 'login':
                return <Login />;
            case 'menu-publico':
                return <MenuPublico />;
            case 'carrinho':
                return <Carrinho />;
            case 'checkout':
                return <Checkout />;
            case 'admin-dashboard':
                return <AdminDashboard />;
            case 'admin-usuarios':
                return <AdminUsuarios />;
            case 'admin-restaurantes':
                return <AdminRestaurantes />;
            case 'admin-estoque-inteligente':
                return <AdminEstoqueInteligente />;
            case 'admin-auditoria':
                return <AdminAuditoria />;
            case 'admin-relatorios':
                return <AdminRelatorios />;
            case 'admin-configuracoes':
                return <AdminConfiguracoes />;
            case 'admin-funcionarios':
                return <AdminFuncionarios />;
            case 'admin-mesas':
                return <AdminMesas />;
            case 'admin-delivery':
                return <AdminDelivery />;
            case 'admin-desperdicios':
                return <AdminDesperdicios />;
            case 'admin-cardapio':
                return <AdminCardapio />;
            case 'admin-notificacoes':
                return <AdminNotificacoes />;
            case 'admin-busca':
                return <AdminBusca />;
            case 'restaurante-dashboard':
                return <RestauranteDashboard />;
            case 'restaurante-insumos':
                return <RestauranteInsumos />;
            case 'restaurante-cardapio':
                return <RestauranteCardapio />;
            case 'restaurante-pedidos':
                return <RestaurantePedidos />;
            case 'restaurante-estoque':
                return <RestauranteEstoque />;
            case 'restaurante-alertas':
                return <RestauranteAlertas />;
            case 'restaurante-compras':
                return <RestauranteCompras />;
            case 'restaurante-receitas':
                return <RestauranteReceitas />;
            case 'restaurante-fila':
                return <RestauranteFila />;
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

// Componentes de Introdução
const Introducao = () => (
    <div>
        <div className="content-header">
            <h1>Manual StockOne</h1>
            <p>Guia completo de uso do sistema de gestão para restaurantes</p>
        </div>
        <div className="content-section">
            <h2>Visão Geral</h2>
            <p>
                O StockOne é um sistema completo de gestão para restaurantes que oferece controle 
                de estoque, pedidos, produção, cardápio e muito mais. Este manual fornece instruções 
                detalhadas sobre como utilizar todas as funcionalidades do sistema.
            </p>
            <p>
                O sistema é dividido em três módulos principais:
            </p>
            <ul>
                <li><strong>Módulo Público:</strong> Acesso sem autenticação para visualizar menu e fazer pedidos</li>
                <li><strong>Painel Administrativo:</strong> Gestão completa do sistema para administradores</li>
                <li><strong>Módulo Restaurante:</strong> Operações diárias do restaurante para usuários autenticados</li>
            </ul>
        </div>
    </div>
);

const Modulos = () => (
    <div>
        <div className="content-header">
            <h1>Módulos do Sistema</h1>
            <p>Estrutura e organização dos módulos StockOne</p>
        </div>
        <div className="content-section">
            <h2>Módulo Público (Sem Autenticação)</h2>
            <p>Área acessível sem necessidade de login:</p>
            <ul>
                <li><strong>Login/Autenticação:</strong> <code>/login</code> ou <code>/entrar</code> - Tela de login</li>
                <li><strong>Menu Público:</strong> <code>/menu</code> - Visualização do cardápio</li>
                <li><strong>Carrinho:</strong> Adicionar, atualizar e remover itens do carrinho</li>
                <li><strong>Checkout:</strong> Finalizar pedido</li>
            </ul>
            <h2>Painel Administrativo (Admin)</h2>
            <p>Área exclusiva para administradores do sistema:</p>
            <ul>
                <li>Dashboard e visão geral</li>
                <li>Gestão de usuários e restaurantes</li>
                <li>Auditoria e logs</li>
                <li>Relatórios diversos</li>
                <li>Configurações do sistema</li>
                <li>Gestão de funcionários, mesas, reservas</li>
                <li>Controle de delivery e desperdícios</li>
                <li>Notificações e busca global</li>
            </ul>
            <h2>Módulo Restaurante (Usuário Autenticado)</h2>
            <p>Área para operações diárias do restaurante:</p>
            <ul>
                <li>Dashboard do restaurante</li>
                <li>Gestão de insumos e estoque</li>
                <li>Cardápio e pedidos</li>
                <li>Alertas e sugestões de compra</li>
                <li>Receitas e fila de produção</li>
            </ul>
        </div>
    </div>
);

// Componentes do Módulo Público
const Login = () => (
    <div>
        <div className="content-header">
            <h1>Login e Autenticação</h1>
            <p>Acesso ao sistema através de login</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Para acessar o sistema, você precisa fazer login através das rotas:</p>
            <ul>
                <li><code>/login</code></li>
                <li><code>/entrar</code></li>
            </ul>
            <h3>Processo de Login</h3>
            <ol>
                <li>Informe seu email cadastrado</li>
                <li>Digite sua senha</li>
                <li>Clique em "Entrar" ou pressione Enter</li>
            </ol>
            <div className="info-card">
                <strong>Nota:</strong> Após o login bem-sucedido, você será redirecionado para o dashboard 
                correspondente ao seu perfil (administrador ou restaurante).
            </div>
            <h3>Recuperação de Senha</h3>
            <p>Se você esqueceu sua senha, utilize a opção de recuperação disponível na tela de login.</p>
        </div>
    </div>
);

const MenuPublico = () => (
    <div>
        <div className="content-header">
            <h1>Menu Público</h1>
            <p>Visualização do cardápio sem necessidade de login</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>O menu público está disponível em: <code>/menu</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Visualização de Itens:</strong> Todos os itens ativos do cardápio são exibidos</li>
                <li><strong>Organização por Categoria:</strong> Itens agrupados por categoria para facilitar navegação</li>
                <li><strong>Informações do Item:</strong> Nome, descrição, preço e imagem (quando disponível)</li>
                <li><strong>Adicionar ao Carrinho:</strong> Botão para adicionar itens diretamente ao carrinho</li>
            </ul>
            <h3>Filtros e Busca</h3>
            <p>O menu público oferece recursos de busca e filtragem para encontrar itens rapidamente:</p>
            <ul>
                <li>Busca por nome do item</li>
                <li>Filtro por categoria</li>
                <li>Filtro por preço</li>
            </ul>
            <div className="info-card">
                <strong>Importante:</strong> Apenas itens marcados como "ativo online" aparecem no menu público.
            </div>
        </div>
    </div>
);

const Carrinho = () => (
    <div>
        <div className="content-header">
            <h1>Carrinho de Compras</h1>
            <p>Gerenciamento de itens no carrinho</p>
        </div>
        <div className="content-section">
            <h2>Funcionalidades do Carrinho</h2>
            <p>O carrinho permite gerenciar os itens selecionados antes de finalizar o pedido.</p>
            <h3>Adicionar Itens</h3>
            <ol>
                <li>No menu público, clique no botão "Adicionar" do item desejado</li>
                <li>O item será adicionado ao carrinho automaticamente</li>
                <li>Você pode adicionar múltiplas unidades do mesmo item</li>
            </ol>
            <h3>Atualizar Quantidade</h3>
            <ul>
                <li>Use os botões de incremento (+) e decremento (-) para ajustar quantidades</li>
                <li>A quantidade mínima é 1 e máxima é 99 por item</li>
                <li>As alterações são salvas automaticamente</li>
            </ul>
            <h3>Remover Itens</h3>
            <ol>
                <li>Localize o item no carrinho</li>
                <li>Clique no botão de remover ou reduza a quantidade para zero</li>
                <li>O item será removido imediatamente</li>
            </ol>
            <h3>Visualização</h3>
            <p>O carrinho exibe:</p>
            <ul>
                <li>Nome e descrição de cada item</li>
                <li>Quantidade selecionada</li>
                <li>Preço unitário</li>
                <li>Subtotal por item</li>
                <li>Total geral do pedido</li>
            </ul>
            <div className="success-card">
                <strong>Dica:</strong> O carrinho é mantido na sessão, então você pode navegar pelo menu 
                e continuar adicionando itens sem perder o que já foi selecionado.
            </div>
        </div>
    </div>
);

const Checkout = () => (
    <div>
        <div className="content-header">
            <h1>Finalizar Pedido (Checkout)</h1>
            <p>Processo de finalização do pedido</p>
        </div>
        <div className="content-section">
            <h2>Processo de Checkout</h2>
            <p>Após adicionar itens ao carrinho, você pode finalizar o pedido.</p>
            <h3>Etapas</h3>
            <ol>
                <li><strong>Revisar Itens:</strong> Verifique todos os itens e quantidades no carrinho</li>
                <li><strong>Confirmar Total:</strong> Confirme o valor total do pedido</li>
                <li><strong>Finalizar:</strong> Clique no botão "Finalizar Pedido"</li>
            </ol>
            <h3>Informações do Pedido</h3>
            <p>Antes de finalizar, certifique-se de que:</p>
            <ul>
                <li>Todos os itens desejados estão no carrinho</li>
                <li>As quantidades estão corretas</li>
                <li>O valor total está de acordo</li>
            </ul>
            <div className="warning-card">
                <strong>Atenção:</strong> Após finalizar o pedido, ele será registrado no sistema e 
                não poderá ser cancelado pelo cliente. Para cancelamentos, entre em contato com o restaurante.
            </div>
            <h3>Confirmação</h3>
            <p>Após finalizar, você receberá uma confirmação do pedido com um número de identificação.</p>
        </div>
    </div>
);

// Componentes do Painel Administrativo
const AdminDashboard = () => (
    <div>
        <div className="content-header">
            <h1>Dashboard Administrativo</h1>
            <p>Visão geral do sistema para administradores</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>O dashboard administrativo está disponível em: <code>/admin/dashboard</code></p>
            <h3>Funcionalidades</h3>
            <p>O dashboard exibe estatísticas e informações importantes do sistema:</p>
            <ul>
                <li><strong>Total de Usuários:</strong> Contagem total, ativos e inativos</li>
                <li><strong>Administradores:</strong> Quantidade de usuários com permissão admin</li>
                <li><strong>Usuários Regulares:</strong> Quantidade de usuários comuns</li>
                <li><strong>Restaurantes:</strong> Total de restaurantes cadastrados</li>
                <li><strong>Pedidos:</strong> Estatísticas de pedidos (hoje, mês, pendentes)</li>
            </ul>
            <h3>Tabelas e Gráficos</h3>
            <ul>
                <li>Usuários recentes cadastrados</li>
                <li>Distribuição de usuários por restaurante</li>
                <li>Gráficos de crescimento e atividade</li>
            </ul>
        </div>
    </div>
);

const AdminUsuarios = () => (
    <div>
        <div className="content-header">
            <h1>Gestão de Usuários</h1>
            <p>Gerenciamento completo de usuários do sistema</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/admin/users</code></p>
            <h3>Funcionalidades</h3>
            <h4>Listar Usuários</h4>
            <p>Visualize todos os usuários do sistema com informações como nome, email, restaurante e papel.</p>
            <h4>Criar Usuário</h4>
            <p>Acesse <code>/admin/users/create</code> para criar um novo usuário:</p>
            <ul>
                <li>Nome completo</li>
                <li>Email único</li>
                <li>Senha (mínimo 8 caracteres)</li>
                <li>Restaurante associado</li>
                <li>Papel (Admin ou Usuário)</li>
            </ul>
            <h4>Editar Usuário</h4>
            <p>Acesse <code>/admin/users/{'{id}'}/edit</code> para modificar dados do usuário.</p>
            <h4>Operações</h4>
            <ul>
                <li><strong>Ativar/Desativar:</strong> Controle de acesso ao sistema</li>
                <li><strong>Deletar:</strong> Remoção permanente (requer confirmação)</li>
            </ul>
            <div className="info-card">
                <strong>Nota:</strong> Usuários desativados não podem fazer login, mas seus dados são preservados.
            </div>
        </div>
    </div>
);

const AdminRestaurantes = () => (
    <div>
        <div className="content-header">
            <h1>Gestão de Restaurantes</h1>
            <p>Gerenciamento de restaurantes cadastrados</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/admin/restaurantes</code></p>
            <h3>Funcionalidades</h3>
            <h4>Listar Restaurantes</h4>
            <p>Visualize todos os restaurantes com informações como nome, status e quantidade de usuários.</p>
            <h4>Criar Restaurante</h4>
            <p>Acesse <code>/admin/restaurantes/create</code> para cadastrar um novo restaurante.</p>
            <h4>Editar Restaurante</h4>
            <p>Acesse <code>/admin/restaurantes/{'{id}'}/edit</code> para modificar dados do restaurante.</p>
            <h4>Estoque Inteligente</h4>
            <p>Acesse <code>/admin/restaurantes/{'{id}'}/estoque-inteligente</code> para visualizar e gerenciar o estoque inteligente do restaurante.</p>
            <div className="success-card">
                <strong>Dica:</strong> O estoque inteligente oferece análises e sugestões automáticas 
                baseadas no histórico de consumo e estoque atual.
            </div>
        </div>
    </div>
);

const AdminEstoqueInteligente = () => (
    <div>
        <div className="content-header">
            <h1>Estoque Inteligente</h1>
            <p>Sistema de análise e sugestões de estoque</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/admin/restaurantes/{'{id}'}/estoque-inteligente</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Análise de Consumo:</strong> Histórico de consumo de insumos</li>
                <li><strong>Sugestões de Compra:</strong> Recomendações baseadas em estoque e consumo</li>
                <li><strong>Alertas de Reposição:</strong> Avisos quando estoque está baixo</li>
                <li><strong>Previsões:</strong> Estimativas de necessidade futura</li>
            </ul>
            <div className="info-card">
                <strong>Importante:</strong> O estoque inteligente utiliza algoritmos para otimizar 
                o gerenciamento de estoque e reduzir desperdícios.
            </div>
        </div>
    </div>
);

const AdminAuditoria = () => (
    <div>
        <div className="content-header">
            <h1>Logs de Auditoria</h1>
            <p>Registro completo de ações no sistema</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/admin/audit-logs</code></p>
            <h3>Funcionalidades</h3>
            <p>O sistema de auditoria registra todas as ações realizadas:</p>
            <ul>
                <li><strong>Data e Hora:</strong> Momento exato da ação</li>
                <li><strong>Usuário:</strong> Quem realizou a ação</li>
                <li><strong>Ação:</strong> Tipo de operação (criar, editar, deletar, etc)</li>
                <li><strong>Modelo:</strong> Entidade afetada (User, Restaurante, etc)</li>
                <li><strong>Mudanças:</strong> Detalhes do que foi alterado</li>
                <li><strong>IP:</strong> Endereço IP de origem</li>
            </ul>
            <h4>Detalhes do Log</h4>
            <p>Acesse <code>/admin/audit-logs/{'{id}'}</code> para ver detalhes completos de um log específico.</p>
            <h4>Filtros</h4>
            <p>Utilize filtros para encontrar logs específicos:</p>
            <ul>
                <li>Por usuário</li>
                <li>Por tipo de ação</li>
                <li>Por modelo</li>
                <li>Por período</li>
            </ul>
        </div>
    </div>
);

const AdminRelatorios = () => (
    <div>
        <div className="content-header">
            <h1>Relatórios</h1>
            <p>Relatórios e análises do sistema</p>
        </div>
        <div className="content-section">
            <h2>Relatórios Disponíveis</h2>
            <h3>Vendas por Período</h3>
            <p>Acesse <code>/admin/reports/vendas-periodo</code> para visualizar vendas agrupadas por período.</p>
            <h3>Vendas por Prato</h3>
            <p>Acesse <code>/admin/reports/vendas-prato</code> para análise de vendas por item do cardápio.</p>
            <h3>Vendas por Funcionário</h3>
            <p>Acesse <code>/admin/reports/vendas-funcionario</code> para ver desempenho de funcionários.</p>
            <h3>Funcionalidades dos Relatórios</h3>
            <ul>
                <li>Filtros por data</li>
                <li>Exportação para Excel/PDF</li>
                <li>Gráficos e visualizações</li>
                <li>Comparações entre períodos</li>
            </ul>
        </div>
    </div>
);

const AdminConfiguracoes = () => (
    <div>
        <div className="content-header">
            <h1>Configurações do Sistema</h1>
            <p>Configurações gerais do StockOne</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/admin/settings</code></p>
            <h3>Configurações Disponíveis</h3>
            <ul>
                <li><strong>Nome da Aplicação:</strong> Nome exibido no sistema</li>
                <li><strong>Email de Contato:</strong> Email para comunicações</li>
                <li><strong>Máximo de Usuários:</strong> Limite de usuários (ou ilimitado)</li>
                <li><strong>Itens por Página:</strong> Quantidade de registros nas tabelas</li>
                <li><strong>Registro de Usuários:</strong> Permitir ou não registro público</li>
                <li><strong>Modo Manutenção:</strong> Ativar modo de manutenção</li>
                <li><strong>Retenção de Logs:</strong> Período de retenção de logs de auditoria</li>
            </ul>
            <div className="warning-card">
                <strong>Atenção:</strong> Algumas configurações podem afetar o funcionamento do sistema. 
                Revise cuidadosamente antes de salvar.
            </div>
        </div>
    </div>
);

const AdminFuncionarios = () => (
    <div>
        <div className="content-header">
            <h1>Gestão de Funcionários</h1>
            <p>Gerenciamento de funcionários do sistema</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/admin/funcionarios</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Listar Funcionários:</strong> Visualizar todos os funcionários cadastrados</li>
                <li><strong>Criar Funcionário:</strong> Cadastrar novo funcionário</li>
                <li><strong>Editar Funcionário:</strong> Modificar dados do funcionário</li>
                <li><strong>Deletar Funcionário:</strong> Remover funcionário do sistema</li>
            </ul>
            <h3>Informações do Funcionário</h3>
            <ul>
                <li>Nome completo</li>
                <li>Cargo/função</li>
                <li>Restaurante associado</li>
                <li>Status (ativo/inativo)</li>
                <li>Contatos</li>
            </ul>
        </div>
    </div>
);

const AdminMesas = () => (
    <div>
        <div className="content-header">
            <h1>Mesas e Reservas</h1>
            <p>Gerenciamento de mesas e sistema de reservas</p>
        </div>
        <div className="content-section">
            <h2>Gestão de Mesas</h2>
            <p>Disponível em: <code>/admin/mesas</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Listar Mesas:</strong> Visualizar todas as mesas</li>
                <li><strong>Criar Mesa:</strong> Cadastrar nova mesa</li>
                <li><strong>Editar Mesa:</strong> Modificar informações da mesa</li>
                <li><strong>Status:</strong> Controlar disponibilidade (livre, ocupada, reservada)</li>
            </ul>
            <h2>Gestão de Reservas</h2>
            <p>Disponível em: <code>/admin/reservas</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Listar Reservas:</strong> Visualizar todas as reservas</li>
                <li><strong>Criar Reserva:</strong> Cadastrar nova reserva</li>
                <li><strong>Editar Reserva:</strong> Modificar dados da reserva</li>
                <li><strong>Status:</strong> Gerenciar status (confirmada, cancelada, concluída)</li>
            </ul>
            <h3>Informações da Reserva</h3>
            <ul>
                <li>Cliente</li>
                <li>Data e hora</li>
                <li>Mesa reservada</li>
                <li>Número de pessoas</li>
                <li>Observações</li>
            </ul>
        </div>
    </div>
);

const AdminDelivery = () => (
    <div>
        <div className="content-header">
            <h1>Pedidos de Delivery</h1>
            <p>Gerenciamento de pedidos de entrega</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Lista disponível em: <code>/admin/delivery</code></p>
            <p>Detalhes disponível em: <code>/admin/delivery/{'{id}'}</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Listar Pedidos:</strong> Visualizar todos os pedidos de delivery</li>
                <li><strong>Filtros:</strong> Por status, data, restaurante</li>
                <li><strong>Detalhes:</strong> Visualizar informações completas do pedido</li>
                <li><strong>Atualizar Status:</strong> Alterar status do pedido (pendente, em preparo, saiu para entrega, entregue, cancelado)</li>
            </ul>
            <h3>Informações do Pedido</h3>
            <ul>
                <li>Cliente e contato</li>
                <li>Endereço de entrega</li>
                <li>Itens do pedido</li>
                <li>Valor total</li>
                <li>Status atual</li>
                <li>Data e hora</li>
            </ul>
        </div>
    </div>
);

const AdminDesperdicios = () => (
    <div>
        <div className="content-header">
            <h1>Controle de Desperdícios</h1>
            <p>Gerenciamento e análise de desperdícios</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Lista disponível em: <code>/admin/desperdicios</code></p>
            <p>Análise disponível em: <code>/admin/desperdicios/analise</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Registrar Desperdício:</strong> Cadastrar ocorrências de desperdício</li>
                <li><strong>Listar Desperdícios:</strong> Visualizar histórico de desperdícios</li>
                <li><strong>Análise:</strong> Relatórios e gráficos de desperdícios</li>
                <li><strong>Filtros:</strong> Por período, tipo, restaurante</li>
            </ul>
            <h3>Informações do Desperdício</h3>
            <ul>
                <li>Item/Insumo desperdiçado</li>
                <li>Quantidade</li>
                <li>Motivo</li>
                <li>Data e hora</li>
                <li>Responsável</li>
            </ul>
            <div className="info-card">
                <strong>Importante:</strong> O controle de desperdícios ajuda a identificar padrões 
                e reduzir perdas, melhorando a eficiência do restaurante.
            </div>
        </div>
    </div>
);

const AdminCardapio = () => (
    <div>
        <div className="content-header">
            <h1>Gestão de Cardápio</h1>
            <p>Gerenciamento do cardápio do sistema</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/admin/cardapio</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Listar Itens:</strong> Visualizar todos os itens do cardápio</li>
                <li><strong>Criar Item:</strong> Adicionar novo item ao cardápio</li>
                <li><strong>Editar Item:</strong> Modificar informações do item</li>
                <li><strong>Ativar/Desativar:</strong> Controlar visibilidade no menu público</li>
                <li><strong>Organização:</strong> Gerenciar categorias e ordem de exibição</li>
            </ul>
            <h3>Informações do Item</h3>
            <ul>
                <li>Nome e descrição</li>
                <li>Categoria</li>
                <li>Preço de venda</li>
                <li>Imagem</li>
                <li>Tempo de preparo</li>
                <li>Status (ativo/inativo)</li>
            </ul>
        </div>
    </div>
);

const AdminNotificacoes = () => (
    <div>
        <div className="content-header">
            <h1>Central de Notificações</h1>
            <p>Gerenciamento de notificações do sistema</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/admin/notifications</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Visualizar Notificações:</strong> Ver todas as notificações do sistema</li>
                <li><strong>Filtros:</strong> Por tipo, prioridade, status (lida/não lida)</li>
                <li><strong>Marcar como Lida:</strong> Marcar notificações como visualizadas</li>
                <li><strong>Excluir:</strong> Remover notificações antigas</li>
            </ul>
            <h3>Tipos de Notificação</h3>
            <ul>
                <li>Alertas de estoque baixo</li>
                <li>Novos pedidos</li>
                <li>Atualizações de status</li>
                <li>Avisos do sistema</li>
            </ul>
        </div>
    </div>
);

const AdminBusca = () => (
    <div>
        <div className="content-header">
            <h1>Busca Global</h1>
            <p>Busca avançada em todo o sistema</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/admin/search</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Busca Unificada:</strong> Pesquisar em múltiplos módulos simultaneamente</li>
                <li><strong>Filtros:</strong> Por tipo de entidade (usuários, restaurantes, pedidos, etc)</li>
                <li><strong>Resultados Rápidos:</strong> Busca otimizada com resultados instantâneos</li>
                <li><strong>Histórico:</strong> Últimas buscas realizadas</li>
            </ul>
            <h3>O que pode ser buscado</h3>
            <ul>
                <li>Usuários</li>
                <li>Restaurantes</li>
                <li>Pedidos</li>
                <li>Itens do cardápio</li>
                <li>Insumos</li>
                <li>Funcionários</li>
            </ul>
        </div>
    </div>
);

// Componentes do Módulo Restaurante
const RestauranteDashboard = () => (
    <div>
        <div className="content-header">
            <h1>Dashboard do Restaurante</h1>
            <p>Visão geral das operações do restaurante</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/dashboard</code></p>
            <h3>Funcionalidades</h3>
            <p>O dashboard exibe informações importantes sobre o restaurante:</p>
            <ul>
                <li><strong>Pedidos de Hoje:</strong> Quantidade e valor total</li>
                <li><strong>Receita:</strong> Receita do dia, mês e comparações</li>
                <li><strong>Estoque Crítico:</strong> Itens com estoque baixo</li>
                <li><strong>Taxa de Conversão:</strong> Pedidos concluídos vs total</li>
                <li><strong>Tempo Médio de Preparo:</strong> Estatísticas de produção</li>
                <li><strong>Alertas:</strong> Notificações importantes</li>
            </ul>
            <h3>Gráficos e Visualizações</h3>
            <ul>
                <li>Gráfico de receita ao longo do tempo</li>
                <li>Distribuição de pedidos por status</li>
                <li>Top itens mais vendidos</li>
                <li>Análise de tendências</li>
            </ul>
        </div>
    </div>
);

const RestauranteInsumos = () => (
    <div>
        <div className="content-header">
            <h1>Gestão de Insumos</h1>
            <p>Gerenciamento de insumos do restaurante</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/insumos</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Listar Insumos:</strong> Visualizar todos os insumos cadastrados</li>
                <li><strong>Criar Insumo:</strong> Cadastrar novo insumo</li>
                <li><strong>Editar Insumo:</strong> Modificar informações do insumo</li>
                <li><strong>Deletar Insumo:</strong> Remover insumo do sistema</li>
            </ul>
            <h3>Informações do Insumo</h3>
            <ul>
                <li>Nome e descrição</li>
                <li>Categoria</li>
                <li>Unidade de medida</li>
                <li>Ponto de reposição mínimo</li>
                <li>Fornecedor</li>
                <li>Preço de compra</li>
            </ul>
            <h3>Filtros e Busca</h3>
            <ul>
                <li>Busca por nome ou descrição</li>
                <li>Filtro por categoria</li>
                <li>Filtro por estoque (baixo, ok)</li>
                <li>Ordenação personalizada</li>
            </ul>
        </div>
    </div>
);

const RestauranteCardapio = () => (
    <div>
        <div className="content-header">
            <h1>Gestão de Cardápio</h1>
            <p>Gerenciamento de itens do cardápio</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/cardapio-itens</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Listar Itens:</strong> Visualizar todos os itens do cardápio</li>
                <li><strong>Criar Item:</strong> Adicionar novo item ao cardápio</li>
                <li><strong>Editar Item:</strong> Modificar informações do item</li>
                <li><strong>Deletar Item:</strong> Remover item do cardápio</li>
                <li><strong>Ativar/Desativar Online:</strong> Controlar visibilidade no menu público</li>
            </ul>
            <h3>Informações do Item</h3>
            <ul>
                <li>Nome e descrição</li>
                <li>Categoria</li>
                <li>Preço de venda</li>
                <li>Imagem</li>
                <li>Tempo de preparo estimado</li>
                <li>Ordem de exibição</li>
                <li>Status (ativo/inativo)</li>
            </ul>
            <h3>Organização</h3>
            <p>Organize os itens por categoria e defina a ordem de exibição no menu público.</p>
        </div>
    </div>
);

const RestaurantePedidos = () => (
    <div>
        <div className="content-header">
            <h1>Gestão de Pedidos</h1>
            <p>Gerenciamento de pedidos do restaurante</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Lista disponível em: <code>/pedidos</code></p>
            <p>Detalhes disponível em: <code>/pedidos/{'{id}'}/detalhes</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Listar Pedidos:</strong> Visualizar todos os pedidos</li>
                <li><strong>Filtros:</strong> Por status, data, tipo (mesa, delivery, balcão)</li>
                <li><strong>Detalhes:</strong> Visualizar informações completas do pedido</li>
                <li><strong>Atualizar Status:</strong> Alterar status do pedido</li>
            </ul>
            <h3>Status dos Pedidos</h3>
            <ul>
                <li><strong>Pendente:</strong> Pedido recebido, aguardando preparo</li>
                <li><strong>Em Preparo:</strong> Pedido sendo preparado</li>
                <li><strong>Pronto:</strong> Pedido finalizado, aguardando entrega</li>
                <li><strong>Concluído:</strong> Pedido entregue ao cliente</li>
                <li><strong>Cancelado:</strong> Pedido cancelado</li>
            </ul>
            <h3>Informações do Pedido</h3>
            <ul>
                <li>Número do pedido</li>
                <li>Data e hora</li>
                <li>Itens e quantidades</li>
                <li>Valor total</li>
                <li>Status atual</li>
                <li>Observações</li>
            </ul>
        </div>
    </div>
);

const RestauranteEstoque = () => (
    <div>
        <div className="content-header">
            <h1>Gestão de Estoque</h1>
            <p>Controle de estoque do restaurante</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/estoque</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Listar Estoque:</strong> Visualizar estoque de todos os insumos</li>
                <li><strong>Atualizar Quantidade:</strong> Ajustar quantidade em estoque</li>
                <li><strong>Entrada de Estoque:</strong> Registrar entrada de insumos</li>
                <li><strong>Saída de Estoque:</strong> Registrar consumo/uso</li>
                <li><strong>Histórico:</strong> Visualizar movimentações</li>
            </ul>
            <h3>Informações do Estoque</h3>
            <ul>
                <li>Insumo</li>
                <li>Quantidade atual</li>
                <li>Ponto de reposição mínimo</li>
                <li>Status (crítico, baixo, ok)</li>
                <li>Última atualização</li>
            </ul>
            <h3>Alertas</h3>
            <p>O sistema alerta automaticamente quando o estoque está abaixo do ponto de reposição.</p>
            <div className="warning-card">
                <strong>Atenção:</strong> Mantenha o estoque atualizado para evitar falta de insumos 
                durante o preparo dos pratos.
            </div>
        </div>
    </div>
);

const RestauranteAlertas = () => (
    <div>
        <div className="content-header">
            <h1>Gestão de Alertas</h1>
            <p>Gerenciamento de alertas do restaurante</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/alertas</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Listar Alertas:</strong> Visualizar todos os alertas ativos</li>
                <li><strong>Criar Alerta:</strong> Cadastrar novo alerta manual</li>
                <li><strong>Editar Alerta:</strong> Modificar informações do alerta</li>
                <li><strong>Resolver Alerta:</strong> Marcar alerta como resolvido</li>
                <li><strong>Deletar Alerta:</strong> Remover alerta do sistema</li>
            </ul>
            <h3>Tipos de Alertas</h3>
            <ul>
                <li><strong>Estoque Baixo:</strong> Insumo abaixo do ponto de reposição</li>
                <li><strong>Validade Próxima:</strong> Produto próximo do vencimento</li>
                <li><strong>Pedido Pendente:</strong> Pedido aguardando há muito tempo</li>
                <li><strong>Personalizado:</strong> Alertas criados manualmente</li>
            </ul>
            <h3>Prioridades</h3>
            <ul>
                <li>Baixa</li>
                <li>Média</li>
                <li>Alta</li>
                <li>Crítica</li>
            </ul>
        </div>
    </div>
);

const RestauranteCompras = () => (
    <div>
        <div className="content-header">
            <h1>Compras e Sugestões</h1>
            <p>Sugestões automáticas de compra baseadas em estoque</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/compras-sugestoes</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Listar Sugestões:</strong> Visualizar sugestões de compra geradas automaticamente</li>
                <li><strong>Analisar Sugestão:</strong> Ver detalhes e justificativa da sugestão</li>
                <li><strong>Aprovar Sugestão:</strong> Converter sugestão em ordem de compra</li>
                <li><strong>Rejeitar Sugestão:</strong> Descartar sugestão</li>
                <li><strong>Criar Compra Manual:</strong> Registrar compra sem sugestão</li>
            </ul>
            <h3>Como Funciona</h3>
            <p>O sistema analisa automaticamente:</p>
            <ul>
                <li>Estoque atual de insumos</li>
                <li>Ponto de reposição mínimo</li>
                <li>Histórico de consumo</li>
                <li>Tendências de uso</li>
            </ul>
            <p>Com base nesses dados, gera sugestões inteligentes de compra.</p>
            <div className="success-card">
                <strong>Dica:</strong> As sugestões ajudam a manter o estoque sempre adequado, 
                evitando faltas e reduzindo desperdícios.
            </div>
        </div>
    </div>
);

const RestauranteReceitas = () => (
    <div>
        <div className="content-header">
            <h1>Gestão de Receitas</h1>
            <p>Gerenciamento de receitas e preparos</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Lista disponível em: <code>/receitas</code></p>
            <p>Detalhes disponível em: <code>/receitas/{'{id}'}/detalhes</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Listar Receitas:</strong> Visualizar todas as receitas cadastradas</li>
                <li><strong>Criar Receita:</strong> Cadastrar nova receita</li>
                <li><strong>Editar Receita:</strong> Modificar informações da receita</li>
                <li><strong>Deletar Receita:</strong> Remover receita do sistema</li>
                <li><strong>Visualizar Detalhes:</strong> Ver ingredientes, quantidades e modo de preparo</li>
            </ul>
            <h3>Informações da Receita</h3>
            <ul>
                <li>Nome da receita</li>
                <li>Descrição</li>
                <li>Ingredientes e quantidades</li>
                <li>Modo de preparo</li>
                <li>Tempo de preparo</li>
                <li>Rendimento (porções)</li>
                <li>Custo estimado</li>
            </ul>
            <h3>Integração com Cardápio</h3>
            <p>As receitas podem ser vinculadas aos itens do cardápio, facilitando o cálculo de custos 
            e o controle de estoque necessário para cada prato.</p>
        </div>
    </div>
);

const RestauranteFila = () => (
    <div>
        <div className="content-header">
            <h1>Fila de Produção</h1>
            <p>Gerenciamento da fila de preparo de pedidos</p>
        </div>
        <div className="content-section">
            <h2>Acesso</h2>
            <p>Disponível em: <code>/fila-producao</code></p>
            <h3>Funcionalidades</h3>
            <ul>
                <li><strong>Visualizar Fila:</strong> Ver todos os pedidos em produção</li>
                <li><strong>Ordenar Fila:</strong> Reorganizar ordem de preparo</li>
                <li><strong>Atualizar Status:</strong> Marcar pedido como em preparo, pronto, etc</li>
                <li><strong>Priorizar:</strong> Definir prioridade de preparo</li>
                <li><strong>Atribuir Cozinheiro:</strong> Designar responsável pelo preparo</li>
            </ul>
            <h3>Organização</h3>
            <p>A fila de produção organiza os pedidos de forma a otimizar o tempo de preparo:</p>
            <ul>
                <li>Pedidos são ordenados por prioridade e data</li>
                <li>Itens similares podem ser agrupados</li>
                <li>Tempo estimado de preparo é exibido</li>
                <li>Status visual facilita acompanhamento</li>
            </ul>
            <h3>Status na Fila</h3>
            <ul>
                <li><strong>Aguardando:</strong> Pedido na fila, aguardando início do preparo</li>
                <li><strong>Em Preparo:</strong> Pedido sendo preparado</li>
                <li><strong>Pronto:</strong> Preparo concluído</li>
            </ul>
            <div className="info-card">
                <strong>Importante:</strong> A fila de produção ajuda a organizar o trabalho da cozinha 
                e garantir que os pedidos sejam preparados na ordem correta.
            </div>
        </div>
    </div>
);

ReactDOM.render(<App />, document.getElementById('root'));
