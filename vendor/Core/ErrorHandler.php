<?php

namespace Core;

class ErrorHandler
{
    
    public $logFile = LOGS . "/error.log";

    public $fileHandler;


    public function __construct()
    {
        $this->fileHandler = new FileHandler($this->logFile);
        
        (DEBUG) ? error_reporting(-1) : error_reporting(0);

        set_error_handler([$this, "errorHandler"]);
        set_exception_handler([$this, "exceptionHandler"]);

        register_shutdown_function([$this, "shutdownHandler"]);
    }

    public function errorHandler($errstr, $errfile, $errline)
    {
        $this->logError($errstr, $errfile, $errline);
    }

    public function exceptionHandler(\Throwable $exception)
    {
        $this->logError($exception->getMessage(), $exception->getFile(), $exception->getLine());
    }

    public function shutdownHandler()
    {
        $error = error_get_last();

        if (!empty($error) && $error['type'] & (E_USER_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR))
        {
            $this->logError($errstr, $errfile, $errline);
        }
    }

    /** 
     * Важный момент.
     *      Перед тестированием не устанавливать влаг $encode = true
     *       -> Тогда тестирование не пройдёт
     *  */
    public function logError($message = '', $file = '', $line = '')
    {
        $data = "[" . date("Y-m-d H:i:s") . "] Текст ошибки: {$message} | Файл: {$file} | Строка: {$line}\n=================\n";
        
        return $this->fileHandler->write($data, FILE_APPEND);
    }
    
    public function __destruct()
    {
        restore_error_handler();
        restore_exception_handler();
    }
}
