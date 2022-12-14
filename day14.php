<?php
include(__DIR__."/_loader.php");

$input = InputHelper::loadFile("datasets/day14.txt");

// null: hide all the unnecessary output
// "map": show only the map evolution
// "debug": verbose everything
$verbose = null;

// null: no timer (the fastest)
// nb microseconds, eg:
//     150000: 0.15s delay between each tick
$usleep = null;

// Part 1
$answer = 0;

$map = new Map($input);
$map->display();

$status = 0;
while ($status >= 0) {
    $answer++;
    $status = $map->drop(500,0);
    $map->display();
    if (! is_null($usleep)) usleep($usleep);
}

echo "Answer #1: ".($answer-1).PHP_EOL;

// Part 2
$answer = 0;

$map = new Map($input);
$map->addFloor();
$map->display();

$map->allowExtend = true;

$status = 0;
while ($status >= 0) {
    $answer++;
    $status = $map->drop(500,0);
    $map->display();
    if (! is_null($usleep)) usleep($usleep);
}

echo "Answer #2: ".($answer-1).PHP_EOL;


class Map {
    public $map = [];
    public $height = 0;
    public $allowExtend = false;

    public function __construct($input) {
        $this->map = [];

        $blocks = [];
        $minY = 0;
        $minX = 1000000;
        $maxY = $maxX = 0;
        
        // Reading all the blocks of rocks
        foreach ($input as $line) {
            $rocks = [];
            $line = str_replace(' -> ', ' ', $line);
            $coords = explode(' ', $line);
            foreach ($coords as $coord) $rocks[] = explode(',', $coord);
            $blocks[] = $rocks;
        }
        
        // Parsing the blocks to set the map size
        foreach ($blocks as $rocks) {
            foreach ($rocks as $rock) {
                list($x, $y) = $rock;
        
                $minX = min($minX, $x);
                $maxY = max($maxY, $y);
                $maxX = max($maxX, $x);
            }
        }

        $this->height = $maxY;
        
        // Creating the empty map
        for ($y=$minY; $y<=$maxY; $y++) {
            $this->map[$y] = [];
            for ($x=$minX; $x<=$maxX; $x++) $this->map[$y][$x] = '.';
        }
        
        // Adding the rocks
        foreach ($blocks as $rocks) {
            for ($r=0; $r<(count($rocks)-1); $r++) {
                list($x, $y) = $rocks[$r];
                list($x2, $y2) = $rocks[($r+1)];
        
                if ($x == $x2) {
                    $c = min($y, $y2);
                    $max = max($y, $y2);
                    for (;$c<=$max;$c++) $this->map[$c][$x] = '#';
                } else {
                    $c = min($x, $x2);
                    $max = max($x, $x2);
                    for (;$c<=$max;$c++) $this->map[$y][$c] = '#';
                }
            }
        }
        
        // Adding the sand generator
        $this->map[0][500] = '+';
    }

    public function display() {
        global $verbose;
        if (is_null($verbose)) return; 

        foreach ($this->map as $y => $xs) {
            ksort($xs);
            echo str_pad($y, 3, '0', STR_PAD_LEFT)."  ";

            foreach ($xs as $x => $s) {
                echo $s;
            }
            echo PHP_EOL;
        }
        echo PHP_EOL;
    }

    /**
     * @return $status : 
     * -1 : Out of bound
     * 0 : Location [$x,$y] not free
     * 1 : OK, sand added.
     */
    public function drop($x, $y, $sub=0) {
        verbose("drop({$x}, {$y});");
        
        // Out of bound
        if (! isset($this->map[$y])) return -1;
        if (! isset($this->map[$y][$x])) {
            $ret = $this->extend($x); // for PART 2
            if ($ret == -1) return -1;
        }

        // Location not free
        if (! in_array($this->map[$y][$x], ['.','+'])) {
            if ($sub) return 0;
            return -1;
        }

        // straight until rock or map border
        for (; $y < ($this->height - 1); $y++) {
            if (in_array($this->map[($y+1)][$x], ['.','+'])) continue;
            break;
        }

        verbose("down to [{$x}, {$y}]");

        // trying left
        verbose("Trying left");
        $left = $this->drop($x-1, $y+1, 1);
        if ($left == -1) return -1;
        if ($left == 1) return 1;

        // trying right
        verbose("Trying right");
        $right = $this->drop($x+1, $y+1, 1);
        if ($right == -1) return -1;
        if ($right == 1) return 1;

        verbose("Placing sand to [{$x}, {$y}]");
        $this->map[$y][$x] = 'o';

        return 1;
    }

    // -------- PART 2 methods ----------

    public function addFloor() {
        $xs = array_keys($this->map[0]);

        $this->height++;
        $this->map[$this->height] = [];
        foreach ($xs as $x) $this->map[$this->height][$x] = '.';

        $this->height++;
        $this->map[$this->height] = [];
        foreach ($xs as $x) $this->map[$this->height][$x] = '#';
    }

    public function extend($x) {
        verbose("extend({$x});");

        if (! $this->allowExtend) return -1;

        for ($y=0; $y<$this->height; $y++) $this->map[$y][$x] = '.';
        $this->map[$y][$x] = '#';

        return 0;
    }
}

function verbose($msg) {
    global $verbose;
    if ($verbose != 'debug') return; 

    echo $msg.PHP_EOL;
}
