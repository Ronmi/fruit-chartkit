<?php

require('vendor/autoload.php');

$chart = new Fruit\ChartKit\HorizontalBarChart(100);

$chart->add('test1', 100);
$chart->add('test2', 200);
$chart->add('test3', 45.2);
$chart->add('test4', 10);
$chart->add('test5', 11);
$chart->add('test6', 12);
$chart->add('test7', 1);

echo $chart->render();
