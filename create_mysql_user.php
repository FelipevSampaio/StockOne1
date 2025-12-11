<?php

/**
 * Script para criar usuário MySQL via socket Unix
 * Execute: php create_mysql_user.php
 */

$socket = '/var/run/mysqld/mysqld.sock';

if (!file_exists($socket)) {
    die("Erro: Socket MySQL não encontrado em $socket\n");
}

try {
    // Conectar via socket Unix (funciona com root no Ubuntu)
    $pdo = new PDO("mysql:unix_socket=$socket", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conectado ao MySQL via socket!\n\n";

    // Criar usuário
    $pdo->exec("CREATE USER IF NOT EXISTS 'stockone_user'@'localhost' IDENTIFIED BY 'stockone_pass_2024'");
    echo "✓ Usuário 'stockone_user' criado\n";

    // Conceder permissões no banco laravel
    $pdo->exec("GRANT ALL PRIVILEGES ON laravel.* TO 'stockone_user'@'localhost'");
    echo "✓ Permissões concedidas no banco 'laravel'\n";

    // Aplicar mudanças
    $pdo->exec("FLUSH PRIVILEGES");
    echo "✓ Privilégios aplicados\n\n";

    echo "SUCESSO! Usuário criado com sucesso.\n";
    echo "Seus dados no banco 'laravel' estão seguros!\n";

} catch (PDOException $e) {
    if (strpos($e->getMessage(), '1698') !== false) {
        echo "ERRO: Não foi possível conectar via socket.\n";
        echo "Execute manualmente no terminal:\n\n";
        echo "sudo mysql\n\n";
        echo "E depois cole:\n";
        echo "CREATE USER IF NOT EXISTS 'stockone_user'@'localhost' IDENTIFIED BY 'stockone_pass_2024';\n";
        echo "GRANT ALL PRIVILEGES ON laravel.* TO 'stockone_user'@'localhost';\n";
        echo "FLUSH PRIVILEGES;\n";
        echo "EXIT;\n";
    } else {
        echo "ERRO: " . $e->getMessage() . "\n";
    }
    exit(1);
}



