<?php

namespace App\Repositories;

use App\Exceptions\CreateWithoutRequiredFieldsException;
use App\Exceptions\InvalidFieldException;

abstract class JsonBaseRepository extends FileBaseRepository
{
    public function __construct($fileName)
    {
        $dirName = getenv('JSON_STORAGE_PATH');

        return parent::__construct("{$fileName}.json", $dirName);
    }

    public function all()
    {
        $content = file_get_contents($this->filePath);

        return ($content === '') ? [] : json_decode($content, true);
    }

    public function create($item)
    {
        $entityFields = $this->getEntityFields();
        $item = array_intersect_key($item, array_flip($entityFields));

        if (count($item) !== count($entityFields)) {
            throw new CreateWithoutRequiredFieldsException;
        }

        $contents = $this->all();
        $contents[] = $item;

        file_put_contents($this->filePath, json_encode($contents, JSON_PRETTY_PRINT));

        return $item;
    }

    public function allGroupedBy($field)
    {
        return $this->getGroupedBy($field, []);
    }

    public function isExist($condition = [])
    {
        return !empty($this->first($condition));
    }

    public function first($condition = [])
    {
        foreach ($this->all() as $value) {
            if ($this->isSuitableRecord($condition, $value)) {
                return $value;
            }
        }

        return null;
    }

    public function get($condition)
    {
        return array_filter($this->all(), function ($value) use ($condition) {
            return $this->isSuitableRecord($condition, $value);
        });
    }

    public function getGroupedBy($field, $condition = [])
    {
        if (!in_array($field, $this->getEntityFields())) {
            throw new InvalidFieldException($field);
        }

        $content = $this->get($condition);

        return array_group($content, $field);
    }

    protected function isSuitableRecord($condition, $value)
    {
        return array_intersect_assoc($condition, $value) === $condition;
    }

    abstract protected function getEntityFields(): array;
}
