<?php

namespace Src\Helper;

class PrintHelper
{
    public static function printArray(array $arr, string $title)
    {
        $result = "\n$title [";
        foreach ($arr as $j => $value) {
            if($j === 0) {
                $result .= " $value";
            } else {
                $result .= "; $value";
            }
        }
        $result .= " ]\n";
        echo($result);
    }

    public static function printMatrix(?array $matrix, string $title)
    {
        if($matrix === null) return;
        $result = "\n$title [\n";
        foreach ($matrix as $i => $row) {
            foreach ($row as $j => $value) {
                if($j === 0) {
                    $result .= "\t[ $value";
                } else {
                    $result .= "; $value";
                }
            }
            $result .= " ]\n";
        }
        $result .= "]";
        echo($result);
    }
}