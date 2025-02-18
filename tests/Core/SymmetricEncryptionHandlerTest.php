<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class SymmetricEncryptionHandlerTest extends TestCase
{   
    public function testEncryptAndDecrypt()
    {
        $handler = new \Core\SymmetricEncryptionHandler();
        $this->assertNotEmpty($handler->getKey());
        
        $plainText = "Test message...";

        $handler->encrypt($plainText);
        
        $encryptedData = $handler->getEncryptedData(); 
            
        $this->assertNotEmpty($encryptedData);
        $this->assertNotEquals($plainText, $encryptedData);
        
        $decryptData = $handler->decrypt();
        
        $this->assertEquals($plainText, $decryptData);
    }
    
    public function testDecryptWithEmptyData()
    {
        $handler = new \Core\SymmetricEncryptionHandler();
        
        $decrypt = $handler->decrypt();
        
        $this->assertFalse($decrypt);
    }
    
    public function testEncryptWithCustomKey()
    {
        $key       = sodium_crypto_secretbox_keygen();
        $handler   = new \Core\SymmetricEncryptionHandler($key);
        $plainText = "Test message...";

        $handler->encrypt($plainText);
        $encryptedData = $handler->getEncryptedData();

        $this->assertNotEmpty($encryptedData);
        $this->assertNotEquals($plainText, $encryptedData);

        $decryptedText = $handler->decrypt();

        $this->assertEquals($plainText, $decryptedText);
    }
    
    public function testSetEncryptedDataAndDecrypt()
    {
        $handler   = new \Core\SymmetricEncryptionHandler();
        $plainText = "Это тестовое сообщение";

        $handler->encrypt($plainText);
        $encryptedData = $handler->getEncryptedData();

        $newHandler = new \Core\SymmetricEncryptionHandler($handler->getKey());
        $newHandler->setEncryptedData($encryptedData);

        $decryptedText = $newHandler->decrypt();

        $this->assertEquals($plainText, $decryptedText);
    }
}
