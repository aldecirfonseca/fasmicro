<?php

namespace App\Controller;

use App\Model\UsuarioModel;
use Core\Library\ControllerMain;
use Core\Library\Redirect;
use Core\Library\Session;

class Login extends ControllerMain
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new UsuarioModel();
    }

    public function index()
    {
        return $this->view('login/login', ['titulo' => 'Login'], 'login');
    }

    public function signIn()
    {
        $post  = $this->request->getPost();
        $email = $post['email'] ?? '';
        $senha = $post['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            return Redirect::page('login', [
                'msgError' => 'Login ou senha inválido.',
                'inputs'   => ['email' => $email]
            ]);
        }

        $aUser = $this->model->getUsuarioEmail($email);

        // Verifica existência e status ANTES da senha para não vazar informação
        if (count($aUser) === 0 || $aUser['statusRegistro'] == 2) {
            return Redirect::page('login', [
                'msgError' => 'Login ou senha inválido.',
                'inputs'   => ['email' => $email]
            ]);
        }

        if (!password_verify($senha, $aUser['senha'])) {
            return Redirect::page('login', [
                'msgError' => 'Login ou senha inválido.',
                'inputs'   => ['email' => $email]
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

    public function signOut()
    {
        Session::destroy('userId');
        Session::destroy('userNome');
        Session::destroy('userEmail');
        Session::destroy('userNivel');

        return Redirect::page('home');
    }

    // Método exclusivo para configuração inicial — remover ou bloquear em produção
    public function criaSuperUser()
    {
        if (php_sapi_name() !== 'cli' && ($_ENV['APP_ENV'] ?? 'production') === 'production') {
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
            'senha'          => password_hash($senha, PASSWORD_DEFAULT),
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
