<?php
include(__DIR__."/_loader.php");

$input = InputHelper::loadFile("datasets/day1.txt");

// Part 1
$max = 0;
$items = [];

foreach ($input as $line) {
    if (strlen($line)) {
        $items[] = $line;
        continue;
    }

    $sum = array_sum($items);
    if ($sum > $max) $max = $sum;
    $items = [];
}

$sum = array_sum($items);
if ($sum > $max) $max = $sum;
$items = [];

echo "Answer #1: ".$max.PHP_EOL;


// Part 2
$cursor = 0;
$bags = [0 => []];

foreach ($input as $line) {
    if (strlen($line)) {
        $bags[$cursor][] = $line;
        continue;
    }

    $cursor++;
    $bags[$cursor] = [];
}

$sums = [];
foreach ($bags as $bag) $sums[] = array_sum($bag);

sort($sums);

$total = 0;

for ($i=0; $i<3; $i++) $total += array_pop($sums);

echo "Answer #2: ".$total.PHP_EOL;
