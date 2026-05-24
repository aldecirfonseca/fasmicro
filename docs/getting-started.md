# FasMicro — Documentação

Bem-vindo à documentação do framework **FasMicro**.

## Índice

| Documento | Descrição |
|-----------|-----------|
| [Instalação e Configuração](#instalação) | Pré-requisitos, passos de setup e variáveis de ambiente |
| [Arquitetura](architecture.md) | Estrutura de diretórios e classes do núcleo |
| [Rotas Web](routes.md) | Rotas públicas e administrativas da aplicação |
| [Banco de Dados](database.md) | Tabelas, relacionamentos e papéis de usuário |

### API REST

| Documento | Descrição |
|-----------|-----------|
| [Visão Geral](api/overview.md) | URL base, CORS e variáveis de ambiente |
| [Autenticação](api/authentication.md) | JWT, login, refresh token e níveis de acesso |
| [Endpoints](api/endpoints.md) | Referência completa com exemplos de request/response |
| [Formato de Respostas](api/response-format.md) | Estrutura padrão de sucesso, erro e paginação |

---

## Pré-requisitos

- PHP 7.4 ou superior
- MySQL 8.x
- Apache com `mod_rewrite` habilitado
- Composer

---

## Instalação

**1. Clone o repositório**

```bash
git clone <url-do-repositorio>
cd fasmicro
```

**2. Instale as dependências**

```bash
composer install
```

**3. Configure o ambiente**

Copie o arquivo de exemplo e preencha com suas credenciais:

```bash
cp exemplo.env .env
```

**4. Crie o banco de dados**

```bash
mysql -u root -p < script-database.sql
```

**5. Configure o Apache**

Aponte o `DocumentRoot` do virtual host para a pasta `public/`:

```apache
DocumentRoot "/caminho/para/fasmicro/public"
```

Certifique-se de que `mod_rewrite` está ativo e que `AllowOverride All` está configurado no diretório.

---

## Configuração do `.env`

```ini
# Ambiente: DEVELOPMENT ou PRODUCTION
ENVIRONMENT=DEVELOPMENT

# Banco de dados (DEVELOPMENT)
DEV_DB_HOST=localhost
DEV_DB_NAME=fasmicro
DEV_DB_USER=root
DEV_DB_PASS=sua_senha

# Banco de dados (PRODUCTION)
PROD_DB_HOST=fasmicro.com.br
PROD_DB_NAME=fasmicro
PROD_DB_USER=usuario
PROD_DB_PASS=senha_producao

# E-mail SMTP
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USER=seu@gmail.com
MAIL_PASS=sua_senha_de_app
MAIL_FROM=seu@gmail.com
MAIL_FROM_NAME=FasMicro

# Super usuário inicial
SUPER_USER_EMAIL=admin@fasmicro.com.br
SUPER_USER_PASS=senha_admin

# API JWT — OBRIGATÓRIO para usar a API REST
JWT_SECRET=sua_chave_secreta_muito_segura_min_32_chars
JWT_EXPIRE=3600           # Access token: 1 hora (segundos)
JWT_REFRESH_EXPIRE=604800 # Refresh token: 7 dias (segundos)

# CORS — domínio permitido (* = qualquer origem)
API_CORS_ORIGIN=*
```

---

## Adicionando novos recursos à API

1. Crie o controller em `app/Controller/Api/` estendendo `ApiControllerMain`
2. Registre as rotas em `app/Config/ApiRoutesConfig.php` usando `ApiRoutes::get/post/put/patch/delete`
3. Documente o novo recurso em `docs/api/endpoints.md`
