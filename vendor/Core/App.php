<?php

namespace Core;

class App
{

    public static $app;

    public function __construct()
    {
        self::$app = Registry::getInstance();

        $this->getParams();
        
        if(ERROR_LOG){
            new ErrorHandler();
        }
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
