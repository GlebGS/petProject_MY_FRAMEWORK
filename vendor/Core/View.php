<?php

namespace Core;

use \RedBeanPHP\R;

class View
{

    public function __construct(
        public $route, public $layout = '', public $view = '', public $meta = []
    )
    {
        if (false !== $this->layout)
        {
            $this->layout = $this->layout ?: LAYOUT;
        }
    }

    public function render($data)
    {
        if (is_array($data))
        {
            extract($data);
        }

        $prefix = str_replace('\\', '/', $this->route["admin_prefix"]);

        $view = APP . "/Views/{$prefix}{$this->route["controller"]}/{$this->view}.php";

        if (is_file($view))
        {
            require_once $view;
        }
        else
        {
            throw new \Exception("Вид {$view} не найден", 500);
        }
    }

    public function getDbLogs()
    {
        if (DEBUG)
        {
            $logs = R::getDatabaseAdapter()
                ->getDatabase()
                ->getLogger();
            $logs = array_merge($logs->grep("SELECT"), $logs->grep("INSERT"), $logs->grep("UPDATE"),
                $logs->grep("DELETE"));

            dd($logs);
        }
    }
}
