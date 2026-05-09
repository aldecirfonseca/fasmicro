<?php

namespace Core\Library;

use Core\Library\Redirect;

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
        $this->model        = $this->loadModel($this->controller);

        // Carregamento de helpers
        $this->loadHelper(['url', 'data', 'formHelper']);
        
        // Verificação de permissão dos controllers autorizados sem login
    }

    /**
     * loadModel
     *
     * @param string $nomeModel
     * @return void|object
     */
    public function loadModel(string $nomeModel)
    {
        $pathModel = 'App\Model\\' . $nomeModel . "Model";

        if (class_exists($pathModel)) {
            return new $pathModel();
        }
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

    /**
     * Undocumented function
     *
     * @param string|array $nomeHelper
     * @return void
     */
    public function loadHelper($nomeHelper)
    {
        if (gettype($nomeHelper) == "string") {
            $nomeHelper = [$nomeHelper];
        }

        foreach ($nomeHelper as $value) {
            $pathHelpCore = PATHAPP . "core" . DIRECTORY_SEPARATOR . "Helper" . DIRECTORY_SEPARATOR . "{$value}.php";

            if (file_exists($pathHelpCore)) {
                require_once $pathHelpCore;
            } else {
                $pathHelpApp = PATHAPP . "app" . DIRECTORY_SEPARATOR . "Helper" . DIRECTORY_SEPARATOR . "{$value}.php";
                
                if (file_exists($pathHelpApp)) {
                    require_once $pathHelpApp;
                }               
            }
        }
    }

    public function index()
    {
        return $this->view(
            "admin/lista" . $this->controller,
            [
                'titulo'    => $this->model->titulo,
                "lista"     => $this->model->lista(),
                "aStatus"   => $this->model->listaStatus 
            ]
        );
    }


    /**
     * Undocumented function
     *
     * @param string $action
     * @param integer $id
     * @return void
     */
    public function form($action, $id = 0)
    {
        return $this->view(
            "admin/form" . $this->controller,
            [
                'titulo'    => $this->model->titulo,
                "data"      => $this->model->getById($id),
                "aStatus"   => $this->model->listaStatus,
                "action"    => $this->action
            ]
        ); 
    }

    public function insert()
    {
        if ($this->model->insert($_POST)) {
            return Redirect::page(
                        $this->controller, 
                        ['msgSucesso' => "Registro inserido com sucesso."]
                    );
        } else {
            return Redirect::page(
                    $this->controller. "/form/" . $this->method . "/0", 
                    ['msgError' => "Falha ao inserir registro."]
                );
        }
    }

    public function update()
    {
        if ($this->model->update($_POST)) {
            return Redirect::page(
                    $this->controller, 
                    ['msgSucesso' => "Registro atualizado com sucesso."]
                );
        } else {
            return Redirect::page(
                    $this->controller . '/form/' . $this->method . '/' . $_POST[$this->model->primaryKey], 
                    ['msgError' => "Falha ao atualizar registro."]
                );
        }
    }

    public function delete()
    {
        if ($this->model->delete($_POST)) {
            return Redirect::page(
                    $this->controller, 
                    ['msgSucesso' => "Registro excluído com sucesso."]
                );
        } else {
            return Redirect::page(
                    $this->controller, 
                    ['msgError' => "Falha ao excluír o registro."]
                );
        }
    }
}