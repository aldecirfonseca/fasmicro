<?php

namespace App\Model;

use Core\Library\ModelMain;

class UnidadeMedidaModel extends ModelMain
{
    protected $table = "unidademedida";

    public $titulo = 'Unidade de Medida';

    public $listaStatus = [
        1 => "Ativo",
        2 => "Inativo"
    ];

    public $validationRules = [
        "sigla" => [
            "label" => "Sigla",
            "rules" => "required|min:1|max:2"
        ],
        "descricao" => [
            "label" => "Descrição",
            "rules" => "required|min:3|max:50"
        ],
        "statusRegistro" => [
            "label" => "Status",
            "rules" => "required|int"
        ]
    ];

    public function lista($orderBy = "unidademedida.descricao")
    {
        return $this->db
            ->select("unidademedida.*, COUNT(produto.id) AS totalProdutos")
            ->join("produto", "produto.unidademedida_id = unidademedida.id", "LEFT")
            ->groupBy("unidademedida.id")
            ->orderBy($orderBy)
            ->findAll();
    }

    public function temProdutosVinculados(int $id): bool
    {
        $rs = $this->db
            ->table("produto")
            ->select("COUNT(id) AS total")
            ->where("unidademedida_id", $id)
            ->first();

        $this->db->table($this->table);

        return (int)($rs['total'] ?? 0) > 0;
    }
}
