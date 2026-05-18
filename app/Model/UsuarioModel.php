<?php

namespace App\Model;

use App\Config\PasswordConfig;
use Core\Library\ModelMain;
use Core\Library\Session;
use Core\Library\Validator;

class UsuarioModel extends ModelMain
{
    protected $table = "usuario";

    public $titulo = 'Usuário';

    public $listaStatus = [
        1 => 'Ativo',
        2 => 'Inativo',
        3 => 'Bloqueado'
    ];

    public $listaNivel = [
        1  => 'Super Administrador',
        11 => 'Administrador',
        21 => 'Usuário'
    ];

    public $validationRules = [
        "nome" => [
            "label" => "Nome",
            "rules" => "required|min:3|max:60"
        ],
        "email" => [
            "label" => "E-mail",
            "rules" => "required|email|min:5|max:150"
        ],
        "nivel" => [
            "label" => "Nível",
            "rules" => "required|int"
        ],
        "statusRegistro" => [
            "label" => "Status",
            "rules" => "required|int"
        ]
    ];

    public function lista($orderBy = "nome")
    {
        return $this->db->orderBy($orderBy)->findAll();
    }

    public function insert($dados)
    {
        $rules = array_merge($this->validationRules, [
            'senha' => ['label' => 'Senha', 'rules' => 'required']
        ]);

        if (Validator::make($dados, $rules)) {
            return false;
        }

        if (!$this->validatePassword($dados['senha'], $dados['confirmarSenha'] ?? '', $dados)) {
            return false;
        }

        $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        unset($dados[$this->primaryKey], $dados['confirmarSenha']);

        return $this->db->insert($dados) > 0;
    }

    public function update($dados)
    {
        $rules = $this->validationRules;

        if (!empty($dados['senha'])) {
            if (Validator::make($dados, $rules)) {
                return false;
            }

            if (!$this->validatePassword($dados['senha'], $dados['confirmarSenha'] ?? '', $dados)) {
                return false;
            }

            $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        } else {
            unset($dados['senha']);

            if (Validator::make($dados, $rules)) {
                return false;
            }
        }

        unset($dados['confirmarSenha']);

        return $this->db
            ->where($this->primaryKey, $dados[$this->primaryKey])
            ->update($dados) > 0;
    }

    public function getUsuarioEmail(string $email)
    {
        return $this->db->where("email", $email)->first();
    }

    public function trocarSenha(array $dados): bool
    {
        $rules = [
            'senhaAtual'       => ['label' => 'Senha Atual',          'rules' => 'required'],
            'novaSenha'        => ['label' => 'Nova Senha',           'rules' => 'required'],
            'confirmacaoSenha' => ['label' => 'Confirmação de Senha', 'rules' => 'required'],
        ];

        if (Validator::make($dados, $rules)) {
            return false;
        }

        $usuario = $this->db->where($this->primaryKey, $dados['id'])->first();

        if (empty($usuario) || !password_verify($dados['senhaAtual'], $usuario['senha'])) {
            Session::set('formErrors', ['senhaAtual' => 'A senha atual está incorreta.']);
            Session::set('formInputs', $dados);
            return false;
        }

        if (!$this->validatePassword($dados['novaSenha'], $dados['confirmacaoSenha'], $dados)) {
            return false;
        }

        return $this->db
            ->where($this->primaryKey, $dados['id'])
            ->update(['senha' => password_hash($dados['novaSenha'], PASSWORD_DEFAULT)]) > 0;
    }

    public function redefinirSenha(int $usuarioId, string $novaSenha, string $confirmacaoSenha, string $chave): bool
    {
        $rules = [
            'novaSenha'        => ['label' => 'Nova Senha',           'rules' => 'required'],
            'confirmacaoSenha' => ['label' => 'Confirmação de Senha', 'rules' => 'required'],
        ];

        // Inclui 'chave' no array para que o Validator a preserve em formInputs,
        // permitindo repopular o campo hidden na view em caso de falha de validação
        $dados = [
            'chave'            => $chave,
            'novaSenha'        => $novaSenha,
            'confirmacaoSenha' => $confirmacaoSenha,
        ];

        if (Validator::make($dados, $rules)) {
            return false;
        }

        if (!$this->validatePassword($novaSenha, $confirmacaoSenha, ['chave' => $chave])) {
            return false;
        }

        return $this->db
            ->where($this->primaryKey, $usuarioId)
            ->update(['senha' => password_hash($novaSenha, PASSWORD_DEFAULT)]) > 0;
    }

    /**
     * Valida complexidade da senha e conferência com o campo de confirmação.
     * Em caso de falha, armazena os erros na sessão e retorna false.
     */
    private function validatePassword(string $senha, string $confirmarSenha, array $formData): bool
    {
        $config = PasswordConfig::getConfig();
        $errors = [];

        if ($senha !== $confirmarSenha) {
            $errors['confirmarSenha'] = 'As senhas não conferem.';
        } else {
            $falhas = [];

            if ($config['minLength'] > 0 && strlen($senha) < $config['minLength']) {
                $falhas[] = "mínimo {$config['minLength']} caracteres";
            }
            if ($config['requireUppercase'] && !preg_match('/[A-Z]/', $senha)) {
                $falhas[] = 'letra maiúscula';
            }
            if ($config['requireLowercase'] && !preg_match('/[a-z]/', $senha)) {
                $falhas[] = 'letra minúscula';
            }
            if ($config['requireNumber'] && !preg_match('/[0-9]/', $senha)) {
                $falhas[] = 'número';
            }
            if ($config['requireSpecial'] && !preg_match('/[^A-Za-z0-9]/', $senha)) {
                $falhas[] = 'caractere especial';
            }

            if (!empty($falhas)) {
                $errors['senha'] = 'A senha deve conter: ' . implode(', ', $falhas) . '.';
            }
        }

        if (!empty($errors)) {
            // Não armazena as senhas na sessão por segurança
            $dadosSeguros = $formData;
            unset($dadosSeguros['senha'], $dadosSeguros['confirmarSenha'],
                  $dadosSeguros['novaSenha'], $dadosSeguros['confirmacaoSenha']);

            Session::set('formErrors', $errors);
            Session::set('formInputs', $dadosSeguros);
            return false;
        }

        return true;
    }
}
