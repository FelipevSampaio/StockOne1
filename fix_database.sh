#!/bin/bash

echo "=========================================="
echo "  Configuração do MySQL para StockOne"
echo "=========================================="
echo ""
echo "Este script irá criar o usuário MySQL necessário."
echo "Você precisará inserir sua senha de administrador."
echo ""
read -p "Pressione ENTER para continuar..."

echo ""
echo "Executando comandos SQL..."
echo ""

sudo mysql <<EOF
CREATE USER IF NOT EXISTS 'stockone_user'@'localhost' IDENTIFIED BY 'stockone_pass_2024';
GRANT ALL PRIVILEGES ON laravel.* TO 'stockone_user'@'localhost';
FLUSH PRIVILEGES;
SELECT 'Usuário criado com sucesso!' AS Status;
EOF

if [ $? -eq 0 ]; then
    echo ""
    echo "✓ Usuário criado com sucesso!"
    echo "✓ Seus dados no banco 'laravel' estão seguros!"
    echo ""
    echo "Agora execute:"
    echo "  php artisan config:clear"
    echo "  php artisan migrate:status"
else
    echo ""
    echo "✗ Erro ao criar usuário."
    echo ""
    echo "Execute manualmente:"
    echo "  sudo mysql"
    echo ""
    echo "E depois cole:"
    echo "  CREATE USER IF NOT EXISTS 'stockone_user'@'localhost' IDENTIFIED BY 'stockone_pass_2024';"
    echo "  GRANT ALL PRIVILEGES ON laravel.* TO 'stockone_user'@'localhost';"
    echo "  FLUSH PRIVILEGES;"
    echo "  EXIT;"
fi


