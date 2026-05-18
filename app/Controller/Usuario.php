<?php

namespace App\Controller;

use Core\Library\ControllerMain;

class Usuario extends ControllerMain
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();

        // Permite somente usuário Super Administrador
        $this->validaNivelAcesso(1);
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return $this->view(
            "admin/listaUsuario",
            [
                'titulo'  => $this->model->titulo,
                "lista"   => $this->model->lista(),
                "aStatus" => $this->model->listaStatus,
                "aNiveis" => $this->model->listaNivel,
            ]
        );
    }

    /**
     * form
     *
     * @param string $action
     * @param integer $id
     * @return void
     */
    public function form($action, $id = 0)
    {
        return $this->view(
            "admin/formUsuario",
            [
                'titulo'  => $this->model->titulo,
                "data"    => $this->model->getById($id),
                "aStatus" => $this->model->listaStatus,
                "aNiveis" => $this->model->listaNivel,
                "action"  => $this->action,
            ]
        );
    }
}
