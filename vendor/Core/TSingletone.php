<?php

namespace Core;

trait TSingletone
{
    private static ?self $instance = null;
    
    private function __construct(){}
    
    public static function getInstance()
    {
        return self::$instance ?? self::$instance = new static();
    }
}
