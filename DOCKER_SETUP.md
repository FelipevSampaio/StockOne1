# 🚀 Setup Docker para StockOne Laravel

## Pré-requisitos
- Docker e Docker Compose instalados

## Passos

1. Copie o arquivo de ambiente:
   ```bash
   cp .env.example .env
   ```
2. Edite o `.env` e configure:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=stockone
   DB_USERNAME=root
   DB_PASSWORD=secret
   ```
3. Suba os containers:
   ```bash
   docker-compose up --build
   ```
4. Acesse o sistema em: [http://localhost:8000](http://localhost:8000)

## Serviços Adicionais

- **Redis**: Usado para cache, filas e sessões. Configure no `.env`:
  ```env
  REDIS_HOST=redis
  REDIS_PORT=6379
  ```
- **Mailhog**: Teste envio de e-mails em [http://localhost:8025](http://localhost:8025). Configure no `.env`:
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=mailhog
  MAIL_PORT=1025
  MAIL_USERNAME=null
  MAIL_PASSWORD=null
  MAIL_ENCRYPTION=null
  ```
- **Adminer**: Gerencie o banco de dados em [http://localhost:8080](http://localhost:8080).

## Observações
- O banco de dados será inicializado com usuário root e senha `secret`.
- O comando de migração e seed roda automaticamente ao iniciar o container.
- Para executar comandos artisan:
   ```bash
   docker-compose exec app php artisan <comando>
   ```
- Para instalar pacotes:
   ```bash
   docker-compose exec app composer require <pacote>
   docker-compose exec app npm install <pacote>
   ```

---
Dúvidas ou problemas? Consulte o README ou abra uma issue.
