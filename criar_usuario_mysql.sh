#!/bin/bash

echo "═══════════════════════════════════════════════════════════════"
echo "  Criando usuário MySQL para o StockOne..."
echo "═══════════════════════════════════════════════════════════════"
echo ""

sudo mysql << 'EOF'
CREATE USER IF NOT EXISTS 'stockone_user'@'localhost' IDENTIFIED BY 'stockone_pass_2024';
GRANT ALL PRIVILEGES ON laravel.* TO 'stockone_user'@'localhost';
FLUSH PRIVILEGES;
SELECT '✅ Usuário stockone_user criado com sucesso!' AS Status;
EOF

if [ $? -eq 0 ]; then
    echo ""
    echo "═══════════════════════════════════════════════════════════════"
    echo "  ✅ SUCESSO! Usuário criado."
    echo "═══════════════════════════════════════════════════════════════"
    echo ""
    echo "Agora execute:"
    echo "  php artisan config:clear"
    echo ""
else
    echo ""
    echo "═══════════════════════════════════════════════════════════════"
    echo "  ❌ ERRO ao criar usuário. Verifique as permissões."
    echo "═══════════════════════════════════════════════════════════════"
    echo ""
fi
