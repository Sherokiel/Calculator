<?php

namespace App\Repositories;

class IniBaseRepository extends FileBaseRepository
{
    protected $dirName = 'data_storage';
    protected $fileName;

    public function __construct($fileName)
    {
        return parent::__construct($fileName . '.ini');
    }

    public function all()
    {
        $content = parse_ini_file("{$this->dirName}/{$this->fileName}", true);

        if (is_null($content)) {
            return [];
        }

        return $content;
    }
}

