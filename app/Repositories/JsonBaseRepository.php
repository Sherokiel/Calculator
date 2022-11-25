<?php

namespace App\Repositories;

class JsonBaseRepository extends FileBaseRepository
{
    public function __construct($fileName)
    {
        return parent::__construct($fileName . '.json', 'data_storage');
    }

    public function all()
    {
        $content = file_get_contents($this->filePath);

        return (is_null($content)) ? [] : json_decode($content, true);
    }

    public function create($item)
    {
        $contents = $this->all();

        $contents[] = $item;

        return file_put_contents($this->filePath, json_encode($contents));
    }
}
