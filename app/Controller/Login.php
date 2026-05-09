<?php

namespace App\Controller;

use Core\Library\ControllerMain;

class Login extends ControllerMain
{
    public function login()
    {
        return $this->view('/login/login');
    }

    public function signIn()
    {
        $post = $_POST;

        var_dump($post); exit;

        // buscar na tabela de usuários se existe o login para o e-mail

        // validar a senha

        // validar se o usuário está apache_get_version

        // Se tudo ok permito entrar na área administrativa do site

    }
}