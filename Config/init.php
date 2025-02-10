<?php

define("ROOT", dirname(__DIR__));

define("FLAG_A", 0b0001); // 1
define("FLAG_B", 0b0010); // 2
define("FLAG_C", 0b0100); // 4
define("FLAG_D", 0b1000); // 8

//ErrorHandler Params
define("DEBUG", true);

define("APP", ROOT . "/App");
define("CACHE", ROOT . "/tmp");
define("WWW", ROOT . "/Public");
define("TESTS", ROOT . "/tests");
define("LOGS", ROOT . "/tmp/logs");
define("CONFIG", ROOT . "/Config");
define("CORE", ROOT . "/vendor/Core");
define("HELPERS", ROOT . "/vendor/Core/Helpers");

define("LAYOUT", "FRAMEWORK");
define("NO_IMAGE", "uploads/no_image.png");
define("PATH", "https://my-framework.loc");
define("ADMIN", "https://my-framework.loc/admin");

require_once ROOT . "/vendor/autoload.php";
require_once HELPERS . "/function.php";
require_once CONFIG . "/routes.php";
