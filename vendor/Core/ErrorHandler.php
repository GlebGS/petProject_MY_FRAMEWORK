<?php

namespace Core;

class ErrorHandler {
    
    public function __construct() 
    {
        if(DEBUG)
        {
            error_reporting(-1);
        }else{
            error_reporting(0);
        }
        
        set_error_handler([$this, "handlerError"]);
        set_exception_handler([$this, "handlerException"]);
        register_shutdown_function([$this, "handlerShutdown"]);
    }
    
    /**
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     */
    protected function handlerError($errno, $errstr, $errfile, $errline) 
    {
        $this->logError("Error: [{$errno}] {$errstr} in {$errfile} on line {$errline}");
    }
    
    /**
     * @param Throwable $exception
     */
    protected function handlerException($exception) 
    {
        $this->logError("Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
    }
    
    protected function handlerShutdown() 
    {
        $error = error_get_last();
        
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR]))
        {
            $this->logError("Fatal Error: [{$error['type']}] {$error['message']} in {$error['file']} on line {$error['line']}");
        }
    }
    
    /**
     * @param string $message
     */
    protected function logError($message) 
    {
        if(WRITE_LOGS)
        {
            return file_put_contents(
                LOGS . "/error.log", "[" . date("Y-m-d H:i:s") . "] Ошибка: {$message}\n==========================\n",
                FILE_APPEND
            );
        }   
        
        return true;
    }
}
