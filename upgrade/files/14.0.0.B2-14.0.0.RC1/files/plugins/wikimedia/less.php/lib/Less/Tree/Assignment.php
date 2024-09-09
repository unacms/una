<?php
/**
 * @private
 * @see less-2.5.3.js#Assignment.prototype
 */
class Less_Tree_Assignment extends Less_Tree implements Less_Tree_HasValueProperty {
	public $key;
	public $value;

	public function __construct( string $key, Less_Tree $val ) {
		$this->key = $key;
		$this->value = $val;
	}

	public function accept( $visitor ) {
		$this->value = $visitor->visitObj( $this->value );
	}

	public function compile( $env ) {
		// NOTE: Less.js has a conditional for $this->value,
		// but this appears unreachable ($val is not optional).
		return new self( $this->key, $this->value->compile( $env ) );
	}

	public function genCSS( $output ) {
		$output->add( $this->key . '=' );
		$this->value->genCSS( $output );
	}
}
