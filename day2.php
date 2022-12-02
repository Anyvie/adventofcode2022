<?php
include(__DIR__."/_loader.php");

$input = InputHelper::loadFile("datasets/day2.txt");

// Part 1
$total = 0;

$scores = [
    'Rock' => 1,
    'Paper' => 2,
    'Scissors' => 3,
];
$mapping = [
    'A' => 'Rock',
    'B' => 'Paper',
    'C' => 'Scissors',
    'X' => 'Rock',
    'Y' => 'Paper',
    'Z' => 'Scissors',
];

foreach ($input as $line) {
    $opp = strtr(substr($line, 0, 1), $mapping);
    $my = strtr(substr($line, 2, 1), $mapping);

    $total += $scores[$my];

    // draw
    if ($opp == $my) $total += 3;
    // win
    elseif (($opp == 'Rock' && $my == 'Paper') || ($opp == 'Paper' && $my == 'Scissors') || ($opp == 'Scissors' && $my == 'Rock')) $total += 6;
}

echo "Answer #1: ".$total.PHP_EOL;


// Part 2
$total = 0;

$mapping = [
    'A' => 'Rock',
    'B' => 'Paper',
    'C' => 'Scissors',
];
$scores = [
    'Rock' => 1,
    'Paper' => 2,
    'Scissors' => 3,
    'X' => 0,
    'Y' => 3,
    'Z' => 6,
];

foreach ($input as $line) {
    $opp = strtr(substr($line, 0, 1), $mapping);
    $my = substr($line, 2, 1);

    $total += $scores[$my];

    // draw
    if ($my == 'Y') $total += $scores[$opp];
    // win
    elseif ($my == 'Z') {
        if ($opp == 'Rock') $total += $scores['Paper'];
        elseif ($opp == 'Paper') $total += $scores['Scissors'];
        else $total += $scores['Rock'];
    } else {
        if ($opp == 'Paper') $total += $scores['Rock']; 
        elseif ($opp == 'Scissors') $total += $scores['Paper']; 
        else $total += $scores['Scissors'];
    }
}

echo "Answer #2: ".$total.PHP_EOL;
