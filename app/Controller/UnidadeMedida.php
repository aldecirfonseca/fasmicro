<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Redirect;

class UnidadeMedida extends ControllerMain
{
    /**
     * delete
     *
     * @return void
     */
    public function delete()
    {
        $post = $this->request->getPost();
        $id   = (int)$post[$this->model->primaryKey];

        if ($this->model->temProdutosVinculados($id)) {
            return Redirect::page(
                $this->controller,
                ['msgError' => "Não é possível excluir: existem produtos vinculados a esta unidade de medida."]
            );
        }

        if ($this->model->delete($post)) {
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
