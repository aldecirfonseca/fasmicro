<?php

namespace App\Controller\Api;

use Core\Library\ApiAuth;
use Core\Library\ApiResponse;
use Core\Library\ModelLoaderTrait;
use Core\Library\Validator;

/**
 * Controller base para todos os endpoints de API REST.
 *
 * Não herda de ControllerMain porque não precisa de sessão, template,
 * helpers de view nem do redirect baseado em sessão.
 *
 * Uso nos controllers filhos:
 *
 *   // Endpoint público
 *   class ProdutoApi extends ApiControllerMain {
 *       public function index(array $params): void {
 *           $lista = $this->model->lista();
 *           ApiResponse::success($lista);
 *       }
 *   }
 *
 *   // Endpoint protegido
 *   class ProdutoApi extends ApiControllerMain {
 *       public function store(array $params): void {
 *           $auth = $this->auth();           // exige token válido
 *           $body = $this->body();           // dados do request
 *           ...
 *       }
 *   }
 */
abstract class ApiControllerMain
{
    // Compartilha a resolução App\Model\{Name}Model com ControllerMain.
    // O alias permite sobrescrever o método mantendo acesso à implementação base.
    use ModelLoaderTrait {
        loadModel as private resolveModel;
    }

    protected ?array $authPayload = null;

    // ------------------------------------------------------------------
    // Autenticação
    // ------------------------------------------------------------------

    /**
     * Exige token válido. Encerra com 401 se ausente ou inválido.
     * Retorna o payload do token para uso no controller.
     */
    protected function auth(): array
    {
        $this->authPayload = ApiAuth::required();
        return $this->authPayload;
    }

    /**
     * Exige token válido E nível de acesso mínimo. Encerra com 403 se insuficiente.
     */
    protected function authLevel(int $nivelMinimo): array
    {
        $this->authPayload = ApiAuth::requiredWithLevel($nivelMinimo);
        return $this->authPayload;
    }

    // ------------------------------------------------------------------
    // Request
    // ------------------------------------------------------------------

    /**
     * Retorna o body da requisição (JSON ou form-urlencoded), já sanitizado.
     *
     * @return array<string, mixed>
     */
    protected function body(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (str_contains($contentType, 'application/json')) {
            $raw  = file_get_contents('php://input');
            $data = json_decode($raw, true);
            return is_array($data) ? $this->sanitize($data) : [];
        }

        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        if (in_array($method, ['PUT', 'PATCH'], true) && empty($_POST)) {
            parse_str(file_get_contents('php://input'), $data);
            return $this->sanitize($data);
        }

        return $this->sanitize($_POST);
    }

    /**
     * Retorna parâmetros de rota nomeados (ex: {id} da URL).
     * Os params já chegam via o array passado pelo ApiRoutes::dispatch().
     */
    protected function param(array $params, string $key, mixed $default = null): mixed
    {
        return $params[$key] ?? $_GET[$key] ?? $default;
    }

    /**
     * Retorna parâmetros de query string (?page=1&per_page=15).
     */
    protected function query(string $key, mixed $default = null): mixed
    {
        return isset($_GET[$key]) ? trim((string)$_GET[$key]) : $default;
    }

    // ------------------------------------------------------------------
    // Helpers de paginação
    // ------------------------------------------------------------------

    /**
     * Monta o array de meta para respostas paginadas.
     */
    protected function pageMeta(int $total, int $page, int $perPage): array
    {
        return [
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int)ceil($total / max($perPage, 1)),
        ];
    }

    /**
     * Retorna page e per_page da query string com defaults seguros.
     */
    protected function pagination(int $defaultPerPage = 15): array
    {
        $page    = max(1, (int)$this->query('page', 1));
        $perPage = max(1, min(100, (int)$this->query('per_page', $defaultPerPage)));
        $offset  = ($page - 1) * $perPage;

        return compact('page', 'perPage', 'offset');
    }

    // ------------------------------------------------------------------
    // Carregamento de model
    // ------------------------------------------------------------------

    /**
     * Instancia um model da aplicação.
     * Exemplo: $this->loadModel('Produto') → App\Model\ProdutoModel
     *
     * Encerra com 500 se o model não existir (comportamento adequado para API).
     */
    public function loadModel(string $name): object
    {
        $model = $this->resolveModel($name);

        if ($model === null) {
            ApiResponse::serverError("Model '{$name}Model' não encontrado");
        }

        return $model;
    }

    // ------------------------------------------------------------------
    // Validação
    // ------------------------------------------------------------------

    /**
     * Delega para Validator::check() — validação pura sem sessão.
     * Retorna null se válido, ou array ['campo' => 'mensagem'] se inválido.
     *
     * Uso:
     *   $errors = $this->validate($body, $this->model->validationRules);
     *   if ($errors) ApiResponse::validationError($errors);
     */
    protected function validate(array $data, array $rules): ?array
    {
        return Validator::check($data, $rules);
    }

    // ------------------------------------------------------------------
    // Sanitização
    // ------------------------------------------------------------------

    private function sanitize(array $data): array
    {
        return array_map(function ($value) {
            if (is_array($value)) {
                return $this->sanitize($value);
            }
            return is_string($value) ? trim($value) : $value;
        }, $data);
    }
}
