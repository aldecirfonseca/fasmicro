<?php

namespace App\Controller;

use Core\Library\ControllerMain;

class Produto extends ControllerMain
{
    public function form($action, $id = 0)
    {
        $modelCategoria     = $this->loadModel('Categoria');
        $modelUnidadeMedida = $this->loadModel('UnidadeMedida');

        return $this->view(
            "admin/formProduto",
            [
                'titulo'            => $this->model->titulo,
                "data"              => $this->model->getById($id),
                "aStatus"           => $this->model->listaStatus,
                "action"            => $this->action,
                "aCategorias"       => $modelCategoria->lista(),
                "aUnidadesMedida"   => $modelUnidadeMedida->lista()
            ]
        );
    }
}
