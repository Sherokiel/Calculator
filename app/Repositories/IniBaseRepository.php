<?php

namespace App\Repositories;

class IniBaseRepository extends FileBaseRepository
{
    public function __construct($fileName)
    {
        return parent::__construct($fileName . '.ini', 'settings');
    }

    public function all()
    {
        $content = parse_ini_file($this->filePath, true);

        return (is_null($content)) ? [] : $content;
    }
}

