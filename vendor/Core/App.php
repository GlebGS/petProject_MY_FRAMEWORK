<?php

namespace Core;

class App {
    
    public static $app;
    public static array $params = [];

    public function __construct() 
    {
        self::$app = Registry::getInstance();
        
        $this->getParams();
    }
    
    private function getParams()
    {
        $params = require_once CONFIG . "/params.php";

        if(!empty($params))
        {
            foreach ($params as $k => $v) {
                self::$app->setProperty($k, $v);
            }
        }
    }
}
