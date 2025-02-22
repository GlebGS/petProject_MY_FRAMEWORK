<?php

namespace Core;

class ErrorHandler
{

    private $logFile = LOG_FILE;

    private $fileHandler;

    public function __construct()
    {
        $this->fileHandler = new FileHandler($this->logFile);

        (DEBUG) ? error_reporting(-1) : error_reporting(0);

        set_error_handler([$this, "errorHandler"]);
        set_exception_handler([$this, "exceptionHandler"]);

        ob_start();

        register_shutdown_function([$this, "shutdownHandler"]);
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $this->logError($errstr, $errfile, $errline);
        $this->displayError($errno, $errstr, $errfile, $errline);
    }

    public function exceptionHandler(\Throwable $exception)
    {
        $this->logError($exception->getMessage(), $exception->getFile(), $exception->getLine());
        $this->displayError("Исключение", $exception->getMessage(), $exception->getFile(), $exception->getLine(),
            $exception->getCode());
    }

    public function shutdownHandler()
    {
        $error = error_get_last();

        if (!empty($error) && $error['type'] & (E_USER_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR))
        {
            $this->logError($error["message"], $error["file"], $error["line"]);
            ob_end_clean();
            $this->displayError($error["type"], $error["message"], $error["file"], $error["line"]);
        }
        else
        {
            ob_end_flush();
        }
    }

    public function logError($message = '', $file = '', $line = '')
    {
        $data = "[" . date("Y-m-d H:i:s") . "] Текст ошибки: {$message} | Файл: {$file} | Строка: {$line}\n=================\n";

        return $this->fileHandler->write($data, FILE_APPEND);
    }

    public function displayError($errno = '', $errstr = '', $errfile = '', $errline = '', $responce = 500)
    {
        if ($responce == 0)
        {
            $responce = 404;
        }

        http_response_code($responce);

        if ($responce == 404 && !DEBUG)
        {
            require ERROR_404;
            die;
        }
        if (DEBUG)
        {
            require DEVELOPMENT_FILE;
        }
        else
        {
            require PRODACTION_FILE;
        }

        die;
    }

    public function __destruct()
    {
        restore_error_handler();
        restore_exception_handler();
    }
}
