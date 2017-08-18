<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Mmi\Mvc\ViewHelper;

class Url extends HelperAbstract
{

    /**
     * Generuje link na podstawie parametrów (z użyciem routera)
     * @see \Mmi\Mvc\Router::encodeUrl()
     * @param array $params parametry
     * @param boolean $reset nie łączy z bieżącym requestem
     * @param boolean | null $https czy wymusić https: tak, nie https, null = bez zmiany protokołu
     * @return string
     */
    public function url(array $params = [], $reset = false, $https = null)
    {
        //łączenie parametrów z requestem widoku
        if (!$reset) {
            $params = array_merge($this->view->request->toArray(), $params);
        }
        //usuwanie nullowych parametrów
        foreach ($params as $key => $param) {
            if (null === $param) {
                unset($params[$key]);
            }
        }
        //wyznaczanie url
        $url = \Mmi\App\FrontController::getInstance()->getRouter()->encodeUrl($params);
        //zwrot samego url (bez zmiany protokołu)
        if (null === $https) {
            return $url ? $url : '/';
        }
        //host środowiskowy, lub z konfiguracji (jeśli brak)
        $host = \Mmi\App\FrontController::getInstance()->getEnvironment()->httpHost ? : \App\Registry::$config->host;
        //link absolutny
        return ($https ? 'https' : 'http') . '://' . $host . $url;
    }

}
