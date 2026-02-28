<?php

// Definir o time zone default do projeto
defined("DEFAULT_TIME_ZONE") || define("DEFAULT_TIME_ZONE", "America/Sao_Paulo");

// Controller padrão a ser executado
defined("DEFAULT_CONTROLLER") || define("DEFAULT_CONTROLLER", "Home");

// Método padrão do controller a ser executado
defined("DEFAULT_METHOD") || define("DEFAULT_METHOD", "index");

// Controllers autorizados a executar sem autenticação
defined("CONTROLLER_AUTH") || define("CONTROLLER_AUTH", [
    "Home",
    "Login"
]);

