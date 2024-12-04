<?php

$inputs = file_get_contents('inputs/day2.txt');

$lines = explode("\n", $inputs);
// remove any blank lines
$lines = array_filter($lines);

$safeCount = 0;

function isSafe(array $numbers): bool
{
    // make sure all numbers are ints
    $numbers = array_map('intval', $numbers);
    $numbers = array_values($numbers);
    $safe = true;
    $maxDiff = 3;
    //check the differences between each number is at most 3
    foreach ($numbers as $key => $num1) {
        $num2 = $numbers[$key + 1] ?? null;
        if ($num2 !== null) {
            if (abs($num1 - $num2) > $maxDiff || abs($num1 - $num2) === 0) {
                $safe = false;
            }
        }
    }

    if ($safe) {
        // check ALL numbers either increase or decrease in order
        $sorted = $numbers;
        sort($sorted);
        if ($sorted !== $numbers && array_reverse($sorted) !== $numbers) {
            $safe = false;
        }
    }
    return $safe;
}

foreach ($lines as $line) {
    $numbers = explode(" ", $line);
    if (isSafe($numbers)) {
        $safeCount++;
    }
}

var_dump($safeCount); // 257

// part 2

function isDampened(array $unsafeArray): bool
{
    $unsafeArray = array_map('intval', $unsafeArray);

    // an array is dampened if we can make it safe by removing any single one of the values
    foreach ($unsafeArray as $key => $num) {
        $copyArray = $unsafeArray;
        unset($copyArray[$key]);
        $copyArray = array_values($copyArray);
        if (isSafe($copyArray)) {
            return true;
        }
    }
    return false;
}

$safeOrDampCount = 0;
foreach ($lines as $line) {
    $numbers = explode(" ", $line);
    $numbers = array_map('intval', $numbers);
    if (isSafe($numbers)) {
        $safeOrDampCount++;
    } else {
        if (isDampened($numbers)) {
            $safeOrDampCount++;
        }
    }
}

var_dump($safeOrDampCount); // 328
