<?php

namespace Core\Library;

class ControllerMain
{
    protected $controller;
    protected $method;
    protected $action;
    protected $request;
    
    public $model;

    use RequestTrait;

    /**
     * __construct
     */
    public function __construct()
    {
        $aParametros        = Self::getRotaParametros();
        $this->controller   = $aParametros['controller'];
        $this->method       = $aParametros['method'];
        $this->action       = $aParametros['action'];

        // Carregamento de model default do controller

        // Carregamento de helpers
        // Verificação de permissão dos controllers autorizados sem login
    }
}