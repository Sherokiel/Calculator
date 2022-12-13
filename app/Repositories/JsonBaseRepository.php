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

        return ($content === '') ? [] : json_decode($content, true);
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

        file_put_contents($this->filePath, json_encode($contents));

        return $item;
    }

    public function allGroupedBy($field)
    {
        if (!in_array($field, $this->getEntityFields())) {
            return throw new Exception("Field {$field} is not valid.");
        }

        return array_group($this->all(), $field);
    }

    public function isExist($condition = [])
    {
        foreach ($this->all() as $value) {
            if ($this->isSuitableRecord($condition, $value)) {
                return true;
            }
        }

        return false;
    }

    public function get($condition)
    {
        return array_filter($this->all(), function ($value) use ($condition) {
            return $this->isSuitableRecord($condition, $value);
        });
    }

    protected function isSuitableRecord($condition, $value)
    {
        return array_intersect_assoc($condition, $value) === $condition;
    }

    public function first($condition)
    {
        foreach ($this->all() as $value) {
            if ($this->isSuitableRecord($condition, $value)) {
                return $value;
            }
        }
        return null;
    }
    abstract protected function getEntityFields(): array;
}
