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
        return array_map('trim', $this->param['get']);
    }

    /**
     * @return array<string, mixed>
     */
    public function getPost(): array
    {
        return array_map('trim', $this->param['post']);
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function formAction()
    {
        return baseUrl() . $this->getController() . '/' . $this->getAction();
    }
}