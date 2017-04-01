<?php

require_once 'Parser.php';

class Solver
{
	private $expression_tree;
	private $expression;

	public function solveExpression(string $expression)
	{
		try {
			$tree = Parser::fromString($expression);
		} catch (\Throwable $e) {
			printf("%yeooooo\n");
		}

		return self::solve($tree);
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
