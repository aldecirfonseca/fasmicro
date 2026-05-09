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
}
