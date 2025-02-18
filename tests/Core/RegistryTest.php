<?php

namespace Tests\Core;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class RegistryTest extends TestCase
{
    
    private array $properties = [];

    #[DataProvider("dataProviderSetProperty")]
    public function testSetProperty($key, $value)
    {
        $this->properties[$key] = $value;
        $this->assertIsArray($this->properties);
        
        if(is_float($key))
        {
            $this->assertTrue(false);
        }
    }

    public static function dataProviderSetProperty()
    {
        return [
            ["key", "value"],
            [1, "value"],
            ["key", 2],
            ["key", 2.2],
            [null, "value"]
        ];
    }
}
