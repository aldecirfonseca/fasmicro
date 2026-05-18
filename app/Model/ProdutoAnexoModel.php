<?php

namespace App\Model;

use Core\Library\ModelMain;

class ProdutoAnexoModel extends ModelMain
{
    protected $table = "produtoanexo";

    public function listaPorProduto(int $produto_id): array
    {
        return $this->db
            ->where("produto_id", $produto_id)
            ->orderBy("id")
            ->findAll();
    }

    public function inserirAnexo(int $produto_id, string $nomearquivo): int
    {
        return $this->db->insert([
            'produto_id'  => $produto_id,
            'nomearquivo' => $nomearquivo,
        ]);
    }

    public function excluirAnexo(int $id): bool
    {
        return $this->db->where('id', $id)->delete() > 0;
    }
}
