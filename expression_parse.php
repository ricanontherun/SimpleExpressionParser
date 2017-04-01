<?php

require_once 'Solver.php';

$solver = new Solver();

echo $solver->solveExpression($argv[1]) . PHP_EOL;

