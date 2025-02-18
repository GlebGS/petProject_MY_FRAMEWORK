<?php

namespace App\Controllers\admin;

use \Core\Controller;

class MainController extends Controller
{
    
    public function indexAction()
    {
        $this->setMeta("Admin");
        
        $this->set([
                "title" => "title",
                "body" => "body",
                "footer" => "footer"
            ]);
    }
}
