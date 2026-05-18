<?php

namespace Core\Library;

class Request
{
    protected $param;

    use RequestTrait;

    public function __construct()
    {
        $this->param = Self::getRotaParametros();
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getController()
    {
        return $this->param['controller'];
    }

    /**
     * getMetodo
     *
     * @return string
     */
    public function getMetodo()
    {
        return $this->param['method'];
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getAction() 
    {
        return $this->param['action'];
    }

    /**
     * @return array<string, mixed>
     */
    public function getGet(): array
    {
        return $this->trimRecursive($this->param['get']);
    }

    /**
     * @return array<string, mixed>
     */
    public function getPost(): array
    {
        return $this->trimRecursive($this->param['post']);
    }

    /**
     * trimRecursive
     *
     * @param array $data
     * @return array
     */
    private function trimRecursive(array $data): array
    {
        return array_map(function ($value) {
            if (is_array($value)) {
                return $this->trimRecursive($value);
            }
            return is_string($value) ? trim($value) : $value;
        }, $data);
    }

    /**
     * formAction
     *
     * @return string
     */
    public function formAction()
    {
        return baseUrl() . $this->getController() . '/' . $this->getAction();
    }
}