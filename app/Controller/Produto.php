<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Files;
use Core\Library\Redirect;

class Produto extends ControllerMain
{
    private const PASTA_ANEXOS = 'produtos';

    public function form($action, $id = 0)
    {
        $modelCategoria     = $this->loadModel('Categoria');
        $modelUnidadeMedida = $this->loadModel('UnidadeMedida');
        $modelAnexo         = $this->loadModel('ProdutoAnexo');

        return $this->view(
            "admin/formProduto",
            [
                'titulo'          => $this->model->titulo,
                'data'            => $this->model->getById($id),
                'aStatus'         => $this->model->listaStatus,
                'action'          => $this->action,
                'aCategorias'     => $modelCategoria->lista(),
                'aUnidadesMedida' => $modelUnidadeMedida->lista(),
                'aAnexos'         => $id > 0 ? $modelAnexo->listaPorProduto((int) $id) : [],
                'produto_id'      => (int) $id,
            ]
        );
    }

    public function insert()
    {
        $post       = $this->request->getPost();
        $produto_id = $this->model->insertGetId($post);

        if ($produto_id <= 0) {
            return Redirect::page(
                $this->controller . '/form/' . $this->method . '/0',
                ['msgError' => 'Falha ao inserir produto.']
            );
        }

        $this->processarAnexos($produto_id);

        return Redirect::page(
            $this->controller . '/form/update/' . $produto_id,
            ['msgSucesso' => 'Produto inserido com sucesso.']
        );
    }

    public function update()
    {
        $post = $this->request->getPost();

        if (!$this->model->update($post)) {
            return Redirect::page(
                $this->controller . '/form/' . $this->method . '/' . $post[$this->model->primaryKey],
                ['msgError' => 'Falha ao atualizar produto.']
            );
        }

        $produto_id = (int) $post[$this->model->primaryKey];
        $this->processarAnexos($produto_id);

        return Redirect::page(
            $this->controller,
            ['msgSucesso' => 'Produto atualizado com sucesso.']
        );
    }

    private function processarAnexos(int $produto_id): void
    {
        if (empty($_FILES['anexos']['name'][0])) {
            return;
        }

        $modelAnexo = $this->loadModel('ProdutoAnexo');
        $filesLib   = new Files();
        $files      = Files::normalizeFiles($_FILES['anexos']);
        $pasta      = self::PASTA_ANEXOS . DIRECTORY_SEPARATOR . $produto_id;
        $enviados   = $filesLib->upload($files, $pasta);

        if (!empty($enviados)) {
            foreach ($enviados as $nomeArquivo) {
                $modelAnexo->inserirAnexo($produto_id, $nomeArquivo);
            }
        }
    }
}
