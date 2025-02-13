<?php

namespace Core;

class FileHandler extends SymmetricEncryptionHandler
{

    protected $SEncrypt;

    protected $file;

    public function __construct(string $pathFile)
    {
        $this->SEncrypt = new SymmetricEncryptionHandler();

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

    /** 
     * MODS
     *   a,
     *   w,
     * 
     *   if encode = false 
     *      $mode = FILE_APPEND
     *  */
    public function write(string $data, $mode = null, bool $encode = false)
    {
        if (file_exists($this->file))
        {

            if ($encode == true)
            {
                $fp = fopen($this->file, $mode);
                
                stream_filter_append($fp, "convert.base64-encode");
                stream_filter_append($fp, "zlib.deflate");

                fwrite($fp, $data);

                return fclose($fp);
            }

            return file_put_contents($this->file, $data, FILE_APPEND);
        }

        return false;
    }

    public function read(bool $decode = false)
    {
        if (file_exists($this->file))
        {
            if ($decode === true)
            {
                $fp = fopen($this->file, 'r');
                
                stream_filter_append($fp, "zlib.inflate");
                stream_filter_append($fp, "convert.base64-decode");

                return fread($fp, 4096);
            }

            return file_get_contents($this->file);
        }

        return false;
    }

    public function lock(int $lockLevel = 1, int $sleep = 0)
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
