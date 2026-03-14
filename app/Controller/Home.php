<?php

namespace App\Controller;

use Core\Library\ControllerMain;

class Home extends ControllerMain
{
    public function index()
    {
        echo "Bem vindo ao FasMicro";
        echo "<p>";
        echo '<a href="/categoria/teste">Categoria</a>';
        echo "</p>";
    }

    public function sobrenos()
    {
        echo "Sobre nós";
    }
}