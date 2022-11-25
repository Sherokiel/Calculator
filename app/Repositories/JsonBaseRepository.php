<?php

namespace App\Repositories;

class JsonBaseRepository extends FileBaseRepository
{
    protected $dirName = 'data_storage';
    protected $fileName;

    public function __construct($fileName)
    {
        return parent::__construct($fileName . '.json');
    }

    public function all()
    {
        $content = file_get_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"));

        return (is_null($content)) ? [] : json_decode($content, true);
    }

    public function create($item)
    {
        $contents = $this->all();

        $contents[] = $item;

        return file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), json_encode($contents));
    }
}
