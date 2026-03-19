<?php

namespace Core\Library;

class ControllerMain
{
    protected $controller;
    protected $method;
    protected $action;
    protected $request;
    protected $template;
    
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
        $this->template     = new Template();

        // Carregamento de model default do controller

        // Carregamento de helpers
        // Verificação de permissão dos controllers autorizados sem login
    }

    /**
     * view
     * 
     * Exemplo: $this->view("admin/listaProduto", ['titulo' => 'Lista de Produtos'])
     *
     * @param string $view
     * @param array $data
     * @param string|null $layout
     * @return void
     */
    public function view(string $view, array $data = [], ?string $layout = null)
    {
        $this->template->render($view, $data, $layout);
    }
}