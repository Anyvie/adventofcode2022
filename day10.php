<?php
include(__DIR__."/_loader.php");

$input = InputHelper::loadFile("datasets/day10.txt");

// Part 1
$answer = 0;
$X = 1;
$cycles = 1;

function cycle($cycles, $X) {
    if (in_array($cycles, [20,60,100,140,180,220])) return $cycles*$X;
    return 0;
}

foreach ($input as $line) {
    $answer += cycle($cycles, $X);

    $cycles++;
    if ($line == 'noop') continue;

    $answer += cycle($cycles, $X);
    $cycles++;

    $inc = str_replace('addx ', '', $line);
    $X += $inc;
}

echo "Answer #1: ".$answer.PHP_EOL;


// Part 2
$X = 1;
$cycles = 1;

echo "Answer #2: ".PHP_EOL;

function display($cycles, $X) {
    $diff = abs(($cycles-1)%40 - $X);
    echo ($diff <= 1) ? '#' : '.';
    if ($cycles%40 == 0) echo PHP_EOL;
}

foreach ($input as $line) {
    display($cycles, $X);
    $cycles++;
    if ($line == 'noop') continue;

    display($cycles, $X);
    $cycles++;

    $inc = str_replace('addx ', '', $line);
    $X += $inc;
}
