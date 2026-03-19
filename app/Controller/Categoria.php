<?php

namespace App\Controller;

use Core\Library\ControllerMain;

class Categoria extends ControllerMain
{
    public function index()
    {
       return $this->view(
            "admin/listaCategoria",
            [
                'titulo' => "Lista de Categorias -----",
                "categorias" => [
                    "Tecnologia",
                    "Ciências",
                    "Artes",
                    "Esportes",
                    "Cultura"
                ]
            ]
        );
    }

    public function teste()
    {
        echo "Método teste em categoria.";
    }
}