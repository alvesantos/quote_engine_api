# Quote Engine API

Rode em conjunto:

[Frontend](https://github.com/ebagabee/quote_engine) 

Passo a passo para rodar o projeto localmente.

## Dependências

- PHP 8.1+
- PostgreSQL 18.4
- Composer

## Configuração

### 1. Clonar o repositório

```bash
git clone git@github.com:ebagabee/quote_engine_api.git
cd quote_engine_api
```

### 2. Instalar dependências

```bash
composer install
```

### 3. Configurar o ambiente

- Copie o arquivo de `.env.example` ou duplique, renomeie para `.env`.
- Edite o `.env` com suas credenciais do PostgreSQL:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=quote_engine 
DB_USERNAME=postgres
DB_PASSWORD=sua_senha
```

### 4. Gerar a chave da aplicação

```bash
php artisan key:generate
```

### 5. Rodar as migrations

```bash
php artisan migrate
```

### 6. Subir o servidor

```bash
php artisan serve
```

A API estará disponível em `http://localhost:8000`.

### Para Executar testes

```bash
php artisan test
```