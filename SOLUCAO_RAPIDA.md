# 🔧 SOLUÇÃO RÁPIDA - Criar Usuário MySQL

## ⚠️ PROBLEMA
O usuário `stockone_user` ainda não foi criado no MySQL.

## ✅ SOLUÇÃO (ESCOLHA UMA):

### Opção 1: Comando Único (MAIS RÁPIDO)
Copie e cole este comando COMPLETO no terminal:

```bash
sudo mysql -e "CREATE USER IF NOT EXISTS 'stockone_user'@'localhost' IDENTIFIED BY 'stockone_pass_2024'; GRANT ALL PRIVILEGES ON laravel.* TO 'stockone_user'@'localhost'; FLUSH PRIVILEGES; SELECT '✓ Usuário criado!' AS Status;"
```

### Opção 2: Passo a Passo
1. Execute: `sudo mysql`
2. Cole estes comandos:
   ```sql
   CREATE USER IF NOT EXISTS 'stockone_user'@'localhost' IDENTIFIED BY 'stockone_pass_2024';
   GRANT ALL PRIVILEGES ON laravel.* TO 'stockone_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

### Opção 3: Usar o Script
```bash
./fix_database.sh
```

## 🧪 DEPOIS DE EXECUTAR, TESTE:
```bash
php artisan config:clear
php artisan migrate:status
```

## ✅ SE FUNCIONAR:
O menu público deve funcionar normalmente!

## ❌ SE NÃO FUNCIONAR:
Envie a mensagem de erro completa.
