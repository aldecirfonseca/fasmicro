# FasMicro

Micro Framework PHP desenvolvido como projeto acadêmico no 5º período — turma 2026 — **FASM (Faculdade Santa Marcelina)**.

---

## Sobre o Projeto

O FasMicro é um micro-framework MVC construído do zero em PHP puro, sem depender de frameworks externos. O objetivo é demonstrar, na prática, os fundamentos de desenvolvimento web back-end: roteamento, padrão MVC, acesso a banco de dados via PDO, autenticação de usuários, validação de formulários e envio de e-mail.

---

## Stack Tecnológica

| Camada | Tecnologia |
|---|---|
| Linguagem | PHP 7.4+ |
| Banco de Dados | MySQL 8.x |
| Frontend | Bootstrap 5.0.2 |
| E-mail | PHPMailer 7.x |
| Gerenciador de Dependências | Composer |
| Servidor Web | Apache (com mod_rewrite) |

---

## Funcionalidades Implementadas

- **Autenticação** — Login/Logout com senha criptografada (`password_hash` / `password_verify`)
- **Controle de Acesso** — Rotas públicas e protegidas; papéis de usuário (Super Admin, Admin, Usuário)
- **CRUD de Produtos** — Listagem, cadastro, edição e exclusão com validação
- **CRUD de Unidades de Medida** — Listagem, cadastro, edição e exclusão
- **CRUD de Categorias** — Listagem, cadastro, edição e exclusão
- **Formulário de Contato** — Envio de e-mail via SMTP (Gmail) usando PHPMailer
- **Roteamento Customizado** — URLs amigáveis mapeadas para Controller/Método
- **Sistema de Validação** — Validação de formulários com regras configuráveis por model
- **Template Engine** — Layouts reutilizáveis com escape de HTML automático
- **Upload de Arquivos** — Suporte a imagens, documentos e outros tipos (limite: 5 MB)

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

Edite o `.env` com os dados do seu ambiente local (banco de dados e e-mail).

**4. Crie o banco de dados**

