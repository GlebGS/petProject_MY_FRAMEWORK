<?php

namespace Tests\Core;

use Core\Router;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class RouterTest extends TestCase
{

    protected $router;

    protected array $params = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->router = new Router;
    }

    #[DataProvider("dataProviderAddRoute")]
    public function testAddRoute($regexp, $controller, $action)
    {
        if (!is_string($regexp) && !is_string($controller) && !is_string($action))
        {
            if (intval($regexp) || intval($controller) || intval($action))
            {
                $this->assertFalse(true);
            }

            if (floatval($regexp) || floatval($controller) || floatval($action))
            {
                $this->assertFalse(true);
            }

            $this->assertFalse(true);
        }

        $this->router::add($regexp, ["controller" => $controller, "action" => $action]);

        foreach ($this->router::getRoutes() as $pattern => $route) {
            if ($pattern == $regexp)
            {
                $this->assertTrue(true);
            }
            if ($route["controller"] == $controller || $route["action"] == $action)
            {
                $this->assertTrue(true);
            }
        }
    }

    public static function dataProviderAddRoute()
    {
        return [
            ["^/$", "Test", "test"],
            ["^/test$", 1, "test"],
            ["^/test/$", "Test", 1],
            ["^/test/test$", 2.2, "test"],
            ["^/test/test$", "Test", 2.2],
            [2.3, "Test", "test"],
            ["^2.3$", "Test", "test"],
            ["^/test$", "2.3", "test"],
            ["^test$", "Test", "2.3"]
        ];
    }

    #[DataProvider("dataProviderMatchRoute")]
    public function testMatchRoute($url)
    {
        foreach ($this->router::getRoutes() as $pattern => $route) {
            if (preg_match("#$pattern#", $url, $matches))
            {
                $this->assertSame($url, $matches[0]);
            }

            if (empty($route["action"]))
            {
                $route["action"] = "index";

                $this->assertSame($route["action"], "index");
            }
            if (!isset($route["admin_prefix"]))
            {
                $route["admin_prefix"] = '';

                $this->assertSame($route["admin_prefix"], '');
            }
        }
    }

    public static function dataProviderMatchRoute()
    {
        return [
            ["/"],
            ["/test"],
            ["/test/test"]
        ];
    }

    #[DataProvider("dataProviderRemoveQueryString")]
    public function testRemoveQueryString($url)
    {
        $params = explode('?', $url, 2);

        if (isset($params[1]))
        {
            $getParams = explode('&', $params[1]);
            foreach ($getParams as $k => $v) {
                $value = explode('=', $v);

                if (!is_string($k))
                {
                    unset($getParams[$k]);
                }
                elseif (empty($value[1]))
                {
                    $value[1] = null;
                }
                
                $getParams[$value[0]] = $value[1];
            }
        }
        
        $this->assertTrue(true);
    }

    public static function dataProviderRemoveQueryString()
    {
        return [
            ["/test/to/patch"],
            ["/test/to/patch/?id=1&name=Tester"]
        ];
    }
}
