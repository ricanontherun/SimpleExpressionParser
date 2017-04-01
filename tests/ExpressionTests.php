<?php

use PHPUnit\Framework\TestCase;
use ricanontherun\ExpressionSolver\Solver;

class ExpressionTests extends TestCase
{
	public function testThatItThrowsAnExceptionWhenANonExpressionIsProvided()
	{
		$this->expectException(ricanontherun\ExpressionSolver\ParseException::class);

		$solver = new Solver;
		$answer = $solver->solveExpression('THIS IS + NOT A VALID * EXPRESSION');
	}

	public function testThatItThrowsAnExceptionWhenMissingARightOperand()
	{
		$this->expectException(ricanontherun\ExpressionSolver\ParseException::class);

		$solver = new Solver;
		$answer = $solver->solveExpression('1-');
	}

	public function testThatItThrowsAnExceptionWhenMissingALeftOperand()
	{
		$this->expectException(ricanontherun\ExpressionSolver\ParseException::class);

		$solver = new Solver;
		$answer = $solver->solveExpression('*200');
	}

	public function testThatItThrowsAnExceptionWhenMissingAnOperator()
	{
		$this->expectException(ricanontherun\ExpressionSolver\ParseException::class);

		$solver = new Solver;
		$answer = $solver->solveExpression('200 199');
	}

	public function testBasicExpressions()
	{
		$solver = new Solver;

		// spaces
		$this->assertEquals($solver->solveExpression('1 + 1'), 2);

		// Lots of spaces.
		$this->assertEquals($solver->solveExpression('3   + 4/       2- 5 '), 0);

		// No spaces.
		$this->assertEquals($solver->solveExpression('3+4-5*6'), -23);

		// PEMDAS
		$this->assertEquals($solver->solveExpression('3+4/2-5'), 0);

		$this->assertEquals(round($solver->solveExpression('1+2/3'), 3), 1.667);
	}
}

