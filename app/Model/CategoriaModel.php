<?php

namespace App\Model;

use Core\Library\ModelMain;

class CategoriaModel extends ModelMain
{
    protected $table = "categoria";

    public $titulo = 'Categoria';

    public $listaStatus = [
        1 => "Ativo",
        2 => "Inativo"
    ];

    public $validationRules = [
        "descricao" => [
            "label" => "Descrição",
            "rules" => "required|min:3|max:50"
        ],
        "statusRegistro" => [
            "label" => "Status",
            "rules" => "required|int"
        ]
    ];

    public function lista($orderBy = "categoria.descricao")
    {
        return $this->db
            ->select("categoria.*, COUNT(produto.id) AS totalProdutos")
            ->join("produto", "produto.categoria_id = categoria.id", "LEFT")
            ->groupBy("categoria.id")
            ->orderBy($orderBy)
            ->findAll();
    }

    public function temProdutosVinculados(int $id): bool
    {
        $rs = $this->db
            ->table("produto")
            ->select("COUNT(id) AS total")
            ->where("categoria_id", $id)
            ->first();

        $this->db->table($this->table);

        return (int)($rs['total'] ?? 0) > 0;
    }
}