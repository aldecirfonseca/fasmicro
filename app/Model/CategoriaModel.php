<?php

namespace App\Model;

use Core\Library\ModelMain;

class CategoriaModel extends ModelMain
{
    protected $table = "categoria";

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
}