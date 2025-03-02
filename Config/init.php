<?php

define("ROOT", dirname(__DIR__));

define("FLAG_A", 0b0001); // 1
define("FLAG_B", 0b0010); // 2
define("FLAG_C", 0b0100); // 4
define("FLAG_D", 0b1000); // 8

define("APP", ROOT . "/App");
define("CACHE", ROOT . "/tmp");
define("WWW", ROOT . "/Public");
define("TESTS", ROOT . "/tests");
define("LOGS", ROOT . "/tmp/logs");
define("CONFIG", ROOT . "/Config");
define("CORE", ROOT . "/vendor/Core");
define("HELPERS", ROOT . "/vendor/Core/Helpers");

//ErrorHandler params
define("DEBUG", true);

define("LOG_FILE", LOGS . "/error.log");
define("ERROR_404", WWW . "/errors/404.php");
define("ERROR_500", WWW . "/errors/500.php");
define("DEVELOPMENT_FILE", WWW . "/errors/dev.php");
define("PRODACTION_FILE", WWW . "/errors/prod.php");

//Router params
define("CONTROLLERS_PATH", "App\Controllers\\");
define("MODELS_PATH", "App\Models\\");
define("VIEWS_PATH", "App\Views\\");

// FileHandler params
define("CHUNK_SIZE", 4096);
define("DECRYPTED_FILE", LOGS . "/decrypted.txt");
define("ENCRYPTED_FILE", LOGS . "/encrypted.enc");

// Date params
define("DEFAULT_TIME_ZONE", date_default_timezone_set("Europe/Moscow"));

define("LAYOUT", "FRAMEWORK");
define("NO_IMAGE", "uploads/no_image.png");
define("PATH", "https://my-framework.loc");
define("ADMIN", "https://my-framework.loc/admin");

require_once ROOT . "/vendor/autoload.php";

require_once HELPERS . "/function.php";
require_once CONFIG . "/routes.php";
