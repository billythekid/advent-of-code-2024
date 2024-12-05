<?php

$input = file_get_contents('inputs/day5.txt');
$requiredPageOrders = explode("\n", explode("\n\n", $input)[0]);
$pagesToPrintIfInOrder = explode("\n", explode("\n\n", $input)[1]);

function isPageInOrder($page, $pages)
{
    global $requiredPageOrders;
    foreach ($requiredPageOrders as $order) {
        list($first, $second) = explode('|', $order);
        if ($first == $page || $second == $page) {
            $firstIndex = array_search($first, $pages);
            $secondIndex = array_search($second, $pages);
            if ($firstIndex !== false && $secondIndex !== false && $firstIndex > $secondIndex) {
                return false;
            }
        }
    }
    return true;
}

function arePagesInOrder($pages)
{
    foreach ($pages as $page) {
        if (!isPageInOrder($page, $pages)) {
            return false;
        }
    }
    return true;
}


$pageLinesInOrder = [];
$pagesNotInOrder = [];

foreach ($pagesToPrintIfInOrder as $line) {
    $pages = explode(',', $line);
    if (arePagesInOrder($pages)) {
        $pageLinesInOrder[] = $line;
    } else {
        $pagesNotInOrder[] = $line;
    }
}

$totalMiddlePages = 0;
foreach ($pageLinesInOrder as $line) {
    $pages = explode(',', $line);
    $centrePage = (int)$pages[floor(count($pages) / 2)];
    $totalMiddlePages += $centrePage;
}

echo "The total of the middle pages is: " . $totalMiddlePages . "\n";

// part 2

function reorderPages($pages)
{
    global $requiredPageOrders;
    $ordered = false;

    while (!$ordered) {
        $ordered = true;
        foreach ($requiredPageOrders as $order) {
            list($first, $second) = explode('|', $order);
            $firstIndex = array_search($first, $pages);
            $secondIndex = array_search($second, $pages);

            if ($firstIndex !== false && $secondIndex !== false && $firstIndex > $secondIndex) {
                // Swap the pages to correct the order
                $temp = $pages[$firstIndex];
                $pages[$firstIndex] = $pages[$secondIndex];
                $pages[$secondIndex] = $temp;
                $ordered = false;
            }
        }
    }

    return $pages;
}

$brokenPageLinesNowInOrder = [];
while (count($pagesNotInOrder) > 0) {
    $line = array_shift($pagesNotInOrder);
    $pages = explode(',', $line);
    $pages = reorderPages($pages);
    if (arePagesInOrder($pages)) {
        $brokenPageLinesNowInOrder[] = implode(',', $pages);
    } else {
        $pagesNotInOrder[] = implode(',', $pages);
    }
}

$totalMiddlePages = 0;
foreach ($brokenPageLinesNowInOrder as $line) {
    $pages = explode(',', $line);
    $centrePage = (int)$pages[floor(count($pages) / 2)];
    $totalMiddlePages += $centrePage;
}

echo "The total of the middle pages after reordering is: " . $totalMiddlePages . "\n";
