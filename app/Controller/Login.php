<?php

namespace App\Controller;

use App\Model\RecuperaSenhaModel;
use App\Model\UsuarioModel;
use Core\Library\ControllerMain;
use Core\Library\Email;
use Core\Library\Redirect;
use Core\Library\Session;

class Login extends ControllerMain
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new UsuarioModel();
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return $this->view('login/login', ['titulo' => 'Login'], 'login');
    }

    /**
     * signIn
     *
     * @return void
     */
    public function signIn()
    {
        $post  = $this->request->getPost();
        $email = $post['email'] ?? '';
        $senha = $post['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            return Redirect::page('login', [
                'msgError' => 'Login ou senha inválido.',
                'formInputs'   => ['email' => $email]
            ]);
        }

        $aUser = $this->model->getUsuarioEmail($email);

        // Verifica existência e status ANTES da senha para não vazar informação
        if (count($aUser) === 0 || $aUser['statusRegistro'] == 2) {
            return Redirect::page('login', [
                'msgError' => 'Login ou senha inválido.',
                'formInputs'   => ['email' => $email]
            ]);
        }

        if (!password_verify($senha, $aUser['senha'])) {
            return Redirect::page('login', [
                'msgError' => 'Login ou senha inválido.',
                'formInputs'   => ['email' => $email]
            ]);
        }

        // Previne session fixation regenerando o ID após autenticação bem-sucedida
        session_regenerate_id(true);

        Session::set('userId',    $aUser['id']);
        Session::set('userNome',  $aUser['nome']);
        Session::set('userEmail', $aUser['email']);
        Session::set('userNivel', $aUser['nivel']);

        return Redirect::page('home');
    }

    /**
     * signOut
     *
     * @return void
     */
    public function signOut()
    {
        Session::destroy('userId');
        Session::destroy('userNome');
        Session::destroy('userEmail');
        Session::destroy('userNivel');

        return Redirect::page('home');
    }

    /**
     * trocarSenha
     *
     * @return void
     */
    public function trocarSenha()
    {
        if (!Session::get('userId')) {
            return Redirect::page('Login', ['msgError' => 'Faça login para continuar.']);
        }

        return $this->view('login/trocarSenha', ['titulo' => 'Trocar Senha']);
    }

    /**
     * atualizarSenha
     *
     * @return void
     */
    public function atualizarSenha()
    {
        if (!Session::get('userId')) {
            return Redirect::page('Login', ['msgError' => 'Faça login para continuar.']);
        }

        $post = $this->request->getPost();

        $dados = [
            'id'               => Session::get('userId'),
            'senhaAtual'       => $post['senhaAtual']       ?? '',
            'novaSenha'        => $post['novaSenha']        ?? '',
            'confirmacaoSenha' => $post['confirmacaoSenha'] ?? '',
        ];

        if (!$this->model->trocarSenha($dados)) {
            return Redirect::page('Login/trocarSenha', ['msgError' => 'Não foi possível atualizar a senha.']);
        }

        return Redirect::page('Login/trocarSenha', ['msgSucesso' => 'Senha alterada com sucesso.']);
    }

    /**
     * esqueciASenha
     *
     * @return void
     */
    public function esqueciASenha()
    {
        return $this->view('login/esqueciASenha', ['titulo' => 'Recuperar Senha'], 'login');
    }

    /**
     * enviarLinkRecuperacao
     *
     * @return void
     */
    public function enviarLinkRecuperacao()
    {
        $email = $this->request->getPost()['email'] ?? '';

        // Mesma mensagem independente de o e-mail existir (evita enumeração)
        $msgSucesso = 'Se o e-mail informado estiver cadastrado, você receberá um link de recuperação em breve.';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return Redirect::page('Login/esqueciASenha', [
                'msgError'   => 'Informe um e-mail válido.',
                'formInputs' => ['email' => $email]
            ]);
        }

        $usuario = $this->model->getUsuarioEmail($email);

        if (!empty($usuario) && $usuario['statusRegistro'] == 1) {
            try {
                $recuperaSenhaModel = new RecuperaSenhaModel();
                $token = $recuperaSenhaModel->gerarToken((int) $usuario['id']);

                $link  = baseUrl() . 'Login/redefinirSenha/' . $token;
                $corpo = $this->emailRecuperacao(htmlspecialchars($usuario['nome']), $link);

                try {
                    Email::enviarEmail(
                        $_ENV['MAIL.USER'],
                        $_ENV['MAIL.NOME'],
                        'Recuperação de Senha - FasMicro',
                        $corpo,
                        $email
                    );
                } catch (\Throwable $e) {
                    // Token já foi gerado — loga falha no envio sem expor ao usuário
                    error_log('[FasMicro] Falha ao enviar e-mail de recuperação para ' . $email . ': ' . $e->getMessage());
                }
            } catch (\Throwable $_e) {
                // Não expõe detalhes para evitar enumeração de e-mails
                error_log('[FasMicro] Falha ao gerar token de recuperação: ' . $_e->getMessage());
            }
        }

        return Redirect::page('Login/esqueciASenha', ['msgSucesso' => $msgSucesso]);
    }

    /**
     * redefinirSenha
     *
     * @param string $token
     * @param integer $_id
     * @return void
     */
    public function redefinirSenha(string $token = '', int $_id = 0)
    {
        if (empty($token)) {
            return Redirect::page('Login', ['msgError' => 'Link de recuperação inválido.']);
        }

        $recuperaSenhaModel = new RecuperaSenhaModel();
        $registro = $recuperaSenhaModel->getTokenAtivo($token);

        if (empty($registro)) {
            return Redirect::page('Login', ['msgError' => 'Link de recuperação expirado ou inválido.']);
        }

        return $this->view('login/redefinirSenha', [
            'titulo' => 'Redefinir Senha',
            'data'   => ['chave' => $token]
        ], 'login');
    }

    /**
     * salvarNovaSenha
     *
     * @return void
     */
    public function salvarNovaSenha()
    {
        $post  = $this->request->getPost();
        $chave = $post['chave']            ?? '';
        $nova  = $post['novaSenha']        ?? '';
        $conf  = $post['confirmacaoSenha'] ?? '';

        if (empty($chave)) {
            return Redirect::page('Login', ['msgError' => 'Link de recuperação inválido.']);
        }

        $recuperaSenhaModel = new RecuperaSenhaModel();
        $registro = $recuperaSenhaModel->getTokenAtivo($chave);

        if (empty($registro)) {
            return Redirect::page('Login', ['msgError' => 'Link de recuperação expirado ou inválido.']);
        }

        if (!$this->model->redefinirSenha((int) $registro['usuario_id'], $nova, $conf, $chave)) {
            return Redirect::page('Login/redefinirSenha/' . $chave, [
                'msgError' => 'Não foi possível atualizar a senha.'
            ]);
        }

        $recuperaSenhaModel->invalidarToken($chave);

        return Redirect::page('Login', ['msgSucesso' => 'Senha redefinida com sucesso. Faça login com sua nova senha.']);
    }

    /**
     * emailRecuperacao
     *
     * @param string $nome
     * @param string $link
     * @return string
     */
    private function emailRecuperacao(string $nome, string $link): string
    {
        return <<<HTML
        <div style="font-family:Arial,sans-serif;max-width:520px;margin:auto;padding:28px;border:1px solid #e0e0e0;border-radius:10px;">
            <h2 style="color:#0d6efd;margin-bottom:4px;">Recuperação de Senha</h2>
            <hr style="border:none;border-top:1px solid #e0e0e0;margin:12px 0 20px;">
            <p>Olá, <strong>{$nome}</strong>!</p>
            <p>Recebemos uma solicitação para redefinir a senha da sua conta no <strong>FasMicro</strong>.</p>
            <p>Clique no botão abaixo para criar uma nova senha. Este link é válido por <strong>1 hora</strong>.</p>
            <div style="text-align:center;margin:28px 0;">
                <a href="{$link}"
                   style="background:#0d6efd;color:#ffffff;padding:12px 32px;border-radius:6px;text-decoration:none;font-weight:bold;font-size:15px;">
                    Redefinir Minha Senha
                </a>
            </div>
            <p style="font-size:13px;color:#555;">
                Se o botão não funcionar, copie e cole o link abaixo no seu navegador:<br>
                <a href="{$link}" style="color:#0d6efd;word-break:break-all;">{$link}</a>
            </p>
            <p style="font-size:13px;color:#888;">
                Se você não solicitou a recuperação de senha, ignore este e-mail. Sua senha permanecerá inalterada.
            </p>
            <hr style="border:none;border-top:1px solid #e0e0e0;margin:20px 0 12px;">
            <p style="font-size:12px;color:#aaa;text-align:center;">FasMicro — Framework PHP Minimalista e Poderoso</p>
        </div>
        HTML;
    }

    // Método exclusivo para configuração inicial — remover ou bloquear em produção
    public function criaSuperUser()
    {
        if (php_sapi_name() !== 'cli' && ($_ENV['ENVIRIONMENT'] ?? 'production') === 'production') {
            return Redirect::page('login', ['msgError' => 'Acesso não autorizado.']);
        }

        $email = $_ENV['SUPERUSER_EMAIL'] ?? null;
        $senha = $_ENV['SUPERUSER_SENHA'] ?? null;
        $nome  = $_ENV['SUPERUSER_NOME']  ?? null;

        if (!$email || !$senha || !$nome) {
            return Redirect::page('login', ['msgError' => 'Credenciais do superusuário não configuradas.']);
        }

        $dados = [
            'nivel'          => 1,
            'nome'           => $nome,
            'email'          => $email,
            'senha'          => $senha,
            'confirmarSenha' => $senha,
            'statusRegistro' => 1
        ];

        $aSuperUser = $this->model->getUsuarioEmail($dados['email']);

        if (count($aSuperUser) > 0) {
            return Redirect::page('login', ['msgError' => 'Login já existe.']);
        }

        if ($this->model->insert($dados)) {
            return Redirect::page('login', ['msgSucesso' => 'Login criado com sucesso.']);
        }

        return Redirect::page('login');
    }
}
