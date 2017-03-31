<?php

// Operator Map
$operator_precendence = [
	'*' => 2,
	'/' => 2,
	'+' => 0,
	'-' => 0
];

const TYPE_OPERATOR = 1;
const TYPE_OPERAND = 2;

class ExpressionTree
{
	public $root = null;
	public $type = null;
	public $left = null;
	public $right = null;
}

function FindLastLeast(string $expression) : int
{
	global $operator_precendence;

	$smallest_seen_thus_far = PHP_INT_MAX;
	$index = PHP_INT_MAX;

	for ( $i = 0; $i < strlen($expression); $i++ ) {
		if ( is_numeric($expression[$i]) ) {
			continue;
		}

		if ( $operator_precendence[$expression[$i]] <= $smallest_seen_thus_far ) {
			$smallest_seen_thus_far = $operator_precendence[$expression[$i]];
			$index = $i;
		}
	}

	return $index;
}

function ParseExpression(ExpressionTree $root, string $expression)
{
	if ( is_numeric($expression) ) {
		$root->type = TYPE_OPERAND;
		$root->root = $expression;

		return;
	}

	$root->type = TYPE_OPERATOR;

	$index = FindLastLeast($expression);

	// Find the last operator of least precendence.
	// The the content to the left and right of the operator.
	$left = substr($expression, 0, $index);
	$right = substr($expression, $index + 1);

	$root->root = $expression[$index];

	$root->left = new ExpressionTree;
	$root->right = new ExpressionTree;

	ParseExpression($root->left, $left);
	ParseExpression($root->right, $right);
}

function SolveExpression(ExpressionTree $root)
{
	if ( is_numeric($root->root)  ) {
		return intval($root->root);
	}

	switch ( $root->root ) {
		case '+':
			return SolveExpression($root->left) + SolveExpression($root->right);
			break;
		case '-':
			return SolveExpression($root->left) - SolveExpression($root->right);
			break;
		case '*':
			return SolveExpression($root->left) * SolveExpression($root->right);
			break;
		case '/':
			return SolveExpression($root->left) / SolveExpression($root->right);
			break;
	}
}

$root = new ExpressionTree;

ParseExpression($root, '1-2+3*3*4*6-2');

echo SolveExpression($root);
