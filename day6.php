<?php
include(__DIR__."/_loader.php");

$input = file_get_contents("datasets/day6.txt");

// Part 1

$answer = 0;

$chars = str_split($input);
$buffer = [];

foreach ($chars as $c) {
    $answer++;

    $buffer[] = $c;

    if (count($buffer) < 4) continue;
    if (count(array_unique($buffer)) == 4) break;

    array_shift($buffer);
}

echo "Answer #1: ".$answer.PHP_EOL;


// Part 2

$answer = 0;

$chars = str_split($input);
$buffer = [];

foreach ($chars as $c) {
    $answer++;

    $buffer[] = $c;

    if (count($buffer) < 14) continue;
    if (count(array_unique($buffer)) == 14) break;

    array_shift($buffer);
}


echo "Answer #2: ".$answer.PHP_EOL;
