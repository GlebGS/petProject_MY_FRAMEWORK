<?php

namespace App\Models;

use \Core\Model;
use RedBeanPHP\R;

class Main extends Model
{
    
    public function all()
    {
        return R::findAll("names");
    }
    
}
