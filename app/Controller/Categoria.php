<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Redirect;

class Categoria extends ControllerMain
{
    public function index()
    {
       return $this->view(
            "admin/listaCategoria",
            [
                'titulo' => "Lista de Categorias",
                "categorias" => $this->model->lista(),
                "aStatus" => $this->model->listaStatus 
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
            "admin/formCategoria",
            [
                'titulo'    => "Categoria",
                "data"      => $this->model->getById($id),
                "aStatus"   => $this->model->listaStatus,
                "action"    => $this->action
            ]
        ); 
    }

    public function insert()
    {
        if ($this->model->insert($_POST)) {
            return Redirect::page("categoria", ['msgSucesso' => "Registro inserido com sucesso."]);
        } else {
            return Redirect::page("categoria", ['msgError' => "Falha ao inserir registro."]);
        }
    }

    public function update()
    {
        if ($this->model->update($_POST)) {
            return Redirect::page("categoria", ['msgSucesso' => "Registro atualizado com sucesso."]);
        } else {
            return Redirect::page("categoria", ['msgError' => "Falha ao atualizar registro."]);
        }
    }

    public function delete()
    {
        if ($this->model->delete($_POST)) {
            return Redirect::page("categoria", ['msgSucesso' => "Registro excluído com sucesso."]);
        } else {
            return Redirect::page("categoria", ['msgError' => "Falha ao excluír o registro."]);
        }
    }

}