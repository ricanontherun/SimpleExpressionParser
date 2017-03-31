<?php

require_once 'Parser.php';

class Solver
{
	private $expression_tree;
	private $expression;

	public function solveExpression(string $expression)
	{
		$tree = Parser::fromString($expression);

		return self::solve($tree);
	}

	private function solve(Tree $root)
	{
		if ( is_numeric($root->root)  ) {
			return intval($root->root);
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
				return self::solve($root->left) / self::solve($root->right);
				break;
		}
	}
}
