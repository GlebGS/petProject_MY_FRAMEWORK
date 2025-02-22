<?php

namespace Core;

class SymmetricEncryptionHandler
{    
    private string $key;
    
    private string $encryptedData = '';
    
    public function __construct(string $key = null)
    {
        if ($key === null)
        {
            $this->key = sodium_crypto_secretbox_keygen();
        }
        else
        {
            if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES)
            {
                throw new \InvalidArgumentException("Неверная длина ключа. Ожидается " . SODIUM_CRYPTO_SECRETBOX_KEYBYTES . " байты.");
            }
            
            $this->key = $key;
        }
    }
    
    public function encrypt(string $plainText): void
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipherText = sodium_crypto_secretbox($plainText, $nonce, $this->key);
        
        $this->encryptedData = bin2hex($nonce . $cipherText);
    }
    
    /**
     * Потоковое шифрование данных из одного файла в другой.
     *
     * @param string $inputFile Файл из которого мы считываем данные и шифруем в другой.
     * @param string $outputFile Файл для записи зашифрованных данных.
     */
    public function streamEncryption(string $inputFile, string $outputFile): void
    {
        $input = fopen($inputFile, "rb");
        $output = fopen($outputFile, "wb");
        
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        fwrite($output, $nonce);

        while (!feof($input)) {
            $chunk = fread($input, 8192);
            if ($chunk === false)
            {
                break;
            }

            $encryptedChunk = sodium_crypto_stream_xchacha20_xor($chunk, $nonce, $this->key);

            fwrite($output, $encryptedChunk);
        }

        fclose($input);
        fclose($output);
    }
    
    public function decrypt(): string | false
    {
        if(empty($this->encryptedData))
        {
            return false;
        }
        
        $encryptedData = hex2bin($this->encryptedData);
        
        $nonce = substr($encryptedData, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipherText = substr($encryptedData, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        
        $plainText = sodium_crypto_secretbox_open($cipherText, $nonce, $this->key);
        
        return $plainText !== false ? $plainText : false;
    }
    
    /**
     * Потоковая расшифровка данных из одного файла в другой.
     *
     * @param string $inputFile Файл с зашифрованными данными.
     * @param string $outputFile Файл для записи расшифрованных данных.
     */
    public function streamDecryption(string $inputFile, string $outputFile)
    {
        $input  = fopen($inputFile, "rb");
        $output = fopen($outputFile, "wb");

        $nonce = fread($input, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        
        while (!feof($input)) {
            $chunk = fread($input, 8192);
            if ($chunk === false)
            {
                break;
            }
            
            $decryptedChunk = sodium_crypto_stream_xchacha20_xor($chunk, $nonce, $this->key);
            
            fwrite($output, $decryptedChunk);
        }

        fclose($input);
        fclose($output);
    }
    
    public function getEncryptedData(): string
    {
        return $this->encryptedData;
    }
    
    public function setEncryptedData(string $encryptedData): void
    {
        $this->encryptedData = $encryptedData;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}