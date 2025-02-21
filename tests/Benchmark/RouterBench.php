<?php

namespace Tests\Benchmark;

use Core\Router;
use PhpBench\Attributes as Bench;

class RouterBench
{
    private static $urls = [
        '/home',
        '/about-us',
        '/contact',
        '/user/profile',
        '/admin/dashboard',
        '/product/123',
        '/category/electronics',
    ];

    private static $routes = [
        '#^/home$#'                      => ['controller' => 'home', 'action' => 'index'],
        '#^/about-us$#'                  => ['controller' => 'about', 'action' => 'index'],
        '#^/contact$#'                   => ['controller' => 'contact', 'action' => 'index'],
        '#^/user/profile$#'              => ['controller' => 'user', 'action' => 'profile'],
        '#^/category/(?P<name>[a-z]+)$#' => ['controller' => 'category', 'action' => 'view'],
        '#^/admin/dashboard$#'           => ['controller' => 'admin', 'action' => 'dashboard', 'admin_prefix' => 'Admin'],
        '#^/product/(?P<id>\d+)$#'       => ['controller' => 'product', 'action' => 'view'],
    ];

    public static function setUpBeforeClass(): void
    {
        // Добавляем маршруты перед запуском бенчмарков
        foreach (self::$routes as $pattern => $route) {
            Router::add($pattern, $route);
        }
    }

    #[Bench\BeforeMethods("setUp")]
    #[Bench\AfterMethods("tearDown")]
    public function benchDispatch()
    {
        foreach (self::$urls as $url) {
            Router::dispatch($url);
        }
    }

    #[Bench\BeforeMethods("setUp")]
    #[Bench\AfterMethods("tearDown")]
    public function benchMatchRoute()
    {
        foreach (self::$urls as $url) {
            Router::matchRoute($url);
        }
    }

    #[Bench\BeforeMethods("setUp")]
    #[Bench\AfterMethods("tearDown")]
    public function benchRemoveQueryString()
    {
        $urlsWithQuery = [
            '/home?param1=value1&param2=value2',
            '/about-us?param1=value1',
            '/contact?param1=value1&param2=value2&param3=value3',
            '/user/profile?param1=value1',
            '/admin/dashboard?param1=value1&param2=value2',
            '/product/123?param1=value1',
            '/category/electronics?param1=value1&param2=value2',
        ];

        foreach ($urlsWithQuery as $url) {
            Router::removeQueryString($url);
        }
    }

    public function setUp(): void
    {
        // Опционально: Настройка перед каждым бенчмарком
    }

    public function tearDown(): void
    {
        // Опционально: Очистка после каждого бенчмарка
    }
}
