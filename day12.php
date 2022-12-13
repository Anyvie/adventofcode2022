<?php
include(__DIR__."/_loader.php");

$input = InputHelper::loadFile("datasets/day12.txt");

$elevations = array_flip(str_split("abcdefghijklmnopqrstuvwxyz"));

$map = [];
foreach ($input as $line) $map[] = str_split($line);

$current = [];
$end = "";
$visited = [];

foreach ($map as $y => $xe) {
    foreach ($xe as $x => $e) {
        if ($e == 'S') {
            $current = [$x, $y];
            $e = 'a';
        } elseif ($e == 'E') {
            $end = $x.'_'.$y;
            $e = 'z';
        }
        $map[$y][$x] = strtr($e, $elevations);
    }
}

// Part 1
$answer = 0;
$max_attempts = count($map)*count($map[0]);
$answer = moveDirection($current, 0, $map, $end);

echo "Answer #1: ".$answer.PHP_EOL;

// Part 2

foreach ($map as $y => $xe) {
    foreach ($xe as $x => $e) {
        if ($e == 0) {
            $move = moveDirection([$x,$y], 0, $map, $end);
            if ($move <= 0) $move = 1000000;
            $answer = min($answer, $move);
        }
    }
}

echo "Answer #2: ".$answer.PHP_EOL;


function moveDirection($current, $nb, $map, $end) {
    global $max_attempts;
    if ($nb >= $max_attempts) return -1;

    global $visited;
    $key = $current[0]."_".$current[1];

    if ($key == $end) {
        $max_attempts = $nb;
        return $nb;
    }

    if (isset($visited[$key]) && $visited[$key] <= $nb) return -1;
    $visited[$key] = $nb;

    $vectors = [
        [0,  1],
        [1,  0],
        [0,  -1],
        [-1, 0],
    ];

    $min = 1000000;
    $max = $map[$current[1]][$current[0]] + 1;

    foreach ($vectors as $vector) {
        $next = [
            $current[0] + $vector[0],
            $current[1] + $vector[1]
        ];

        if (($map[$next[1]][$next[0]] ?? 1000) > $max) continue;

        $move = moveDirection($next, ($nb+1), $map, $end);
        if ($move > 0) $min = min($min, $move);
    }
    
    if ($min == 1000000) return -1000;
    return $min;
}
