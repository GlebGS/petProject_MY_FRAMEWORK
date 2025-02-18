<?php

namespace App\Controllers;

use Core\Controller;
use RedBeanPHP\R;

class MainController extends Controller
{

    public function indexAction()
    {
        
        dd($this->model->all());
        
        $this->setMeta("Main");

        $this->set([
            "title"  => "title",
            "body"   => "body",
            "footer" => "footer"
        ]);
    }
}
