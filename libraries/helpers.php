<?php

function array_group($array, $arrayGroup)
{
    foreach ($array as $groupItem) {
        $arrayGroup[$groupItem['date']][] = $groupItem['function'];
    }

    return $arrayGroup;
}