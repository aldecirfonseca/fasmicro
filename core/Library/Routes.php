<?php

namespace Core\Library;

class Routes
{
    use RequestTrait;

    /**
     * rota
     *
     * @return void
     */
    public static function rota()
    {
        $pathContr      = "App\Controller\\";
        $aParametros    = Self::getRotaParametros();
        $controller     = $pathContr . $aParametros['controller'];
        
        if (!class_exists($controller)) {
            Erros::controllerNotFound($aParametros['controller']);
        } else {
            if (!method_exists($controller, $aParametros['method'])) {
                Erros::methodNotFound($aParametros['method']);
            } else {
                $instance = new $controller();

                call_user_func_array([$instance, $aParametros['method']], array_merge([$aParametros['action'], $aParametros['id']], $aParametros['outrosPar']));

                return;
            }
        }
    }
}