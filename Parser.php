<?php

require_once 'Tree.php';

class Parser
{
	private static $operator_precendence = [
		'+' => 0,
		'-' => 0,
		'*' => 1,
		'/' => 1
	];

	const TYPE_OPERATOR = 1;
	const TYPE_OPERAND = 2;

	public static function fromString(string $expression) : Tree
	{
		self::cleanInput($expression);

		$root = new Tree;

		self::parse($root, $expression);

		return $root;
	}

	private static function cleanInput(string $expression)
	{
		// Remove all whitespaces.
		return str_replace('/\s/', '', $expression);
	}

	private static function parse(Tree $root, string $expression)
	{
		if ( is_numeric($expression) ) {
			$root->type = self::TYPE_OPERAND;
			$root->root = $expression;

			return;
		}

		$left = $right = $operator =  '';
		self::splitExpression($expression, $left, $operator, $right);

		$root->type = self::TYPE_OPERATOR;
		$root->root = $operator;

		$root->left = new Tree;
		$root->right = new Tree;

		self::parse($root->left, $left);
		self::parse($root->right, $right);
	}

	private static function splitExpression(string $expression, &$left = null, &$operator = null, &$right = null)
	{
		$index = self::findNextOperator($expression);

		// Find the last operator of least precendence.
		// The the content to the left and right of the operator.
		$left = substr($expression, 0, $index);
		$operator = $expression[$index];
		$right = substr($expression, $index + 1);
	}

	private static function findNextOperator(string $expression)
	{
		$smallest_seen_thus_far = PHP_INT_MAX;
		$index = PHP_INT_MAX;

		for ( $i = 0; $i < strlen($expression); $i++ ) {
			$current_character = $expression[$i];

			if ( is_numeric($current_character) ) {
				continue;
			}

			if ( self::$operator_precendence[$current_character] <= $smallest_seen_thus_far ) {
				$smallest_seen_thus_far = self::$operator_precendence[$current_character];
				$index = $i;
			}
		}

		return $index;
	}
}
