<?php

use PHPUnit\Framework\TestCase;
use ricanontherun\ExpressionSolver\Solver;
use ricanontherun\ExpressionSolver\ParseException;

class ExpressionTests extends TestCase
{
	private $solver;

	public function __construct()
	{
		parent::__construct();

		$this->solver = new Solver;
	}

	public function testThatItThrowsAnExceptionWhenANonExpressionIsProvided()
	{
		$this->expectException(ricanontherun\ExpressionSolver\ParseException::class);

		$answer = $this->solver->solveExpression('THIS IS + NOT A VALID * EXPRESSION');
	}

	public function testThatItThrowsAnExceptionWhenMissingARightOperand()
	{
		$this->expectException(ricanontherun\ExpressionSolver\ParseException::class);

		$answer = $this->solver->solveExpression('1-');
	}

	public function testThatItThrowsAnExceptionWhenMissingALeftOperand()
	{
		$this->expectException(ricanontherun\ExpressionSolver\ParseException::class);

		$answer = $this->solver->solveExpression('*200');
	}

	public function testThatItThrowsAnExceptionWhenMissingAnOperator()
	{
		$this->expectException(ricanontherun\ExpressionSolver\ParseException::class);

		$answer = $this->solver->solveExpression('200 199');
	}

	public function testBasicExpressions()
	{
		$this->assertEquals($this->solver->solveExpression('1 + 1'), 2);

		$this->assertEquals($this->solver->solveExpression('3 + 4 / 2 - 5'), 0);

		$this->assertEquals($this->solver->solveExpression('3+4-5*6'), -23);

		$this->assertEquals($this->solver->solveExpression('3+4/2-5'), 0);

		$this->assertEquals(round($this->solver->solveExpression('1+2/3'), 3), 1.667);
	}

	public function testThatItThrowsAnExceptionWhenTheExpressionIsMissingAClosingParen()
	{
		$this->expectException(ricanontherun\ExpressionSolver\ParseException::class);
		$this->expectExceptionMessage("Malformed expression, expecting )");

		$answer = $this->solver->solveExpression('1+(3*(1+3)+4-(6/3)');
	}

	public function testBasicExpressionsWithParenthesis()
	{
		$this->assertEquals($this->solver->solveExpression("1+(3*(1+3)+4)-(6/3)"), 15);
		$this->assertEquals($this->solver->solveExpression("(5-3)*(5+3)"), 16);
		$this->assertEquals($this->solver->solveExpression("7 + (6 * 25 + 3)"), 160);
	}
}

