# 🔧 Solução para Erro 1045 - Access Denied MySQL

## Problema
```
SQLSTATE[HY000] [1045] Access denied for user 'stockone_user'@'localhost'
```

O usuário MySQL `stockone_user` não existe ou não tem as permissões necessárias.

## ✅ Solução Rápida

Execute este comando no terminal:

```bash
sudo mysql < setup_mysql.sql
```

OU execute manualmente:

```bash
sudo mysql
```

Depois cole estes comandos no MySQL:

```sql
CREATE USER IF NOT EXISTS 'stockone_user'@'localhost' IDENTIFIED BY 'stockone_pass_2024';
GRANT ALL PRIVILEGES ON laravel.* TO 'stockone_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Depois execute:

```bash
php artisan config:clear
```

## 🔍 Verificar se funcionou

```bash
mysql -u stockone_user -pstockone_pass_2024 -e "SELECT 1;"
```

Se aparecer uma tabela com "1", está funcionando!

## 📝 Nota

- O script **NÃO apaga** dados existentes
- Seus dados no banco `laravel` estão **100% seguros**
- O script apenas cria um novo usuário e concede permissões
