# Expression Solver
Library for solving match expressions via string. Support is very simple at the moment.

``php

use ricanontherun\ExpressionSolver\Solver;

$solver = new Solver();
$answer = $solver->solveExpression('1+2/3'); // ~1.66667
```
