<?php

namespace Core;

class App
{

    public static $app;

    public function __construct()
    {

        new ErrorHandler();

        self::$app = Registry::getInstance();

        $this->getParams();
        
        Router::dispatch(trim(urldecode($_SERVER["REQUEST_URI"]), '/'));
    }

    private function getParams()
    {
        $params = require_once CONFIG . "/params.php";

        if (!empty($params))
        {
            foreach ($params as $k => $v) {
                self::$app->setProperty($k, $v);
            }
        }
    }
}
