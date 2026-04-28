<?php

use Core\Library\Request;
use Core\Library\Session;

if (!function_exists('formTitulo')) {

    /**
     * Undocumented function
     *
     * @param string $titulo
     * @param boolean $btnNovo
     * @return string
     */
    function formTitulo($titulo, $btnNovo = false)
    {
        $request = new Request();

        if ($btnNovo) {
            $cHtmlBtn = buttons("new");
        } else {
            $cHtmlBtn = buttons("voltarTitulo");
        }

        $cHtml = '<div class="row bg-primary text-white m-2">
                    <div class="col-10 p-2">
                        <h2>' . $titulo . formSubTitulo($request->getAction()) . '</h2>
                    </div>
                    <div class="col-2 text-end p-2">
                        ' . $cHtmlBtn . '
                    </div>
                </div>';

        $cHtml .= exibeAlerta();

        return $cHtml;
    }
}

if (!function_exists('formSubTitulo')) {

    /**
     * Undocumented function
     *
     * @param string $action
     * @return string
     */
    function formSubTitulo($action)
    {
        if ($action == "insert") {
            return ' - Novo';
        } elseif ($action == "update") {
            return ' - Alteração';
        } elseif ($action == "delete") {
            return ' - Exclusão';
        } elseif ($action == "view") {
            return ' - Visualização';
        } else {
            return '';
        }
    }
}

if (!function_exists('formButton')) {

    /**
     * Undocumented function
     *
     * @return string
     */
    function formButton()
    {
        $request = new Request();

        $cHtml = '<a href="' . baseUrl() . $request->getController() . '" class="btn btn-outline-info" title="Voltar">Voltar</a>';
        
        if ($request->getAction() != "view") {
            $cHtml .= '<button type="submit" class="mx-2 btn btn-primary">Enviar</button>';
        }
        
        return $cHtml;
    }
}

if (!function_exists('buttons')) {

    /**
     * Undocumented function
     *
     * @param string $acao
     * @param integer $id
     * @return string
     */
    function buttons($acao, $id = 0)
    {
        $request = new Request();
        $button = "";
        $urlButton = baseUrl() . $request->getController();

        if ($acao == "new") {
            $button .= '<a href="' . $urlButton . '/form/insert" class="btn btn-outline-info text-white" title="Novo">Novo</a>';
        } elseif ($acao == "update") {
             $button .= '<a href="' . $urlButton . '/form/update/'. $id . '" class="btn btn-warning btn-sm" title="Alterar">Alterar</a>';
        } elseif ($acao == "delete") {
             $button .= '<a href="' . $urlButton . '/form/delete/'. $id . '" class="btn btn-danger btn-sm" title="Excluir">Excluir</a>';
        } elseif ($acao == "view") {
             $button .= '<a href="' . $urlButton . '/form/view/'. $id . '" class="btn btn-secondary btn-sm" title="Visualizar">Visualizar</a>';
        } elseif ($acao == "voltarTitulo") {
            $button .= '<a href="' . $urlButton . '" class="btn btn-outline-info text-white" title="Voltar">Voltar</a>';
        }

        return $button;
    }
   
}


if (!function_exists('exibeAlerta')) {

    /**
     * Undocumented function
     *
     * @return string
     */
    function exibeAlerta()
    {
        $msgSucesso = Session::getDestroy('msgSucesso');
        $msgError = Session::getDestroy('msgError');
        $msgAlerta = Session::getDestroy('msgAlerta');

        $mensagem = '';
        $classAlerta = '';

        if ($msgSucesso != "") {
            $mensagem = $msgSucesso;
            $classAlerta = 'success';
        } elseif ($msgError != "") {
            $mensagem = $msgError;
            $classAlerta = 'danger';
        } elseif ($msgAlerta != "") {
            $mensagem = $msgAlerta;
            $classAlerta = 'warning';
        }

        if ($mensagem == "") {
            return "";
        } else {
            return '<div class="alert alert-' . $classAlerta . ' alert-dismissible fade show" role="alert">
                        <strong>' . $mensagem . '</strong>.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        }
    }
}