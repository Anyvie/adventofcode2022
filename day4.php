<?php
include(__DIR__."/_loader.php");

$input = InputHelper::loadFile("datasets/day4.txt");

// Part 1
$total = 0;

foreach ($input as $line) {
    $pairs = explode(',', $line);
    $ranges = [];
    foreach ($pairs as $pair) {
        $tmp = explode('-', $pair);
        $ranges[] = range($tmp[0], $tmp[1]);
    }
    foreach ($ranges as $k => $range) {
        foreach ($ranges as $k2 => $range2) {
            if ($k == $k2) continue;
            $common = array_intersect($range, $range2);
            if ($common == $range || $common == $range2) {
                $total++;
                break 2;
            }
        }
    }
}

echo "Answer #1: ".$total.PHP_EOL;


// Part 2
$total = 0;

foreach ($input as $line) {
    $pairs = explode(',', $line);
    $ranges = [];
    foreach ($pairs as $pair) {
        $tmp = explode('-', $pair);
        $ranges[] = range($tmp[0], $tmp[1]);
    }
    foreach ($ranges as $k => $range) {
        foreach ($ranges as $k2 => $range2) {
            if ($k == $k2) continue;
            $common = array_intersect($range, $range2);
            if (! empty($common)) {
                $total++;
                break 2;
            }
        }
    }
}

echo "Answer #2: ".$total.PHP_EOL;
