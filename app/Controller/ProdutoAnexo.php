<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Files;
use Core\Library\Redirect;

class ProdutoAnexo extends ControllerMain
{
    private const PASTA = 'produtos';

    public function upload($action, $produto_id)
    {
        $produto_id = (int) $produto_id;

        if ($produto_id <= 0) {
            return Redirect::page('Produto', ['msgError' => 'Produto não encontrado.']);
        }

        if (empty($_FILES['anexos']['name'][0])) {
            return Redirect::page("Produto/form/update/{$produto_id}", ['msgAlerta' => 'Nenhum arquivo selecionado.']);
        }

        $files    = Files::normalizeFiles($_FILES['anexos']);
        $filesLib = new Files();
        $pasta    = self::PASTA . DIRECTORY_SEPARATOR . $produto_id;
        $enviados = $filesLib->upload($files, $pasta);

        // false = erro fatal de diretório (Files já setou msgError)
        if ($enviados === false) {
            return Redirect::page("Produto/form/update/{$produto_id}");
        }

        // array vazio = todos os arquivos falharam na validação (Files já setou msgError)
        if (empty($enviados)) {
            return Redirect::page("Produto/form/update/{$produto_id}");
        }

        foreach ($enviados as $nomeArquivo) {
            $this->model->inserirAnexo($produto_id, $nomeArquivo);
        }

        $qtd = count($enviados);
        return Redirect::page("Produto/form/update/{$produto_id}", ['msgSucesso' => "{$qtd} arquivo(s) adicionado(s) com sucesso."]);
    }

    public function excluir($action, $id)
    {
        $id         = (int) $id;
        $post       = $this->request->getPost();
        $produto_id = (int) ($post['produto_id'] ?? 0);

        $anexo = $this->model->getById($id);

        if (empty($anexo)) {
            return Redirect::page("Produto/form/update/{$produto_id}", ['msgError' => 'Anexo não encontrado.']);
        }

        $filesLib = new Files();
        $pasta    = self::PASTA . DIRECTORY_SEPARATOR . $anexo['produto_id'];
        $filesLib->delete($anexo['nomearquivo'], $pasta);

        $this->model->excluirAnexo($id);

        return Redirect::page("Produto/form/update/{$produto_id}", ['msgSucesso' => 'Anexo excluído com sucesso.']);
    }

    public function download($action, $id)
    {
        $id    = (int) $id;
        $anexo = $this->model->getById($id);

        if (empty($anexo)) {
            http_response_code(404);
            exit('Arquivo não encontrado.');
        }

        $filesLib     = new Files();
        $pasta        = self::PASTA . DIRECTORY_SEPARATOR . $anexo['produto_id'];
        $forceDownload = !empty($this->request->getGet()['dl']);

        if (!$filesLib->exists($anexo['nomearquivo'], $pasta)) {
            http_response_code(404);
            exit('Arquivo não encontrado no servidor.');
        }

        $filesLib->serve($anexo['nomearquivo'], $pasta, $forceDownload);
    }
}
