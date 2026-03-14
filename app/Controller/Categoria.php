<?php

namespace App\Controller;

use Core\Library\ControllerMain;

class Categoria extends ControllerMain
{
    public function index()
    {
        echo "lista de categoria";
    }

    public function teste()
    {
        echo "Método teste em categoria.";
    }
}