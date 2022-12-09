<?php
include(__DIR__."/_loader.php");

$input = InputHelper::loadFile("datasets/day9.txt");

// Part 1
$head = new Knot();
$tail = new Knot();

foreach ($input as $line) {
    list($direction, $nb) = explode(' ', $line);

    for ($i=0; $i<$nb; $i++) {
        $head->move($direction);
        $tail->follow($head);
    }
}

$answer = count(array_unique($tail->history));

echo "Answer #1: ".$answer.PHP_EOL;


// Part 2
$knots = [];
for ($k=0; $k<10; $k++) $knots[$k] = new Knot();

foreach ($input as $line) {
    list($direction, $nb) = explode(' ', $line);

    for ($i=0; $i<$nb; $i++) {
        $knots[0]->move($direction);
        for ($k=1; $k<10; $k++) {
            $knots[$k]->follow($knots[($k-1)]);
        }
    }
}

$answer = count(array_unique($knots[9]->history));

echo "Answer #2: ".$answer.PHP_EOL;



class Knot {
    public $x = 0;
    public $y = 0;
    public $history = ['0_0'];

    // "R" for Right -- or "RU" for Right+Up
    public function move($directions) {
        $directions = str_split($directions);

        foreach ($directions as $direction) {
            switch ($direction) {
                case 'R': $this->x++; break;
                case 'L': $this->x--; break;
                case 'U': $this->y++; break;
                case 'D': $this->y--; break;
            }
        }
        
        $this->history[] = $this->x."_".$this->y;
    }

    public function follow($knot) {
        $diffX = ($knot->x - $this->x);
        $diffY = ($knot->y - $this->y);
        if (abs($diffX) <= 1 && abs($diffY) <= 1) return;

        // single axis
        if ($diffY == 0) $this->move(($diffX == 2) ? 'R' : 'L');
        elseif ($diffX == 0) $this->move(($diffY == 2) ? 'U' : 'D');

        // diagonal
        else {
            if ($diffX == 2) $this->move('R' . (($diffY > 0) ? 'U' : 'D') );
            elseif ($diffX == -2) $this->move('L' . (($diffY > 0) ? 'U' : 'D') );
            elseif ($diffY == 2) $this->move('U' . (($diffX > 0) ? 'R' : 'L') );
            elseif ($diffY == -2) $this->move('D' . (($diffX > 0) ? 'R' : 'L') );
        }
    }
}
