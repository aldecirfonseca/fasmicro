# FasMicro

Micro Framework PHP desenvolvido como projeto acadêmico no 5º período — turma 2026 — **FASM (Faculdade Santa Marcelina)**.

---

## Sobre o Projeto

O FasMicro é um micro-framework MVC construído do zero em PHP puro, sem depender de frameworks externos. O objetivo é demonstrar, na prática, os fundamentos de desenvolvimento web back-end: roteamento, padrão MVC, acesso a banco de dados via PDO, autenticação de usuários, validação de formulários, envio de e-mail e API REST com autenticação JWT.

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

## Funcionalidades

- Autenticação com login/logout e senha criptografada
- Controle de acesso por papéis (Super Admin, Admin, Usuário)
- CRUD de Produtos, Categorias e Unidades de Medida
- Formulário de contato via SMTP (Gmail / PHPMailer)
- Roteamento customizado com URLs amigáveis
- Validação de formulários com regras configuráveis
- Template engine com layouts reutilizáveis
- Upload de arquivos (imagens, documentos — limite 5 MB)
- API REST com autenticação JWT, paginação e CORS

---

## Instalação Rápida

```bash
# Clone e instale dependências
git clone <url-do-repositorio>
cd fasmicro
composer install

# Configure o ambiente
cp exemplo.env .env   # edite com suas credenciais

# Crie o banco de dados
mysql -u root -p < script-database.sql
```

Aponte o `DocumentRoot` do Apache para a pasta `public/` e habilite `mod_rewrite`.

---

## Documentação

Consulte a pasta **[docs/](docs/)** para documentação detalhada:

| Documento | Descrição |
|---|---|
| [Instalação e Configuração](docs/getting-started.md) | Pré-requisitos, `.env` completo e passos de setup |
| [Arquitetura](docs/architecture.md) | Estrutura de diretórios e classes do núcleo |
| [Rotas Web](docs/routes.md) | Rotas públicas e administrativas |
| [Banco de Dados](docs/database.md) | Tabelas, relacionamentos e papéis de usuário |
| [API REST](docs/api/overview.md) | Visão geral, autenticação, endpoints e exemplos |

> Para documentação completa da API com exemplos no Postman, Python, PHP e React Native, veja [FasMicro_API_Documentacao.docx](FasMicro_API_Documentacao.docx).

---

## Contexto Acadêmico

Projeto desenvolvido ao longo do 1º semestre de 2026 nas aulas práticas do 5º período do curso de **Análise e Desenvolvimento de Sistemas** — FASM.

| Data | Conteúdo |
|---|---|
| 09/03/2026 | Início do projeto e estrutura base |
| 11/03/2026 | Roteamento e front controller |
| 18/03/2026 | Padrão MVC e primeiras views |
| 25/03/2026 | Models e acesso ao banco (PDO) |
| 08/04/2026 | Bootstrap e layout |
| 22/04/2026 | Validação de formulários |
| 29/04/2026 | Upload de arquivos e helpers |
| 06/05/2026 | Autenticação, CRUD e envio de e-mail |
| 17/05/2026 | API REST com JWT, CORS e controle de acesso por nível |

---

## Autor

**Aldecir Fonseca** — [aldecirfonseca@hotmail.com](mailto:aldecirfonseca@hotmail.com)

---

## Licença

Projeto de uso acadêmico — FASM 2026.
