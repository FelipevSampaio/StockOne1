-- Script para configurar o MySQL para o Laravel StockOne
-- Execute com: sudo mysql < setup_mysql.sql
--
-- IMPORTANTE: Este script NÃO apaga dados existentes!
-- Ele apenas cria um novo usuário e concede permissões no banco 'laravel' existente
-- Seus dados estão 100% seguros!

-- Criar usuário para o banco 'laravel' existente (NÃO cria banco novo, usa o que já existe)
CREATE USER IF NOT EXISTS 'stockone_user'@'localhost' IDENTIFIED BY 'stockone_pass_2024';

-- Conceder permissões no banco 'laravel' existente (seus dados continuam lá!)
GRANT ALL PRIVILEGES ON laravel.* TO 'stockone_user'@'localhost';

-- Aplicar mudanças
FLUSH PRIVILEGES;

-- Mostrar confirmação
SELECT 'Usuário criado com sucesso! Seus dados no banco laravel estão seguros.' AS Status;

