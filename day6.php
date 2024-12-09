<?php
error_reporting(E_ERROR);
include 'vendor/autoload.php';

use Ds\Set;

$mapGrid = file_get_contents('inputs/day6.txt');

$directions = [
    "right" => [0, 1],
    "down" => [1, 0],
    "left" => [0, -1],
    "up" => [-1, 0]
];

$rows = explode("\n", $mapGrid);
$cells = [];
$currentDirection = "up";
$positions = new Set();
$steps = 0;
$positionWithDirection = new Set();
$currentPosition = [];

function setupGrid(): void
{

    global $positions, $steps, $currentDirection, $positionWithDirection, $currentPosition;
    global $rows, $cells;

    $cells = [];
    foreach ($rows as $row) {
        $cells[] = str_split($row);
    }

    $steps = 0;
    $currentDirection = "up";
    $positions = new Set();
    $positionWithDirection = new Set();

    for ($i = 0; $i < count($cells); $i++) {
        for ($j = 0; $j < count($cells[$i]); $j++) {
            if ($cells[$i][$j] === '^') {
                $positions[] = [$i, $j];
            }
        }
    }

    $currentPosition = $positions->first();

    $positionWithDirection = new Set([[
        "position" => $currentPosition,
        "direction" => $currentDirection
    ]]);

    echo "Starting at " . $positions->first()[0] . ", " . $positions->first()[1] . "\n";
    echo "Facing " . $currentDirection . "\n";
}

function turnRight($currentDirection)
{
    switch ($currentDirection) {
        case "right":
            return "down";
        case "down":
            return "left";
        case "left":
            return "up";
        case "up":
            return "right";
    }
}

function move(array $position, string $direction): array
{
    global $directions;
    global $cells;
    global $currentDirection;
    /**
     * @var Set $positions
     */
    global $positions;
    global $steps;
    global $positionWithDirection;

    if ($position[0] === '*' || $position[1] === '*') {
        return ['*', '*'];
    }

    $nextCell = [$position[0] + $directions[$direction][0], $position[1] + $directions[$direction][1]];

    if ($nextCell[0] < 0 || $nextCell[0] >= count($cells) || $nextCell[1] < 0 || $nextCell[1] >= count($cells[0])) {
        return ['*', '*'];
    }

    if ($cells[$nextCell[0]][$nextCell[1]] === '.' || $cells[$nextCell[0]][$nextCell[1]] === '^' || $cells[$nextCell[0]][$nextCell[1]] === 'X') {
        $cells[$nextCell[0]][$nextCell[1]] = 'X';
        $positions->add([$nextCell]);
        if ($positionWithDirection->contains(["position" => $nextCell, "direction" => $currentDirection])) {
            return ['*', '*', 'LOOP FOUND'];
        }
        $positionWithDirection->add(["position" => $nextCell, "direction" => $currentDirection]);
        $steps++;
        return $nextCell;
    } elseif ($cells[$nextCell[0]][$nextCell[1]] === '#') {
        $currentDirection = turnRight($direction);
        return $position;
    } else {
        return ['*', '*'];
    }
}

//part 1
setupGrid();

function play()
{
    global $currentPosition, $currentDirection;
    $currentPosition = move($currentPosition, $currentDirection);
    while ($currentPosition[0] !== '*') {
        $currentPosition = move($currentPosition, $currentDirection);
    }
}

play();
echo "Steps taken: " . $steps . "\n";
echo "Total positions visited: " . count($positions) . "\n";
echo "Out of bounds at cell: [" . $positionWithDirection->last()['position'][0] . ',' . $positionWithDirection->last()['position'][1] . "] and facing " . $currentDirection . "\n";

// part 2

$cells = [];
foreach ($rows as $row) {
    $cells[] = str_split($row);
}

// we only need to add a hash to positions in the original flow, not all dots
$dots = $positions->toArray();
array_shift($dots);

$loopsFound = 0;
foreach ($dots as $index => $dot) {
    setupGrid();
    $cells[$dot[0][0]][$dot[0][1]] = '#';
    echo "Hash added at " . $dot[0][0] . ", " . $dot[0][1] . "\n";
    echo "Loops found: " . $loopsFound . "\n";
    echo "Possibilities remaining: " . count($dots) - $index . "\n";
    play();
    if ($currentPosition === ['*', '*', 'LOOP FOUND']) {
//        echo "Loop found at cell: [" . $positionWithDirection->last()['position'][0] . ',' . $positionWithDirection->last()['position'][1] . "] and facing " . $currentDirection . "\n";
        $loopsFound++;
    } elseif ($currentPosition === ['*', '*']) {
//        echo "Out of bounds at cell: [" . $positionWithDirection->last()['position'][0] . ',' . $positionWithDirection->last()['position'][1] . "] and facing " . $currentDirection . "\n";
    }
}
echo "--- Run Complete ---\n";
echo "Loops found: " . $loopsFound . "\n";
echo "Steps taken: " . $steps . "\n";

