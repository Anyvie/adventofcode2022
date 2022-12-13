<?php
include(__DIR__."/_loader.php");

$input = InputHelper::loadFile("datasets/day13.txt");

// adding 1 more blank line on purpose
$input[] = "";

// Part 1
$answer = 0;

$numPair = 1;
$pairs = [];

foreach ($input as $line) {
    if (! strlen($line)) {
        if (comparePairs($pairs) >= 0) $answer += $numPair;
        $numPair++;
        $pairs = [];
        continue;
    }

    $pairs[] = $line;
}

echo "Answer #1: ".$answer.PHP_EOL;

// Part 2

$signals = [];

foreach ($input as $line) if (strlen($line)) $signals[] = $line;

$signals[] = "[[2]]";
$signals[] = "[[6]]";

$max = count($signals);
$ok = 0;
while (! $ok) {
    $ok = 1;
    for ($i=0; $i<($max-1); $i++) {
        $result = comparePairs([$signals[$i], $signals[$i+1]]);
        if ($result < 0) {
            $tmp = $signals[$i];
            $signals[$i] = $signals[$i+1];
            $signals[$i+1] = $tmp;
            $ok = 0;
        }
    }
}

foreach ($signals as $k => $signal) $signals[$k] = json_encode($signal);
$signals = array_flip($signals);

$pos1 = $signals['"[[2]]"']+1;
$pos2 = $signals['"[[6]]"']+1;

echo "Answer #2: {$pos1} * {$pos2} = ".($pos1*$pos2).PHP_EOL;


function comparePairs($pairs) {
    if (count($pairs) != 2) return -1;

    $left = $pairs[0];
    if (! is_array($left))$left = json_decode($left, true);

    $right = $pairs[1];
    if (! is_array($right))$right = json_decode($right, true);

    if (is_array($left) && empty($left)) {
        if (is_array($right) && empty($right)) return 0;
        return 1;
    }

    foreach ($left as $k=>$litem) {
        if (! isset($right[$k])) return -1;
        $ritem = $right[$k];

        if (is_numeric($litem) && is_numeric($ritem)) {
            if ($litem == $ritem) continue;
            return ($litem < $ritem) ? 1 : -1;
        }

        if (is_numeric($litem)) $litem = [$litem];
        if (is_numeric($ritem)) $ritem = [$ritem];
        $result = comparePairs([$litem, $ritem]);
        if ($result == 0) continue;
        return $result;
    }

    if (isset($right[$k+1])) return 1;

    return 0;
}
