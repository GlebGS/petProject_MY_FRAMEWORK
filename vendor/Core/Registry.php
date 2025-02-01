<?php

namespace Core;

class Registry {
    
    use TSingletone;
    
    private static array $properties = [];
    
    public function setProperty($key, $value)
    {
        return self::$properties[$key] = $value;
    }
    
    public function getProperty($key)
    {
        return self::$properties[$key] ?? null;
    }
    
    public function getProperties()
    {
        return self::$properties;
    }
}
