<?php

include("secret/classes.php");

// Parse the markdown table
$lines = explode("\n", $markdownTable);
$issues = [];

foreach ($lines as $line) {
    $columns = explode("|", $line);
    if (count($columns) >= 4) {
        $className = trim($columns[1]);
        $estimatedTime = trim($columns[4]) . 'h'; // Append 'h' for hours
        $startComplex = trim($columns[2]);
        $targetComplex = trim($columns[3]);
        $issues[] = [
            'className' => $className,
            'estimatedTime' => $estimatedTime,
            'startComplex' => $startComplex,
            'targetComplex' => $targetComplex,
        ];
    }
}