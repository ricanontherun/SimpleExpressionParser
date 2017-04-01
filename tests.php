<?php

require_once __DIR__ . '/vendor/autoload.php';

use ricanotherun\ExpressionSolver\Solver;

$solver = new Solver();

echo $solver->solveExpression($argv[1]) . PHP_EOL;

