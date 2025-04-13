<?php
/**
 * @private
 * @see less-3.13.1.js#JavaScript.prototype
 */
class Less_Tree_JavaScript extends Less_Tree {

	public $escaped;
	public $expression;
	public $index;

	/**
	 * @param string $string
	 * @param bool $escaped
	 * @param int $index
	 */
	public function __construct( $string, $escaped, $index ) {
		$this->escaped = $escaped;
		$this->expression = $string;
		$this->index = $index;
	}

	public function compile( $env ) {
		return new Less_Tree_Anonymous( '/* Sorry, can not do JavaScript evaluation in PHP... :( */' );
	}

}
