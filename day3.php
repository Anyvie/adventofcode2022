<?php
include(__DIR__."/_loader.php");

$input = InputHelper::loadFile("datasets/day3.txt");

// Part 1
$total = 0;

foreach ($input as $line) {
    $middle = strlen($line)/2;

    $half1 = str_split(substr($line, 0, $middle));
    $half2 = str_split(substr($line, -1*$middle));
    $common = @array_pop(array_intersect($half1, $half2));
    
    $score = ord($common);
    if ($score >= 97) $score -= 96;
    elseif ($score >= 65) $score -= (64-26);

    $total += $score;
}

echo "Answer #1: ".$total.PHP_EOL;


// Part 2
$total = 0;
$i = 1;
$lines = [];

foreach ($input as $line) {
    $lines[] = $line;

    if (count($lines) == 3) {
        foreach ($lines as $k => $v) $lines[$k] = str_split($v);

        $common = @array_pop(array_intersect($lines[0], $lines[1], $lines[2]));
        
        $score = ord($common);
        if ($score >= 97) $score -= 96;
        elseif ($score >= 65) $score -= (64-26);
    
        $total += $score;
        $lines = [];
    }

}

echo "Answer #2: ".$total.PHP_EOL;
