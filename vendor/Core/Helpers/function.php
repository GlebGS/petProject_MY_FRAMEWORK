<?php

// Debug
function dd($value, $die = false)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    
    if($die)
    {
        die;
    }
}

function h($str)
{
    return htmlspecialchars($str);
}
