<?php

namespace Tests\Benchmark;

use PhpBench\Attributes as Bench;

class SymmetricEncryptionHandlerBench
{

    private $handler;

    private $plainText;

    private $inputFile;

    private $outputFile;

    public function __construct()
    {
        $this->handler    = new \Core\SymmetricEncryptionHandler();
        $this->plainText  = str_repeat('a', 1024 * 1024); // 1MB of data
        $this->inputFile  = dirname(__DIR__) . "/testLogsDir/decrypted.txt";
        $this->outputFile = dirname(__DIR__) . "/testLogsDir/encrypted.enc";

        // Create a test input file
        file_put_contents($this->inputFile, $this->plainText);
    }

    #[Bench\BeforeMethods("setUp")]
    #[Bench\AfterMethods("tearDown")]
    public function benchEncrypt()
    {
        $this->handler->encrypt($this->plainText);
    }

    #[Bench\BeforeMethods("setUp")]
    #[Bench\AfterMethods("tearDown")]
    public function benchDecrypt()
    {
        $this->handler->encrypt($this->plainText);
        $this->handler->decrypt();
    }

    #[Bench\BeforeMethods("setUp")]
    #[Bench\AfterMethods("tearDown")]
    public function benchStreamEncryption()
    {
        $this->handler->streamEncryption($this->inputFile, $this->outputFile);
    }

    #[Bench\BeforeMethods("setUp")]
    #[Bench\AfterMethods("tearDown")]
    public function benchStreamDecryption()
    {
        $this->handler->streamEncryption($this->inputFile, $this->outputFile);
        $this->handler->streamDecryption($this->outputFile, $this->inputFile . '.decrypted');
    }

    public function setUp(): void
    {
        // Optional: Any setup before each benchmark iteration
    }

    public function tearDown(): void
    {
        // Clean up after each benchmark iteration
        if (file_exists($this->outputFile))
        {
            unlink($this->outputFile);
        }
        if (file_exists($this->inputFile . '.decrypted'))
        {
            unlink($this->inputFile . '.decrypted');
        }
    }
}
