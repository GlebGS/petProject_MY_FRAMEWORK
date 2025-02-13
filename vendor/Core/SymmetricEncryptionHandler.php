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
                throw new InvalidArgumentException("Неверная длина ключа. Ожидается " . SODIUM_CRYPTO_SECRETBOX_KEYBYTES . " байты.");
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

//// Создаем экземпляр с автоматической генерацией ключа
//$crypto = new SodiumCrypto();
//
//// Шифруем сообщение
//$crypto->encrypt("Секретное сообщение");
//
//// Получаем зашифрованные данные
//$encryptedData = $crypto->getEncryptedData();
//
//// Для демонстрации создадим новый объект с тем же ключом
//$crypto2 = new SodiumCrypto($crypto->getKey());
//$crypto2->setEncryptedData($encryptedData);
//
//// Расшифровываем сообщение
//$decrypted = $crypto2->decrypt();
