<?php

namespace App\Model;

use Core\Library\ModelMain;

class UsuarioModel extends ModelMain
{
    protected $table = "usuario";

    public $validationRules = [
        "nome" => [
            "label" => "Nome",
            "rules" => "required|min:3|max:50"
        ],
        "email" => [
            "label" => "Email",
            "rules" => "required|email|min:5|max:100"
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

    /**
     * Undocumented function
     *
     * @param string $email
     * @return array
     */
    public function getUsuarioEmail($email)
    {
        return $this->db->where("email", $email)->first();
    }
}