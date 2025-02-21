<?php

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
