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

    public function create($item)
    {
        $entityFields = $this->getEntityFields();
        $fieldsToInsert = array_intersect_key($item, array_flip($entityFields));

        if (count($fieldsToInsert) !== count($entityFields)) {
            throw new Exception('One of required fields does not filled.');
        }

        $contents = $this->all();
        $contents[] = $item;

        return file_put_contents($this->filePath, json_encode($contents));
    }

    public function allGroupedBy($field)
    {
        if (!in_array($field, $this->getEntityFields())) {
            return throw new Exception("Field {$field} is not valid.");
        }

        return array_group($this->all(), $field);
    }

    public function isExist()
    {
        $keys = $this->allGroupedBy('date');

        foreach ($keys as $key => $value) {
            $groupKeys[] = $key;

        }

        return $isExist = in_array( '21-11-2022', $groupKeys);
    }

    abstract protected function getEntityFields(): array;
}
