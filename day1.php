<?php

$input = file_get_contents('inputs/day1.txt');
// input is a long list of numbers in two columns, we need to split and sort each column
$numbercolumns = explode("\n", $input);
$column1 = [];
$column2 = [];
foreach ($numbercolumns as $row) {
    if (empty($row)) {
        continue;
    }
    $numbers = explode("   ", $row);
    $column1[] = $numbers[0];
    $column2[] = $numbers[1];
}
sort($column1);
sort($column2);

$distances = [];
foreach ($column1 as $key => $num1) {
    $num2 = $column2[$key];
    $distances[] = abs($num1 - $num2);
}

$total = array_sum($distances);

var_dump($total); // 118930414

$similarityScores = [];
// part 2 - similarity score
foreach ($column1 as $num1) {
    // how many times does num1 appear in column2?
    $count = array_count_values($column2)[$num1] ?? 0;
    $similarityScores[] = $num1 * $count;
}

$similarity = array_sum($similarityScores);

var_dump($similarity); // 24349736

