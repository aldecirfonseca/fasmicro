<?php

namespace App\Controller\Api;

use App\Model\ProdutoModel;
use Core\Library\ApiResponse;

/**
 * CRUD REST de Produto.
 *
 * GET    /api/v1/produtos              → index()   lista paginada
 * GET    /api/v1/produtos/{id}         → show()    detalhe
 * POST   /api/v1/produtos              → store()   criar
 * PUT    /api/v1/produtos/{id}         → update()  substituir
 * PATCH  /api/v1/produtos/{id}         → patch()   atualizar parcialmente
 * DELETE /api/v1/produtos/{id}         → destroy() excluir
 *
 * Todos os endpoints exigem autenticação (Bearer token).
 * Criação, edição e exclusão exigem nível ≤ 11 (Administrador).
 */
class ProdutoApi extends ApiControllerMain
{
    private ProdutoModel $model;

    public function __construct()
    {
        $this->model = new ProdutoModel();
    }

    // ------------------------------------------------------------------
    // GET /api/v1/produtos
    // ------------------------------------------------------------------

    public function index(array $params): void
    {
        $this->auth();

        ['page' => $page, 'perPage' => $perPage, 'offset' => $offset] = $this->pagination(15);

        $orderBy = $this->query('order_by', 'produto.descricao');
        $busca   = $this->query('busca', '');

        // Filtragem por termo de busca (descrição ou complemento)
        if (!empty($busca)) {
            $lista = $this->model->db
                ->select('produto.*, categoria.descricao AS nomeCategoria, unidademedida.sigla AS siglaUnidade')
                ->join('categoria',     'produto.categoria_id = categoria.id')
                ->join('unidademedida', 'produto.unidademedida_id = unidademedida.id')
                ->whereLike('produto.descricao', $busca)
                ->orderBy($orderBy)
                ->limit($perPage, $offset)
                ->findAll();

            $total = $this->model->db
                ->select('COUNT(*) AS total')
                ->join('categoria',     'produto.categoria_id = categoria.id')
                ->join('unidademedida', 'produto.unidademedida_id = unidademedida.id')
                ->whereLike('produto.descricao', $busca)
                ->findCount();
        } else {
            $lista = $this->model->db
                ->select('produto.*, categoria.descricao AS nomeCategoria, unidademedida.sigla AS siglaUnidade')
                ->join('categoria',     'produto.categoria_id = categoria.id')
                ->join('unidademedida', 'produto.unidademedida_id = unidademedida.id')
                ->orderBy($orderBy)
                ->limit($perPage, $offset)
                ->findAll();

            $total = $this->model->db->findCount();
        }

        ApiResponse::success(
            $this->formatLista($lista),
            200,
            $this->pageMeta((int)$total, $page, $perPage)
        );
    }

    // ------------------------------------------------------------------
    // GET /api/v1/produtos/{id}
    // ------------------------------------------------------------------

    public function show(array $params): void
    {
        $this->auth();

        $id = (int)$this->param($params, 'id', 0);

        $produto = $this->buscarOu404($id);

        ApiResponse::success($this->formatItem($produto));
    }

    // ------------------------------------------------------------------
    // POST /api/v1/produtos
    // ------------------------------------------------------------------

    public function store(array $params): void
    {
        $this->authLevel(11); // Administrador ou superior

        $body = $this->body();

        $errors = $this->validate($body, $this->model->validationRules);
        if ($errors) {
            ApiResponse::validationError($errors);
        }

        $dados = $this->filtrarCampos($body);
        unset($dados['id']);

        $novoId = $this->model->db->insert($dados);

        if (!$novoId) {
            ApiResponse::serverError('Falha ao inserir o produto no banco de dados');
        }

        $produto = $this->buscarOu404((int)$novoId);

        ApiResponse::created($this->formatItem($produto));
    }

    // ------------------------------------------------------------------
    // PUT /api/v1/produtos/{id}
    // ------------------------------------------------------------------

