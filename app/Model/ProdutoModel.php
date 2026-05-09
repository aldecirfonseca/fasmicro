<?php

namespace App\Model;

use Core\Library\ModelMain;

class ProdutoModel extends ModelMain
{
    protected $table = "produto";

    public $titulo = 'Produto';

    public $listaStatus = [
        1 => "Ativo",
        2 => "Inativo"
    ];

    public $validationRules = [
        "descricao" => [
            "label" => "Descrição",
            "rules" => "required|min:3|max:60"
        ],
        "complemento" => [
            "label" => "Complemento",
            "rules" => "required|min:3"
        ],
        "categoria_id" => [
            "label" => "Categoria",
            "rules" => "required|int"
        ],
        "unidademedida_id" => [
            "label" => "Unidade de Medida",
            "rules" => "required|int"
        ],
        "statusRegistro" => [
            "label" => "Status",
            "rules" => "required|int"
        ],
        "saldoEstoque" => [
            "label" => "Saldo Estoque",
            "rules" => "required"
        ],
        "precoVenda" => [
            "label" => "Preço de Venda",
            "rules" => "required"
        ]
    ];

    public function lista($orderBy = "produto.descricao")
    {
        return $this->db
            ->select("produto.*, categoria.descricao AS nomeCategoria, unidademedida.sigla AS siglaUnidade")
            ->join("categoria", "produto.categoria_id = categoria.id")
            ->join("unidademedida", "produto.unidademedida_id = unidademedida.id")
            ->orderBy($orderBy)
            ->findAll();
    }

    public function getById($id)
    {
        if ($id == 0) {
            return [];
        }

        return $this->db
            ->where("id", $id)
            ->first();
    }
}
