<?php

namespace Core;

class Router
{

    protected static array $routes = [];

    protected static array $route = [];

    protected static array $getParams = [];

    public static function add($regexp, $route = [])
    {
        return self::$routes[$regexp] = $route;
    }

    public static function getRoutes()
    {
        return self::$routes;
    }

    public static function getRoute()
    {
        return self::$route;
    }

    public static function dispatch($url)
    {
        $url = self::removeQueryString($url);

        if (self::matchRoute($url))
        {
            $controller = CONTROLLERS_PATH . self::$route["admin_prefix"] . self::$route["controller"] . "Controller";
            $action     = self::lowerCamelCase(self::$route["action"] . "Action");

            if (class_exists($controller))
            {
                $controllerObject = new $controller(self::$route, self::$getParams);

                $controllerObject->getModel();

                if (method_exists($controllerObject, $action))
                {
                    $controllerObject->$action();
                    $controllerObject->getView();
                }
                else
                {
                    throw new \Exception("Метод {$controller}::{$action} не найден", 404);
                }
            }
            else
            {
                throw new \Exception("Контроллер {$controller} не найден", 404);
            }
        }
    }

    public static function matchRoute($url)
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#{$pattern}#", $url, $matches))
            {
                foreach ($matches as $k => $v) {
                    if (is_string($k))
                    {
                        $route[$k] = $v;
                    }
                }

                if (empty($route['action']))
                {
                    $route['action'] = 'index';
                }
                if (!isset($route['admin_prefix']))
                {
                    $route['admin_prefix'] = '';
                }
                else
                {
                    $route['admin_prefix'] .= '\\';
                }

                $route["controller"] = self::upperCamelCase($route["controller"]);

                self::$route = $route;
                return true;
            }
        }

        return throw new \Exception("Пути /$url не существует.", 404);
    }

    protected static function removeQueryString($url)
    {
        if ($url)
        {
            $params = explode('?', $url, 2);

            if (!empty($params[1]))
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

                self::$getParams = $getParams;
            }


            if (false === str_contains($params[0], '='))
            {

                return rtrim($params[0], '/');
            }
        }

        return '';
    }

    protected static function upperCamelCase($str): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $str)));
    }

    protected static function lowerCamelCase($str): string
    {
        return lcfirst(self::upperCamelCase($str));
    }
}
