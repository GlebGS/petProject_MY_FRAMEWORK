<?php

namespace Core;

use RedBeanPHP\R;

class DB
{
    use Singleton;
    
    private function __construct()
    {
        $db = require CONFIG . '/params.php';
        
        R::setup($db["db_params"]["dsn"], $db["db_params"]["db_username"], $db["db_params"]["db_password"]);
       
        if (!R::testConnection())
        {
            throw new \Exception('No connection to DB', 500);
        }
        
        R::freeze(true);
        
        if (DEBUG)
        {
            R::debug(true, 3);
        }
    }
}
