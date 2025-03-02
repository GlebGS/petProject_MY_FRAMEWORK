# FRAMEWORK

FRAMEWORK имеет следующую функциональность:
  
  + **Маршрутизатор ([Router](vendor/Core/Router.php))**, который реализует использование **[Моделей](vendor/Core/Model.php)** и **[Видов](vendor/Core/View.php).**
  + **Обработчик ошибок ([ErrorHandler](vendor/Core/ErrorHandler.php))**
  + Паттерны: **[Singleton](vendor/Core/Singleton.php)** и **[Registry](vendor/Core/Registry.php)**
  + **Работу с файлами ([FileHandler](vendor/Core/FileHandler.php))**
  + **Симетричное и потоковое шифрование ([SymmetricEncryptionHandler](vendor/Core/SymmetricEncryptionHandler.php))**
  + Тестирование: **PHPUnit, PHPStan, PHPBench**


## Настройка NGINX
```nginx
    events {}

    http {

        server {
            listen 80;
            listen [::]:80;
            server_name framework.loc;
            root /patch/to/Public;

            index index.php;

            charset utf-8;

            location / {
                try_files $uri $uri/ /index.php?$query_string;
            }

            location ~ ^/index\.php(/|$) {
                fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
                include fastcgi_params;
                fastcgi_hide_header X-Powered-By;

                fastcgi_pass 127.0.0.1:9000;
            }

            location ~ /\.(?!well-known).* {
                deny all;
            }
        }   
    }
```

## Настройка APACHE

### FrameWork/.htaccess
```apache
    RewriteEngine On
    RewriteRule (.*) public/$1
```
___
### FrameWork/Public/.htaccess
```apache
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    #RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule (.*) index.php?$1 [L,QSA]

    #Options -Indexes
```

## Настройка подключение к База данных, SMTP
### [params.php](Config/params.php) 
```php
    return [
        "db_params" => [
            "dsn" => "pgsql:host=localhost;dbname=test_db",
            "db" => "pgsql",
            "db_host" => "localhost",
            "db_name" => "test_db",
            "db_username" => "user",
            "db_password" => "password"
        ],
        "mail_params" => [
            "mail" => "smtp",
            "mail_admin" => "test@yandex.ru",
            "mail_SMTPDebug" => 2,
            "mail_SMTPAuth" => true,
            "mail_SMTPSecure" => "ssl",
            "mail_host" => "host",
            "mail_port" => 465,
            "mail_login" => "login@yandex.ru",
            "mail_password" => "password"
        ]
    ];
```
___
## Маршрутизатор. [Router.php](vendor/Core/Router.php)

### Настройка маршрутизатора производиться в фалйе [init.php](Config/init.php)

```php
// Расположение видов, контрроллеров и моделей по умолчанию
    define("CONTROLLERS_PATH", "App\Controllers\\");
    define("MODELS_PATH", "App\Models\\");
    define("VIEWS_PATH", "App\Views\\");
```

