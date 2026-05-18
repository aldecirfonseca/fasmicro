<?php

namespace App\Model;

use Core\Library\ModelMain;

class RecuperaSenhaModel extends ModelMain
{
    protected $table = "usuariorecuperasenha";

    public function gerarToken(int $usuarioId): string
    {
        $token = bin2hex(random_bytes(32));

        // Insere primeiro — se falhar, lança exceção para o controller
        $novoId = (int) $this->db->insert([
            'usuario_id'     => $usuarioId,
            'chave'          => $token,
            'statusRegistro' => 1,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        if ($novoId === 0) {
            throw new \RuntimeException('Falha ao persistir token de recuperação de senha.');
        }

        // Invalida tokens ativos anteriores do mesmo usuário (exceto o recém-criado)
        $this->db
            ->where('usuario_id', $usuarioId)
            ->where('statusRegistro', 1)
            ->where('id <>', $novoId)
            ->update(['statusRegistro' => 2, 'updated_at' => date('Y-m-d H:i:s')]);

        return $token;
    }

    public function getTokenAtivo(string $chave): array
    {
        $registro = $this->db
            ->where('chave', $chave)
            ->where('statusRegistro', 1)
            ->first();

        if (empty($registro)) {
            return [];
        }

        // Token expira em 1 hora
        if (time() > strtotime($registro['created_at']) + 3600) {
            return [];
        }

        return $registro;
    }

    public function invalidarToken(string $chave): bool
    {
        return $this->db
            ->where('chave', $chave)
            ->update(['statusRegistro' => 2, 'updated_at' => date('Y-m-d H:i:s')]) > 0;
    }
}
