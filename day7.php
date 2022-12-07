<?php
include(__DIR__."/_loader.php");

$input = InputHelper::loadFile("datasets/day7.txt");


// Part 1
$answer = 0;

$dirs = getDirs($input);

foreach ($dirs as $dir => $size) {
    if ($size < 100000) $answer += $size;
}

echo "Answer #1: ".$answer.PHP_EOL;


// Part 2

$answer = 0;

$dirs = getDirs($input);

$MAX = 70000000;
$NEEDED = 30000000;

$used = $dirs[''];
$free = $MAX - $used;

$options = [];

foreach ($dirs as $dir => $size) {
    if ($dir == '') continue;
    if ($NEEDED < ($free+$size)) $options[$dir] = $size;
}

arsort($options);
$answer = array_pop($options);

echo "Answer #2: ".$answer.PHP_EOL;




function getDirs($input) {
    $cursor = '';

    $dirs = [
        '' => 0,
    ];
    
    foreach ($input as $line) {
        // command
        if (substr($line, 0, 1) == '$') {
            $cmd = substr($line, 2, 2);
            if ($cmd == 'cd') {
                $path = str_replace('$ cd ', '', $line);
    
                if ($path == '..') {
                    $tmp = explode('/', $cursor);
                    array_pop($tmp);
                    $cursor = implode('/', $tmp);
                }
                elseif ($path == '/') $cursor = '';
                else $cursor .= "/".$path;
            }
        } else { // results
            if (substr($line, 0, 4) == 'dir ') {
                $dir = str_replace('dir ', '', $line);
                $dirs[$cursor.'/'.$dir] = 0;
            } else {
                list($size, $filename) = explode(' ', $line);
                $dirs[$cursor] += $size;
    
                $c = $cursor;
                while (strlen($c)) {
                    $tmp = explode('/', $c);
                    array_pop($tmp);
                    $c = implode('/', $tmp);
                    $dirs[$c] += $size;
                }
            }
        }
    }

    return $dirs;
}

