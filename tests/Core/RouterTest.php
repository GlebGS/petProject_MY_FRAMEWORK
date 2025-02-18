<?php

namespace Tests\Core\Router;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Core\Router;

class RouterTest extends TestCase
{

    protected object $router;

    #[\Override]
    protected function setUp(): void
    {
        $this->router = new Router;
    }

    public function testAddRouteAndGetRoutes()
    {
        $this->router->add("/test", ["controller" => "Test", "action" => "test"]);
        
        foreach ($this->router->getRoutes() as $pattern => $route) {
            $this->assertSame("/test", $pattern);
            $this->assertSame("Test", $route["controller"]);
            $this->assertSame("test", $route["action"]);
        }
    }
    
    public function testMatchRoute()
    {
        
    }
}
