<?php

namespace ricanontherun\ExpressionSolver;

class ParseException extends \Exception {}

class ExpressionParts
{
	public $left = null;
	public $operator = null;
	public $right = null;

	public function valid() : bool
	{
		return !empty($this->left) && !empty($this->operator) && !empty($this->right);
	}
}

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

		$root = new Tree;

		self::parse($root, $expression);

		return $root;
	}

	private static function cleanInput(string &$expression)
	{
		// Remove all whitespaces.
		$expression = str_replace(' ', '', $expression);
	}

	private static function parse(Tree $root, string $expression)
	{
		if ( is_numeric($expression) ) {
			$root->type = self::TYPE_OPERAND;
			$root->root = $expression;

			return;
		}

		$parts = self::splitExpression($expression);

		if ( !$parts->valid() ) {
			throw new ParseException("Malformed expression");
		}

		$root->type = self::TYPE_OPERATOR;
		$root->root = $parts->operator;

		$root->left = new Tree;
		$root->right = new Tree;

		self::parse($root->left, $parts->left);
		self::parse($root->right, $parts->right);
	}

	private static function splitExpression(string $expression) : ExpressionParts
	{
		$parts = new ExpressionParts;
		$index = self::findNextOperator($expression);

		if ( !$index ) {
			return $parts;
		}

		$parts->left = trim(substr($expression, 0, $index));
		$parts->operator = $expression[$index];
		$parts->right = trim(substr($expression, $index + 1));

		return $parts;
	}

	private static function findNextOperator(string $expression)
	{
		$smallest_seen_thus_far = PHP_INT_MAX;
		$index = PHP_INT_MAX;

		// TODO: Handle Paren
		for ( $i = 0; $i < strlen($expression); $i++ ) {
			$current_character = $expression[$i];

			if ( is_numeric($current_character) ) {
				continue;
			}

			if ( self::isOperator($current_character) ) {
				$operator_precendence = self::$operator_precendence[$current_character];

				if ( $operator_precendence <= $smallest_seen_thus_far ) {
					$smallest_seen_thus_far = $operator_precendence;
					$index = $i;
				}
			}
		}

		return $index !== PHP_INT_MAX ? $index : false;
	}

	private static function isOperator(string $token) : bool
	{
		return array_key_exists($token, self::$operator_precendence);
	}
}
