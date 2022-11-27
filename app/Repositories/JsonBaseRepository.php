<?php

namespace App\Repositories;

use Exception;

abstract class JsonBaseRepository extends FileBaseRepository
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

    abstract protected function getEntityFields();

    public function create($item)
    {
        $defaultJson = $this->getEntityFields();
        $dataContentValid = array_intersect_key($item, array_flip($defaultJson));

        if (count($dataContentValid) !== count($defaultJson)) {
            throw new Exception('Всё сломалось');
        }

        $contents = $this->all();
        $contents[] = $item;

        return file_put_contents($this->filePath, json_encode($contents));
    }
}
