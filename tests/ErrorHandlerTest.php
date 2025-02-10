<?php

namespace Test;

use Core\ErrorHandler;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;

class ErrorHandlerTest extends TestCase
{

    protected $errorHandler;

    protected $logFile;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        define("DEBUG", true);
        define("ROOT", dirname(__DIR__));
        define("LOGS", ROOT . "/tmp/logs");

        $this->logFile = LOGS . "/error.log";

        if (file_exists($this->logFile))
        {
            unlink($this->logFile);
        }

        $this->errorHandler = new ErrorHandler();
    }

    #[\Override]
    protected function tearDown(): void
    {
        parent::tearDown();

        error_reporting(E_ALL);

        restore_error_handler();
        restore_exception_handler();
    }

    #[RunInSeparateProcess]
    #[DataProvider("providerConstructorErrorReporting")]
    public function testConstructorErrorReportingLevel($param_error_reporting)
    {
        error_reporting($param_error_reporting);

        $this->assertEquals($param_error_reporting, error_reporting());
    }

    public static function providerConstructorErrorReporting(): array
    {
        return [
            [-1], [0]
        ];
    }

    public function testErrorHandler()
    {
        trigger_error("Test error", E_USER_ERROR);

        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString("Test error", $logContent);
    }

    public function testExceptionHandler()
    {
        $exception = new \Exception("Test exception");
        $this->errorHandler->exceptionHandler($exception);

        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString("Test exception", $logContent);
    }

    public function testShutdownHandler()
    {
        trigger_error("Fatal error", E_USER_ERROR);

        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString("Fatal error", $logContent);
    }

    public function testLogError()
    {
        $this->errorHandler->logError("Test log message", "testfile.php", 123);

        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString("Test log message", $logContent);
        $this->assertStringContainsString("testfile.php", $logContent);
        $this->assertStringContainsString("123", $logContent);
    }
}
