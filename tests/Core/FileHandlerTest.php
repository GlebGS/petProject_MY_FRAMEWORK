<?php

namespace Tests;

use Core\FileHandler;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class FileHandlerTest extends TestCase
{
    protected $testFile = '';

    protected $fileHandler;

    protected function setUp(): void
    {      
        $this->testFile = dirname(__DIR__) . "/tmp/testDir/testFile.txt";

        $this->fileHandler = new \Core\FileHandler($this->testFile);
        
        if($this->assertFileExists($this->testFile))
        {
            file_put_contents($this->testFile, "Test Message");
        }
    }
    
    public function testPathNotStringAndFileNotExists()
    {
        $this->expectException(\InvalidArgumentException::class);

        $testPathFile = 123123;

        $this->expectExceptionMessage("Не верно указан путь");

        if (!is_string($testPathFile))
        {
            throw new \InvalidArgumentException("Не верно указан путь");
        }
        
        if (!file_exists($testPathFile))
        {
            file_put_contents($this->testFile, null);
        }
        
        if (file_exists($testPathFile))
        {
            $this->assertFileExists($testPathFile, "Test Message");
        }
    }
    
    public function testWriteDataInFileNotEncode()
    {
        $testString = "Write test message";
        
        $this->fileHandler->write($testString);
       
        $this->assertStringContainsString($testString, file_get_contents($this->testFile));
    }
    
    public function testWriteDataAndReadDataInFileEncodeAndDecode()
    {
        $testString = "Write test message";

        $this->fileHandler->write($testString, 'w', true);
        
        $decodeString = $this->fileHandler->read(true);
        
        $this->assertEquals($testString, $decodeString);
    }
    
    #[DataProvider("dataProviderLockFile")]
    public function testLockFile($lockLevel, $sleep)
    {
        $this->fileHandler->lock($lockLevel, $sleep);
    }
    
    public static function dataProviderLockFile() 
    {
        return [
            [1, 1],
            [2, 1],
            [1, -1],
            [2, -1],
            [-1, 1],
            [-2, 1]
        ];
    }
    
    public function testDeleteFile()
    {
        $this->assertFileExists($this->testFile);

        $result = $this->fileHandler->delete($this->testFile);

        $this->assertFileDoesNotExist($this->testFile);

        $this->assertTrue($result);
    }
}
