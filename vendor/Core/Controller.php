<?php

namespace Core;

abstract class Controller
{

    public array $data = [];

    public array $meta = [];
    
    public $layout = LAYOUT;

    public string $view = '';

    public $model;

    public function __construct(
        public $route = [], 
        public $param = []
    ){}

    public function getModel()
    {
        $model = "App\Models\\" . $this->route["admin_prefix"] . $this->route["controller"];
        if (class_exists($model))
        {
            $this->model = new $model();
        }
    }
    
    public function getView()
    {
        $this->view = $this->view ?: $this->route["action"];
        
        (new View($this->route, $this->layout, $this->view, $this->meta))->render($this->data);
    }
    
    public function set($data)
    {
        $this->data = $data;
    }
    
    public function setMeta($title = '')
    {
        return $this->meta = [
            "title" => $title 
        ];
    }
}
