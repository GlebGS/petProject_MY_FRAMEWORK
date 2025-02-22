<?php

namespace Core;

class FileHandler extends SymmetricEncryptionHandler
{
    protected $encryptHandler;

    protected $file;

    public function __construct(string $pathFile)
    {
        $this->encryptHandler = new SymmetricEncryptionHandler();

        if (!is_string($pathFile))
        {
            throw new \Exception("Не верный путь \$pathFile.", 500);
        }

        if (!file_exists($pathFile))
        {
            file_put_contents($pathFile, null);
        }

        $this->file = $pathFile;
    }

    public function write(string $data, $mode = null, bool $encode = false)
    {
        if (file_exists($this->file))
        {
            if ($encode == true)
            {
                $this->encryptHandler->encrypt($data);
                
                return file_put_contents($this->file, $this->encryptHandler->getEncryptedData());
            }
            
            return file_put_contents($this->file, $data, $mode);
        }

        return false;
    }

    public function read(bool $decode = false)
    {
        if (file_exists($this->file))
        {
            if ($decode)
            {
                return $this->encryptHandler->decrypt(file_get_contents($this->file));
            }

            return file_get_contents($this->file);
        }

        return false;
    }

    public function lock(int $lockLevel = 1, int $sleep = 0): bool
    {
        if (file_exists($this->file))
        {
            $fp = fopen($this->file, 'r');

            switch ($lockLevel) 
            {
                case 1:
                    flock($fp, LOCK_SH);
                    
                    if ($sleep !== 0)
                    {
                        for($i = 0; $i < $sleep; $i++)
                        {
                            sleep($sleep);
                        }
                    }
                    
                    flock($fp, LOCK_UN);
                    break;
                case 2:
                    flock($fp, LOCK_SH);
                    if ($sleep != 0)
                    {
                        for ($i = 0; $i < $sleep; $i++) {
                            sleep($sleep);
                        }
                    }

                    flock($fp, LOCK_UN);

                    break;
            }
        }
        
        return false;
    }

    public function delete()
    {
        if (file_exists($this->file))
        {
            return unlink($this->file);
        }
        
        return false;
    }
}