    public function update(array $params): void
    {
        $this->authLevel(11);

        $id = (int)$this->param($params, 'id', 0);
        $this->buscarOu404($id);

        $body = $this->body();

        $errors = $this->validate($body, $this->model->validationRules);
        if ($errors) {
            ApiResponse::validationError($errors);
        }

        $dados = $this->filtrarCampos($body);
        unset($dados['id']);

        $this->model->db
            ->where('id', $id)
            ->update($dados);

        $produto = $this->buscarOu404($id);

        ApiResponse::success($this->formatItem($produto));
    }

    // ------------------------------------------------------------------
    // PATCH /api/v1/produtos/{id}
    // ------------------------------------------------------------------

    public function patch(array $params): void
    {
        $this->authLevel(11);

        $id = (int)$this->param($params, 'id', 0);
        $this->buscarOu404($id);

        $body = $this->body();

        if (empty($body)) {
            ApiResponse::badRequest('Nenhum campo enviado para atualização');
        }

        // Valida apenas os campos que vieram no body
        $regrasParciais = array_intersect_key($this->model->validationRules, $body);
        if ($regrasParciais) {
            $errors = $this->validate($body, $regrasParciais);
            if ($errors) {
                ApiResponse::validationError($errors);
            }
        }

        $dados = $this->filtrarCampos($body);
        unset($dados['id']);

        $this->model->db
            ->where('id', $id)
            ->update($dados);

        $produto = $this->buscarOu404($id);

        ApiResponse::success($this->formatItem($produto));
    }

    // ------------------------------------------------------------------
    // DELETE /api/v1/produtos/{id}
    // ------------------------------------------------------------------

    public function destroy(array $params): void
    {
        $this->authLevel(11);

        $id = (int)$this->param($params, 'id', 0);
        $this->buscarOu404($id);

        $deletado = $this->model->db
            ->where('id', $id)
            ->delete();

        if (!$deletado) {
            ApiResponse::serverError('Falha ao excluir o produto');
        }

        ApiResponse::noContent();
    }

    // ------------------------------------------------------------------
    // Helpers privados
    // ------------------------------------------------------------------

    private function buscarOu404(int $id): array
    {
        if ($id <= 0) {
            ApiResponse::notFound('ID inválido');
        }

        $produto = $this->model->db
            ->select('produto.*, categoria.descricao AS nomeCategoria, unidademedida.sigla AS siglaUnidade')
            ->join('categoria',     'produto.categoria_id = categoria.id')
            ->join('unidademedida', 'produto.unidademedida_id = unidademedida.id')
            ->where('produto.id', $id)
            ->first();

        if (empty($produto)) {
            ApiResponse::notFound("Produto #$id não encontrado");
        }

        return $produto;
    }

    /** Campos permitidos para escrita (evita mass assignment) */
    private function filtrarCampos(array $data): array
    {
        $permitidos = [
            'descricao', 'complemento', 'categoria_id', 'unidademedida_id',
            'statusRegistro', 'saldoEstoque', 'precoVenda',
        ];

        return array_intersect_key($data, array_flip($permitidos));
    }

    private function formatItem(array $produto): array
    {
        return [
            'id'               => (int)$produto['id'],
            'descricao'        => $produto['descricao'],
            'complemento'      => $produto['complemento'],
            'precoVenda'       => (float)$produto['precoVenda'],
            'saldoEstoque'     => (float)$produto['saldoEstoque'],
            'statusRegistro'   => (int)$produto['statusRegistro'],
            'categoria_id'     => (int)$produto['categoria_id'],
            'nomeCategoria'    => $produto['nomeCategoria'] ?? null,
            'unidademedida_id' => (int)$produto['unidademedida_id'],
            'siglaUnidade'     => $produto['siglaUnidade'] ?? null,
        ];
    }

    private function formatLista(array $lista): array
    {
        return array_map([$this, 'formatItem'], $lista);
    }
}
