<?php
include(__DIR__."/_loader.php");

$input = InputHelper::loadFile("datasets/day11.txt");

$monkeysInit = [];

foreach ($input as $line) {
    if (! strlen($line)) continue;

    $words = explode(' ', $line);

    switch ($words[0]) {
        case 'Monkey':
            $cursor = str_replace(':', '', $words[1]);
            $monkeysInit[$cursor] = new Monkey();
            $current = $monkeysInit[$cursor];
        break;
        case 'Starting':
            $current->items = explode(',', implode('', array_slice($words, 2)));
        break;
        case 'Operation:':
            $current->formula = str_replace('new = ', '', implode(' ', array_slice($words, 1)));
        break;
        case 'Test:':
            $current->modulo = array_pop($words);
        break;
        case 'If':
            $cond = str_replace(':', '', $words[1]);
            $current->{$cond} = array_pop($words);
        break;
    }
}

// Part 1
$monkeys = unserialize(serialize($monkeysInit));

$rounds = 20;
while ($rounds > 0) {
    foreach ($monkeys as $monkey) $monkey->part1();
    $rounds--;
}

$stats = [];
foreach ($monkeys as $monkey) $stats[] = $monkey->tested;
rsort($stats);

echo "Answer #1: ".array_product(array_slice($stats, 0, 2)).PHP_EOL;


// Part 2

// I was WRONG here :)
// I guessed the correct modulo for example data
// and found by luck that it was the product of all monkeys modulos (see after)

// 1. test if modulo can do the trick (it's a pure guess, not sure)
// It takes quite some time to exec.
// Output: [278 => 96577]
/*
$candidates = [];
$expected = [99, 97, 8, 103];
$mod = 1;
while ($mod < 100000) {
    $monkeys = unserialize(serialize($monkeysInit));

    $rounds = 20;
    while ($rounds > 0) {
        foreach ($monkeys as $monkey) $monkey->part2($mod);
        $rounds--;
    }

    $stats = [];
    foreach ($monkeys as $monkey) $stats[] = $monkey->tested;
    
    if ($stats == $expected) $candidates[] = $mod;
    $mod++;
}

$expected = [10419, 9577, 392, 10391];
foreach ($candidates as $k => $mod) {
    $monkeys = unserialize(serialize($monkeysInit));

    $rounds = 2000;
    while ($rounds > 0) {
        foreach ($monkeys as $monkey) $monkey->part2($mod);
        $rounds--;
    }

    $stats = [];
    foreach ($monkeys as $monkey) $stats[] = $monkey->tested;
    
    if ($stats != $expected) unset($candidates[$k]);
}

print_r($candidates);
exit;
*/

$monkeys = unserialize(serialize($monkeysInit));

// Calculating the correct modulo in the good way !
$mod = 1;
foreach ($monkeys as $monkey) $mod *= $monkey->modulo;

$rounds = 10000;
while ($rounds > 0) {
    foreach ($monkeys as $monkey) $monkey->part2($mod);
    $rounds--;
}

$stats = [];
foreach ($monkeys as $monkey) $stats[] = $monkey->tested;
rsort($stats);

echo "Answer #2: ".array_product(array_slice($stats, 0, 2)).PHP_EOL;


class Monkey {
    public $items = [];
    public $tested = 0;
    public $formula = "";
    public $modulo = 0;
    public $true = 0;
    public $false = 0;

    public function part1() {
        global $monkeys;

        foreach ($this->items as $k => $item) {
            $this->tested++;

            // NOT RECOMMENDED TO USE EVAL ! Replace if possible.
            eval("\$item = (".str_replace('old', $item, $this->formula).");");
            $item = floor($item/3);

            $dest = ($item%$this->modulo == 0) ? $this->true : $this->false;
            $monkeys[$dest]->items[] = $item;

            unset($this->items[$k]);
        }
    }

    public function part2($mod) {
        global $monkeys;

        foreach ($this->items as $k => $item) {
            $this->tested++;

            // NOT RECOMMENDED TO USE EVAL ! Replace if possible.
            eval("\$item = (".str_replace('old', $item, $this->formula).");");
            $item = $item%$mod;

            $dest = ($item%$this->modulo == 0) ? $this->true : $this->false;
            $monkeys[$dest]->items[] = $item;

            unset($this->items[$k]);
        }
    }
}
