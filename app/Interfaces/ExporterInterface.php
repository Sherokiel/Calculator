<?php

namespace App\Interfaces;

interface ExporterInterface
{
    public function export(array $condition = []);
}