### Новый путь добавляется в [Config/routes.php](Config/routes.php)
```php
// URL для админа
    Router::add("^admin/?$", ["controller" => "Main", "action" => "index", "admin_prefix" => "admin"]);
    Router::add("^admin/patch?$", ["controller" => "Main", "action" => "index", "admin_prefix" => "admin"]);
    Router::add("^admin/patch/to?$", ["controller" => "Main", "action" => "index", "admin_prefix" => "admin"]);

// Настройка пути для admin/
    Router::add("^admin/(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$", ["admin_prefix" => "admin"]);

// Основной URL
    Router::add("^$", ["controller" => "Main", "action" => "index"]);
    Router::add("^patch$", ["controller" => "Main", "action" => "index"]);
    Router::add("^patch/to$", ["controller" => "Main", "action" => "index"]);


// Настройка обычного пути
    Router::add("^(?P<controller>[a-z-]+)/(?P<action>[a-z-]+)/?$");
```
___
## Шифрование. [SymmetricEncryptionHandler](vendor/Core/SymmetricEncryptionHandler.php)
### Настройка шифрования производиться в фалйе [init.php](Config/init.php)
```php
// Размер записи
    define("CHUNK_SIZE", 4096);

// Файл для шифрования / расшифроки
    define("DECRYPTED_FILE", LOGS . "/decrypted.txt");
    define("ENCRYPTED_FILE", LOGS . "/encrypted.enc");
```
### Симетричное шифрование. Использование
```php
    // Создаем экземпляр с автоматической генерацией ключа
    $crypto = new SymmetricEncryptionHandler();

    // Шифруем сообщение
    $crypto->encrypt("Секретное сообщение");

    // Получаем зашифрованные данные
    $encryptedData = $crypto->getEncryptedData();

    // Для демонстрации создадим новый объект с тем же ключом
    $crypto2 = new SodiumCrypto($crypto->getKey());
    $crypto2->setEncryptedData($encryptedData);

    // Расшифровываем сообщение
    $decrypted = $crypto2->decrypt();
```
### Потоковое шифрование. Использование
```php
// Создаем экземпляр с автоматической генерацией ключа
    $crypto = new SymmetricEncryptionHandler();

/**
* Потоковое шифрование данных из одного файла в другой.
*
* @param string $inputFile Файл из которого мы считываем данные и шифруем в другой.
* @param string $outputFile Файл для записи зашифрованных данных.
*/
    $crypto->streamEncryption(string $inputFile, string $outputFile);

/**
* Потоковая расшифровка данных из одного файла в другой.
*
* @param string $inputFile Файл с зашифрованными данными.
* @param string $outputFile Файл для записи расшифрованных данных.
*/
    $crypto->streamDecryption(string $inputFile, string $outputFile);
```

## Работа с файлами. [FileHandler.php](vendor/Core/FileHandler.php)
```php
// Создаём экземпляр класса
    $file = FileHandler("file.txt");

// Запись 
    $file->write(string $data, $mode = null, bool $encode = false);

// Чтение 
    $file->read(bool $decode = false);

// Блокировка файла
    $file->lock(int $lockLevel = 1, int $sleep = 0);

// Удаление 
    $file->delete();
```

## Работа с классом Даты и Время. [DateTimeHandler.php](vendor/Core/DateTimeHandler.php)
```php
// Создание объектов
    $date1 = new \Core\DateTimeHandler('2023-01-01');
    $date2 = \Core\DateTimeHandler::now();
    $date3 = \Core\DateTimeHandler::fromTimestamp(time());

// Форматирование
    echo $date1->format('Y-m-d H:i:s');

// Манипуляции с датой
    $date1->addDays(5)->subHours(3);
    $date1->add(new DateInterval('P1M'));

// Сравнение
    if ($date1->isAfter($date2))
    {
        echo "Date1 is later than Date2";
    }

// Разница между датами
    $interval = $date1->diff($date2);
    echo $interval->format('%R%a days');

// Проверка валидности даты
    var_dump(\Core\DateTimeHandler::isValid('2023-02-30', 'Y-m-d'));
```

### Настройка параметров для Даты и Время [init.php](Config/init.php)
```php
// Date params
    define("DEFAULT_TIME_ZONE", date_default_timezone_set("Europe/Moscow"));
```

## Обработчик ошибок. [ErrorHandler.php](vendor/Core/ErrorHandler.php)

### Настройка обработчика ошибок производиться в файле [init.php](Config/init.php)
```php
// Режим PROD(false) / DEV(true)
    define("DEBUG", true);

// Файл для записи логов
    define("LOG_FILE", LOGS . "/error.log");

// Шаблоны ошибок
    define("ERROR_404", WWW . "/errors/404.php");
    define("ERROR_500", WWW . "/errors/500.php");
    define("DEVELOPMENT_FILE", WWW . "/errors/dev.php");
    define("PRODACTION_FILE", WWW . "/errors/prod.php");
```
