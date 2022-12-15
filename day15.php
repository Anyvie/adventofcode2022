<?php
include(__DIR__."/_loader.php");

$input = InputHelper::loadFile("datasets/day15.txt");
$Y_SEARCHED = 2000000;
$MAX_LENGTH = 4000000;

$sensors = $beacons = [];

foreach ($input as $line) {
    $matches = [];
    preg_match("#Sensor at x=(\-?[0-9]*), y=(\-?[0-9]*): closest beacon is at x=(\-?[0-9]*), y=(\-?[0-9]*)#", $line, $matches);
    $sensors[] = ['x' => $matches[1], 'y' => $matches[2], 'dist' => 1000000000];
    $beacons[] = ['x' => $matches[3], 'y' => $matches[4]];
}

// Part 1

$row = [];

foreach ($sensors as $key => $sensor) {
    $xs = $sensor['x']; $ys = $sensor['y'];
    if ($ys == $Y_SEARCHED) $row[$xs] = 'S';

    foreach ($beacons as $beacon) {
        $xb = $beacon['x']; $yb = $beacon['y'];
        if ($yb == $Y_SEARCHED) $row[$xb] = 'B';

        $dist = abs($xb - $xs) + abs($yb - $ys);
        $sensors[$key]['dist'] = min($sensors[$key]['dist'], $dist);
    }

    $d = $sensors[$key]['dist'] - abs($Y_SEARCHED - $ys);
    for (;$d>=0; $d--) {
        $coords = [$xs+$d, $xs-$d];
        foreach ($coords as $c) if (! isset($row[$c])) $row[$c] = '#';
    }
}

$answer = 0;
foreach ($row as $v) if ($v == '#') $answer++;

echo "Answer #1: ".$answer.PHP_EOL;


echo "(please wait - can take a few minutes if you aren't lucky with the input)".PHP_EOL;

// Part 2
$answer = [];
$length = $MAX_LENGTH;

while ($length >= 0) {
    $Y_SEARCHED = $length;

    $rows = [];

    foreach ($sensors as $key => $sensor) {
        $row = ['min'=>$MAX_LENGTH, 'max' => 0];

        $xs = $sensor['x']; $ys = $sensor['y'];
        $d = $sensors[$key]['dist'] - abs($Y_SEARCHED - $ys);

        if ($ys > $Y_SEARCHED) {
            $d = $ys - $sensors[$key]['dist'];
            if ($d > $Y_SEARCHED) continue;
        } else {
            $d = $ys + $sensors[$key]['dist'];
            if ($d < $Y_SEARCHED) continue;
        }

        $diff = abs($Y_SEARCHED - $d);
        $start = max(0, ($xs-$diff));
        $end = ($xs+$diff)+1;

        if ($end > $MAX_LENGTH) $end = $MAX_LENGTH;
        if ($start < 0) $start = 0;

        $rows[$start] = max($end-1, ($rows[$start] ?? 0));
    }

    ksort($rows);
    $cursor = 0;
    foreach ($rows as $min => $max) {
        if (($min-1) > $cursor) { $answer = ['x'=>$cursor+1, 'y'=>$Y_SEARCHED]; break 2; }
        $cursor = max($cursor, $max);
    }

    $length--;
}

echo "Answer #2: ".($answer['x']*4000000 + $answer['y']).PHP_EOL;
