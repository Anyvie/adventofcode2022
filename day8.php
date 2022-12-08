<?php
include(__DIR__."/_loader.php");

$input = InputHelper::loadFile("datasets/day8.txt");

// Part 1
$answer = 0;

$matrix = [];
foreach ($input as $line) $matrix[] = str_split($line);

$width = count($matrix[0]);
$height = count($matrix);

foreach ($matrix as $x => $row) {
    foreach ($row as $y => $tree) {
        // edge
        if (! isset($matrix[($x-1)]) || ! isset($matrix[($x+1)]) || ! isset($matrix[$x][($y-1)]) || ! isset($matrix[$x][($y+1)])) {
            $answer++;
            continue;
        }

        $maxUp = $maxLeft = $maxDown = $maxRight = 0;

        for ($i=0; $i<$x; $i++) $maxUp = max($maxUp, $matrix[$i][$y]);
        for ($i=$x+1; $i<$height; $i++) $maxDown = max($maxDown, $matrix[$i][$y]);
        for ($i=0; $i<$y; $i++) $maxLeft = max($maxLeft, $matrix[$x][$i]);
        for ($i=$y+1; $i<$width; $i++) $maxRight = max($maxRight, $matrix[$x][$i]);
        
        if ($maxUp < $tree || $maxLeft < $tree || $maxDown < $tree || $maxRight < $tree) $answer++;
    }
}

echo "Answer #1: ".$answer.PHP_EOL;


// Part 2
$answer = 0;

$matrix = [];
foreach ($input as $line) $matrix[] = str_split($line);

$width = count($matrix[0]);
$height = count($matrix);

foreach ($matrix as $x => $row) {
    foreach ($row as $y => $tree) {
        // edge
        if (! isset($matrix[($x-1)]) || ! isset($matrix[($x+1)]) || ! isset($matrix[$x][($y-1)]) || ! isset($matrix[$x][($y+1)])) continue;

        $score = 1;
        $scoreBuffer = 0;
        $maxUp = $maxLeft = $maxDown = $maxRight = 0;

        for (
            $i=$x-1, $scoreBuffer=0;
            $i>=0 && $tree>$maxUp;
            $i--, $scoreBuffer++
        ) $maxUp = max($maxUp, $matrix[$i][$y]);

        $score *= $scoreBuffer;

        for (
            $i=$x+1, $scoreBuffer=0;
            $i<$height && $tree>$maxDown;
            $i++, $scoreBuffer++
        ) $maxDown = max($maxDown, $matrix[$i][$y]);

        $score *= $scoreBuffer;
        
        for (
            $i=$y-1, $scoreBuffer=0;
            $i>=0 && $tree>$maxLeft;
            $i--, $scoreBuffer++
        ) $maxLeft = max($maxLeft, $matrix[$x][$i]);

        $score *= $scoreBuffer;
        
        for (
            $i=$y+1, $scoreBuffer=0;
            $i<$width && $tree>$maxRight;
            $i++, $scoreBuffer++
        ) $maxRight = max($maxRight, $matrix[$x][$i]);

        $score *= $scoreBuffer;

        $answer = max($answer, $score);
    }
}

echo "Answer #2: ".$answer.PHP_EOL;
