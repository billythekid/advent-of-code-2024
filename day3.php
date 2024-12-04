<?php

$regex = '/mul\((?P<num1>\d{1,3}),(?P<num2>\d{1,3})\)/';

$inputs = file_get_contents('inputs/day3.txt');

$lines = explode("\n", $inputs);
$lines = array_filter($lines);

$products = [];
$total = 0;
$matches = [];
foreach ($lines as $line) {
    preg_match_all($regex, $line, $matches[]);
}
foreach ($matches as $match) {
    // make sure they're ints
    $match['num1'] = array_map('intval', $match['num1']);
    $match['num2'] = array_map('intval', $match['num2']);
    foreach ($match['num1'] as $key => $num1) {
        $product = $num1 * $match['num2'][$key];
        $products[] = [
            'match' => $match[0][0],
            'num1' => $num1,
            'num2' => $match['num2'][$key],
            'product' => $product
        ];
        $total += $product;
    }
}

var_dump($total); // 183788984

// part 2

$joinedInput = implode("\n", $lines);
$total = 0;

$mulEnabled = true;
$thingsToKeep = "/(do\(\)|don't\(\)|mul\(\d{1,3},\d{1,3}\))/";
preg_match_all($thingsToKeep, $joinedInput, $strippedInput);
foreach ($strippedInput[0] as $key => $command) {
    if ($command === 'do()') {
        $mulEnabled = true;
    } elseif ($command === "don't()") {
        $mulEnabled = false;
    }
    if (strpos($command, 'mul') !== false && $mulEnabled) {
        preg_match_all($regex, $command, $matches);
        $product = $matches['num1'][0] * $matches['num2'][0];
        $total += $product;
    }
}
var_dump($total); // 62098619
