<?php

require_once dirname(__DIR__) . "/Config/init.php";

new Core\App();

echo "<h1>" . LAYOUT . "</h1>";

echo "<pre>";
print_r(\Core\App::$app->getProperties());
