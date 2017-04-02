<?php

namespace ricanontherun\ExpressionSolver;

class ParseException extends \Exception {}

function dd($log)
{
	if ( is_object($log) ) {
		var_dump($log);
	} else {
		printf("%s\n", $log);
	}
	exit;
}

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

		self::parse($root, "({$expression})");

		return $root;
	}

	private static function parse(Tree $root, string $expression)
	{
		if ( preg_match('/^\(.+\)$/', $expression) === 1 ) {
			$expression = preg_replace('/(^\()|(\)$)/', '', $expression);
		}

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
		$expression_length = strlen($expression);
		$min_precedence = PHP_INT_MAX;
		$index = PHP_INT_MAX;

		// TODO: Handle Paren
		for ( $i = 0; $i < strlen($expression); $i++ ) {
			$current_character = $expression[$i];

			if ( is_numeric($current_character) || $current_character == ' ' ) {
				continue;
			}

			if ( $current_character == '(' ) {
				$i = self::findMatchingParen($expression, $i);
				$current_character = $expression[$i];
			}

			if ( self::isOperator($current_character) ) {
				$operator_precendence = self::$operator_precendence[$current_character];

				if ( $operator_precendence <= $min_precedence ) {
					$min_precedence = $operator_precendence;
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

	private static function findMatchingParen(string $expression, int $i)
	{
		$paren_level = 0;

		for ( $i; $i < strlen($expression); ++$i ) {
			$current_character = $expression[$i];

			if ($current_character == '(') {
				$paren_level++; // we are in a new paren level.
			}

			if ( $current_character == ')' ) {
				if ( $paren_level == 1 ) {
					return $i;
				} else {
					--$paren_level;
				}
			}
		}

		throw new ParseException("Malformed expression, expecting )");
	}
}
