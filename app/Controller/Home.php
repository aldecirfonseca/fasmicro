<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Email;
use Core\Library\Redirect;
use Core\Library\Session;
use Core\Library\Validator;
use PHPMailer\PHPMailer\Exception as MailException;

class Home extends ControllerMain
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return $this->view('home', [
            'titulo' => "Bem-vindo ao FasMicro"
        ]);
    }

    /**
     * sobrenos
     *
     * @return void
     */
    public function sobrenos()
    {
        echo "Sobre nós";
    }

    /**
     * contato
     *
     * @return void
     */
    public function contato()
    {
        return $this->view('contato', [
            'titulo' => "Contato"
        ]);
    }

    /**
     * viewErros
     *
     * @return void
     */
    public function viewErros()
    {
        return $this->view("erros");
    }

    /**
     * enviarEmailcontato
     *
     * @return void
     */
    public function enviarEmailcontato()
    {
        $post = $this->request->getPost();

        $hasErrors = Validator::make($post, [
            'nome'     => ['label' => 'Nome',     'rules' => 'required|min:3|max:100'],
            'telefone' => ['label' => 'Telefone', 'rules' => 'required|min:8|max:20'],
            'email'    => ['label' => 'E-mail',   'rules' => 'required|email'],
            'assunto'  => ['label' => 'Assunto',  'rules' => 'required|min:3|max:150'],
            'mensagem' => ['label' => 'Mensagem', 'rules' => 'required|min:10'],
        ]);

        if ($hasErrors) {
            return Redirect::page('Home/contato');
        }

        try {
            Email::enviarEmail(
                emailRemetente: $post['email'],
                nomeRemetente:  $post['nome'],
                assunto:        $post['assunto'],
                corpoEmail:     $this->corpoEmailContato($post),
                destinatario:   'contato@fasmicro.com.br'
            );

            Session::set('msgSucesso', 'E-mail enviado com sucesso!');

        } catch (\InvalidArgumentException $e) {
            Session::set('msgError', $e->getMessage());

        } catch (MailException $e) {
            error_log('[Email] ' . $e->getMessage());
            Session::set('msgError', 'Falha ao enviar e-mail. Tente novamente mais tarde.');
        }

        return Redirect::page('Home/contato');
    }

    /**
     * corpoEmailContato
     *
     * @param array $dados
     * @return string
     */
    private function corpoEmailContato(array $dados): string
    {
        $nome     = htmlspecialchars($dados['nome'],     ENT_QUOTES, 'UTF-8');
        $telefone = htmlspecialchars($dados['telefone'], ENT_QUOTES, 'UTF-8');
        $email    = htmlspecialchars($dados['email'],    ENT_QUOTES, 'UTF-8');
        $assunto  = htmlspecialchars($dados['assunto'],  ENT_QUOTES, 'UTF-8');
        $mensagem = nl2br(htmlspecialchars($dados['mensagem'], ENT_QUOTES, 'UTF-8'));

        return <<<HTML
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Contato pelo site</title>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
                .header { background-color: #0d6efd; color: #ffffff; padding: 24px 32px; }
                .header h1 { margin: 0; font-size: 22px; }
                .body { padding: 32px; color: #333333; }
                .field { margin-bottom: 20px; }
                .field-label { font-size: 12px; text-transform: uppercase; color: #888888; letter-spacing: 0.5px; margin-bottom: 4px; }
                .field-value { font-size: 16px; color: #222222; }
                .mensagem-box { background-color: #f8f9fa; border-left: 4px solid #0d6efd; padding: 16px; border-radius: 4px; font-size: 15px; line-height: 1.6; }
                .footer { background-color: #f0f0f0; text-align: center; padding: 16px; font-size: 12px; color: #999999; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Nova mensagem de contato</h1>
                </div>
                <div class="body">
                    <div class="field">
                        <div class="field-label">Nome</div>
                        <div class="field-value">{$nome}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Telefone</div>
                        <div class="field-value">{$telefone}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">E-mail</div>
                        <div class="field-value"><a href="mailto:{$email}">{$email}</a></div>
                    </div>
                    <div class="field">
                        <div class="field-label">Assunto</div>
                        <div class="field-value">{$assunto}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Mensagem</div>
                        <div class="mensagem-box">{$mensagem}</div>
                    </div>
                </div>
                <div class="footer">
                    Mensagem enviada pelo formulário de contato do site FasMicro
                </div>
            </div>
        </body>
        </html>
        HTML;
    }
}