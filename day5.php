<?php
include(__DIR__."/_loader.php");

$input = InputHelper::loadFile("datasets/day5.txt", "\r\n");

// Part 1

$isInit = true;
$stacks = [];

foreach ($input as $line) {
    // Switch from building the initial state, and read the orders
    if (! strlen($line)) {
        foreach ($stacks as $k => $stack) $stacks[$k] = array_reverse($stack);
        $isInit = false;
        continue;
    }

    // initial state
    if ($isInit) {
        if (strpos($line, '[') === false) continue;
        
        $line = explode(" ", str_replace(
            ["    [", "]    ", "    "], 
            ["___ [", "] ___", " ___"], 
            $line
        ));

        $i = 0;

        foreach ($line as $cell) {
            $i++;
            if (! isset($stacks[$i])) $stacks[$i] = [];

            $crate = substr($cell, 1, 1);
            if ($crate == '_') continue;
            $stacks[$i][] = $crate;
        }
    } else { // orders
        $matches = [];
        preg_match("#move ([0-9]*) from ([0-9]*) to ([0-9]*)#", $line, $matches);

        $nb = $matches[1];
        $from = $matches[2];
        $dest = $matches[3];

        while ($nb > 0) {
            $crate = array_pop($stacks[$from]);
            $stacks[$dest][] = $crate;
            $nb--;
        }
    }
}

$answer = "";

foreach ($stacks as $stack) {
    $answer .= array_pop($stack);
}

echo "Answer #1: ".$answer.PHP_EOL;


// Part 2

$isInit = true;
$stacks = [];

foreach ($input as $line) {
    // Switch from building the initial state, and read the orders
    if (! strlen($line)) {
        foreach ($stacks as $k => $stack) $stacks[$k] = array_reverse($stack);
        $isInit = false;
        continue;
    }

    // initial state
    if ($isInit) {
        if (strpos($line, '[') === false) continue;
        
        $line = explode(" ", str_replace(
            ["    [", "]    ", "    "], 
            ["___ [", "] ___", " ___"], 
            $line
        ));

        $i = 0;

        foreach ($line as $cell) {
            $i++;
            if (! isset($stacks[$i])) $stacks[$i] = [];

            $crate = substr($cell, 1, 1);
            if ($crate == '_') continue;
            $stacks[$i][] = $crate;
        }
    } else { // orders
        $matches = [];
        preg_match("#move ([0-9]*) from ([0-9]*) to ([0-9]*)#", $line, $matches);

        $nb = $matches[1];
        $from = $matches[2];
        $dest = $matches[3];

        $tmp = [];

        while ($nb > 0) {
            $crate = array_pop($stacks[$from]);
            $tmp[] = $crate;
            $nb--;
        }

        $stacks[$dest] = array_merge($stacks[$dest], array_reverse($tmp));
    }
}

$answer = "";

foreach ($stacks as $stack) {
    $answer .= array_pop($stack);
}

echo "Answer #2: ".$answer.PHP_EOL;
