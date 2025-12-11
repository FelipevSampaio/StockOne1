#!/bin/bash

echo "=== Configuração do MySQL para StockOne ==="
echo ""
echo "Este script irá criar um banco de dados e usuário MySQL."
echo ""
echo "Você precisará executar este comando manualmente:"
echo ""
echo "sudo mysql"
echo ""
echo "Depois, no MySQL, execute:"
echo ""
echo "CREATE DATABASE IF NOT EXISTS stockone CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo "CREATE USER IF NOT EXISTS 'stockone_user'@'localhost' IDENTIFIED BY 'stockone_pass_2024';"
echo "GRANT ALL PRIVILEGES ON stockone.* TO 'stockone_user'@'localhost';"
echo "FLUSH PRIVILEGES;"
echo "EXIT;"
echo ""
echo "OU execute o arquivo SQL diretamente:"
echo "sudo mysql < setup_mysql.sql"
echo ""



