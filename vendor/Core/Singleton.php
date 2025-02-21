<?php

namespace Core;

trait Singleton
{
    private static ?self $instance = null;
    
    private function __construct(){}
    
    public static function getInstance()
    {
        return self::$instance ?? self::$instance = new self();
    }
}