Execute o script SQL incluído no projeto:

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
```

---

## Estrutura do Projeto

```
fasmicro/
├── app/                        # Código da aplicação
│   ├── Config/
│   │   └── Constants.php       # Constantes globais (timezone, controller padrão, etc.)
│   ├── Controller/
│   │   ├── Home.php            # Página inicial e formulário de contato
│   │   ├── Login.php           # Autenticação (entrar / sair)
│   │   ├── Produto.php         # CRUD de produtos
│   │   ├── Categoria.php       # CRUD de categorias
│   │   └── UnidadeMedida.php   # CRUD de unidades de medida
│   ├── Helper/
│   │   ├── formHelper.php      # Renderização de campos de formulário
│   │   └── jsHelper.php        # Utilitários JavaScript
│   ├── Model/
│   │   ├── ProdutoModel.php
│   │   ├── CategoriaModel.php
│   │   ├── UnidadeMedidaModel.php
│   │   └── UsuarioModel.php
│   └── View/
│       ├── admin/              # Views da área administrativa
│       ├── Layout/             # Layouts reutilizáveis (default, login)
│       ├── login/              # View de login
│       ├── home.php            # Página inicial pública
│       └── contato.php         # Formulário de contato
│
├── core/                       # Núcleo do framework
│   ├── Helper/
│   │   ├── data.php            # Formatação de datas e dados
│   │   └── url.php             # Geração de URLs
│   └── Library/
│       ├── Ambiente.php        # Leitura do .env
│       ├── ControllerMain.php  # Controller base
│       ├── Database.php        # Abstração PDO (query builder)
│       ├── Email.php           # Wrapper PHPMailer
│       ├── Erros.php           # Tratamento de erros
│       ├── Files.php           # Upload de arquivos
│       ├── ModelMain.php       # Model base com CRUD e validação
│       ├── Raw.php             # Saída de dados brutos
│       ├── Redirect.php        # Redirecionamentos com mensagens flash
│       ├── Request.php         # Dados da requisição HTTP
│       ├── RequestTrait.php    # Trait auxiliar de requisição
│       ├── Routes.php          # Roteador URL → Controller/Método
│       ├── Session.php         # Gerenciamento de sessão
│       ├── Template.php        # Motor de templates/views
│       └── Validator.php       # Validação de formulários
│
├── public/                     # Document root — único diretório público
│   ├── assests/
│   │   ├── bootstrap/          # CSS e JS do Bootstrap 5
│   │   ├── css/                # Estilos customizados
│   │   ├── img/                # Imagens públicas
│   │   └── js/                 # Scripts customizados
│   ├── .htaccess               # Rewrite rules (tudo para index.php)
│   └── index.php               # Front controller
│
├── uploads/                    # Arquivos enviados pelos usuários
├── vendor/                     # Dependências Composer (PHPMailer)
├── .env                        # Configurações do ambiente (não versionado)
├── exemplo.env                 # Template do .env
├── composer.json
├── script-database.sql         # Script de criação do banco de dados
└── README.md
```

---

## Rotas da Aplicação

### Rotas Públicas (sem autenticação)

| Rota | Controller | Método | Descrição |
|---|---|---|---|
| `/` | Home | index | Página inicial |
| `/Home/contato` | Home | contato | Formulário de contato |
| `/Login` | Login | index | Tela de login |
| `/Login/entrar` | Login | entrar | Processar login (POST) |
| `/Login/sair` | Login | sair | Logout |

### Rotas Administrativas (requerem autenticação)

| Rota | Controller | Descrição |
|---|---|---|
| `/Produto` | Produto | Listagem de produtos |
| `/Produto/form` | Produto | Cadastrar novo produto |
| `/Produto/form/{id}` | Produto | Editar produto |
| `/Produto/excluir/{id}` | Produto | Excluir produto |
| `/UnidadeMedida` | UnidadeMedida | Listagem de unidades |
| `/UnidadeMedida/form` | UnidadeMedida | Cadastrar unidade |
| `/UnidadeMedida/form/{id}` | UnidadeMedida | Editar unidade |
| `/UnidadeMedida/excluir/{id}` | UnidadeMedida | Excluir unidade |
| `/Categoria` | Categoria | Listagem de categorias |
| `/Categoria/form` | Categoria | Cadastrar categoria |
| `/Categoria/form/{id}` | Categoria | Editar categoria |
| `/Categoria/excluir/{id}` | Categoria | Excluir categoria |

---

## Banco de Dados

O arquivo [script-database.sql](script-database.sql) cria as tabelas abaixo:

| Tabela | Descrição |
|---|---|
| `usuario` | Usuários do sistema com nível de acesso (1=Super Admin, 11=Admin, 21=Usuário) |
| `categoria` | Categorias de produtos |
| `unidademedida` | Unidades de medida (sigla + descrição) |
| `produto` | Produtos vinculados a categoria e unidade de medida |
| `usuariorecuperasenha` | Tokens para recuperação de senha |

---

## Papéis de Usuário

| Nível | Papel |
|---|---|
| 1 | Super Administrador |
| 11 | Administrador |
| 21 | Usuário comum |

---

## Contexto Acadêmico

Projeto desenvolvido ao longo do 1º semestre de 2026 nas aulas práticas do 5º período do curso de **Análise e Desenvolvimento de Sistemas** da **FASM**.

**Histórico de aulas:**

| Data | Conteúdo |
|---|---|
| 09/03/2026 | Início do projeto e estrutura base |
| 11/03/2026 | Roteamento e front controller |
| 18/03/2026 | Padrão MVC e primeiras views |
| 25/03/2026 | Models e acesso ao banco (PDO) |
| 08/04/2026 | Bootstrap e layout |
| 22/04/2026 | Validação de formulários |
| 29/04/2026 | Upload de arquivos e helpers |
| 06/05/2026 | Autenticação, CRUD de Produtos e Unidades de Medida, formulário de contato com envio de e-mail |

---

## Autor

**Aldecir Fonseca** — [aldecirfonseca@hotmail.com](mailto:aldecirfonseca@hotmail.com)

---

## Licença

Projeto de uso acadêmico — FASM 2026.
