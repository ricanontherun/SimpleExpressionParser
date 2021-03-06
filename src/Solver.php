<?php

namespace ricanontherun\ExpressionSolver;

class Solver
{
	private $expression_tree;
	private $expression;

	public function solveExpression(string $expression)
	{
		return self::solve(Parser::fromString($expression));
	}

	private function solve(Tree $root)
	{
		if ( is_numeric($root->root)  ) {
			return $root->root;
		}

		switch ( $root->root ) {
		case '+':
			return self::solve($root->left) + self::solve($root->right);
			break;
		case '-':
			return self::solve($root->left) - self::solve($root->right);
			break;
		case '*':
			return self::solve($root->left) * self::solve($root->right);
			break;
		case '/':
			$divisor = self::solve($root->right);
			return !empty($divisor) ? self::solve($root->left) / $divisor : 0;

			break;
		}
	}
}
