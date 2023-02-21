<?php
namespace Interface;

interface BaseRepositoryInterface
{
    public function all();
    public function create($item);
    public function allGroupedBy($field);
    public function isExist($condition = []);
    public function first($condition = []);
    public function get($condition);
    public function getGroupedBy($field, $condition = []);

}
