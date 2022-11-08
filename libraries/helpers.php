<?php

function array_group($array, $arrayGroup, $groupKey, $groupValue)
{
    foreach ($array as $groupItem) {
        $arrayGroup[$groupItem["{$groupKey}"]][] = $groupItem["{$groupValue}"];
    }

    return $arrayGroup;
}