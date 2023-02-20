<?php

namespace App\Repositories;

class IniBaseRepository extends FileBaseRepository
{
    public function __construct($fileName)
    {
        $dirName = getenv('INI_STORAGE_PATH');

        return parent::__construct("{$fileName}.ini", $dirName);
    }

    public function all()
    {
        $content = parse_ini_file($this->filePath, true);

        return (is_null($content)) ? [] : $content;
    }
}

