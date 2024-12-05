<?php
$input = file_get_contents('inputs/day4.txt');

function checkDirection($grid, $x, $y, $dx, $dy, $wordLength, $gridSize, $rowSize): string
{
    $foundWord = '';
    for ($i = 0; $i < $wordLength; $i++) {
        $nx = $x + $i * $dx;
        $ny = $y + $i * $dy;
        if ($nx < 0 || $nx >= $gridSize || $ny < 0 || $ny >= $rowSize) {
            return '';
        }
        $foundWord .= $grid[$nx][$ny];
    }
    return $foundWord;
}

function wordSearch($word, $grid, $shape = 'regular'): int
{
    $count = 0;
    $wordLength = strlen($word);
    $gridSize = count($grid);
    $rowSize = strlen($grid[0]);
    $middleIndex = intdiv($wordLength, 2);

    for ($x = 0; $x < $gridSize; $x++) {
        for ($y = 0; $y < $rowSize; $y++) {
            if ($shape === 'regular') {
                $directions = [
                    [0, 1],  // right
                    [0, -1], // left
                    [1, 0],  // down
                    [-1, 0], // up
                    [1, 1],  // down-right
                    [1, -1], // down-left
                    [-1, 1], // up-right
                    [-1, -1] // up-left
                ];
                foreach ($directions as $direction) {
                    $foundWord = checkDirection($grid, $x, $y, $direction[0], $direction[1], $wordLength, $gridSize, $rowSize);
                    if ($foundWord === $word) {
                        $count++;
                    }
                }
            } elseif ($shape === 'X') {
                $directions = [
                    [1, 1],  // down-right
                    [1, -1], // down-left
                ];
                // each X shape shares a middle letter, so let's record those positions
                $foundWordMiddleLetterGridPositions = [];
                foreach ($directions as $direction) {
                    $dx = $direction[0];
                    $dy = $direction[1];
                    $startX = $x - $middleIndex * $dx;
                    $startY = $y - $middleIndex * $dy;
                    $foundWord = checkDirection($grid, $startX, $startY, $dx, $dy, $wordLength, $gridSize, $rowSize);
                    if ($foundWord === $word || $foundWord === strrev($word)) {
                        $foundWordMiddleLetterGridPositions[] = [$startX + $middleIndex * $dx, $startY + $middleIndex * $dy];
                    }
                }
                if (count($foundWordMiddleLetterGridPositions) === 2) {
                    $count++;
                }
            }
        }
    }

    return $count;
}

$grid = explode("\n", $input);
$grid = array_filter($grid);
$words = ['XMAS'];
$shape = 'regular';
$totalWords = 0;
foreach ($words as $word) {
    $totalWords += wordSearch($word, $grid, $shape);
}

var_dump($totalWords); // 1

// part 2

$words = ['MAS'];
$totalWords = 0;
$shape = 'X';
foreach ($words as $word) {
    $totalWords += wordSearch($word, $grid, $shape);
}

var_dump($totalWords); // 2