<?php

namespace App\Controller\Api;

use App\Model\UsuarioModel;
use Core\Library\ApiResponse;
use Core\Library\Jwt;

/**
 * Endpoints de autenticação da API.
 *
 * POST /api/v1/auth/login
 *   Body: { "email": "...", "senha": "..." }
 *   Retorna: { access_token, refresh_token, expires_in, token_type, user }
 *
 * POST /api/v1/auth/refresh
 *   Body: { "refresh_token": "..." }
 *   Retorna: { access_token, expires_in, token_type }
 *
 * POST /api/v1/auth/me
 *   Header: Authorization: Bearer <access_token>
 *   Retorna: dados do usuário autenticado
 */
class AuthApiController extends ApiControllerMain
{
    private UsuarioModel $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * POST /api/v1/auth/login
     */
    public function login(array $params): void
    {
        $body  = $this->body();
        $email = $body['email'] ?? '';
        $senha = $body['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            ApiResponse::badRequest('Os campos email e senha são obrigatórios');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            ApiResponse::badRequest('Formato de e-mail inválido');
        }

        $usuario = $this->usuarioModel->getUsuarioEmail($email);

        // Mensagem genérica para não vazar se o e-mail existe
        if (empty($usuario) || (int)($usuario['statusRegistro'] ?? 0) !== 1) {
            ApiResponse::unauthorized('Credenciais inválidas');
        }

        if (!password_verify($senha, $usuario['senha'])) {
            ApiResponse::unauthorized('Credenciais inválidas');
        }

        $expireSeconds = (int)(($_ENV['JWT_EXPIRE'] ?? 0) ?: 3600);

        $accessToken = Jwt::encode([
            'sub'   => (int)$usuario['id'],
            'nome'  => $usuario['nome'],
            'email' => $usuario['email'],
            'nivel' => (int)$usuario['nivel'],
        ], $expireSeconds);

        $refreshToken = Jwt::encodeRefresh((int)$usuario['id']);

        ApiResponse::success([
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type'    => 'Bearer',
            'expires_in'    => $expireSeconds,
            'user'          => [
                'id'    => (int)$usuario['id'],
                'nome'  => $usuario['nome'],
                'email' => $usuario['email'],
                'nivel' => (int)$usuario['nivel'],
            ],
        ]);
    }

    /**
     * POST /api/v1/auth/refresh
     */
    public function refresh(array $params): void
    {
        $body         = $this->body();
        $refreshToken = $body['refresh_token'] ?? '';

        if (empty($refreshToken)) {
            ApiResponse::badRequest('O campo refresh_token é obrigatório');
        }

        try {
            $payload = Jwt::decode($refreshToken);
        } catch (\RuntimeException $e) {
            ApiResponse::unauthorized('Refresh token inválido ou expirado: ' . $e->getMessage());
        }

        if (($payload['type'] ?? '') !== 'refresh') {
            ApiResponse::unauthorized('Token informado não é um refresh token');
        }

        $userId  = (int)($payload['sub'] ?? 0);
        $usuario = $this->usuarioModel->getById($userId);

        if (empty($usuario) || (int)($usuario['statusRegistro'] ?? 0) !== 1) {
            ApiResponse::unauthorized('Usuário não encontrado ou inativo');
        }

        $expireSeconds = (int)(($_ENV['JWT_EXPIRE'] ?? 0) ?: 3600);

        $newAccessToken = Jwt::encode([
            'sub'   => (int)$usuario['id'],
            'nome'  => $usuario['nome'],
            'email' => $usuario['email'],
            'nivel' => (int)$usuario['nivel'],
        ], $expireSeconds);

        ApiResponse::success([
            'access_token' => $newAccessToken,
            'token_type'   => 'Bearer',
            'expires_in'   => $expireSeconds,
        ]);
    }

    /**
     * GET /api/v1/auth/me
     */
    public function me(array $params): void
    {
        $authPayload = $this->auth();

        $usuario = $this->usuarioModel->getById((int)$authPayload['sub']);

        if (empty($usuario)) {
            ApiResponse::notFound('Usuário não encontrado');
        }

        ApiResponse::success([
            'id'    => (int)$usuario['id'],
            'nome'  => $usuario['nome'],
            'email' => $usuario['email'],
            'nivel' => (int)$usuario['nivel'],
        ]);
    }
}
