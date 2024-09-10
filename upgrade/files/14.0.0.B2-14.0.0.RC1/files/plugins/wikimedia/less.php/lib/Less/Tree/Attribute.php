<?php
/**
 * @private
 * @see less-2.5.3.js#Attribute.prototype
 */
class Less_Tree_Attribute extends Less_Tree implements Less_Tree_HasValueProperty {
	public $key;
	public $op;
	public $value;

	/**
	 * @param string $key
	 * @param null|string $op
	 * @param null|string|Less_Tree $value
	 */
	public function __construct( $key, $op, $value ) {
		$this->key = $key;
		$this->op = $op;
		$this->value = $value;
	}

	public function compile( $env ) {
		// Optimization: Avoid object churn for the common case.
		// Attributes are very common in CSS/LESS input, but rarely involve dynamic values.
		if ( !$this->key instanceof Less_Tree && !$this->value instanceof Less_Tree ) {
			return $this;
		}

		return new self(
			$this->key instanceof Less_Tree ? $this->key->compile( $env ) : $this->key,
			$this->op,
			$this->value instanceof Less_Tree ? $this->value->compile( $env ) : $this->value );
	}

	public function genCSS( $output ) {
		$output->add( $this->toCSS() );
	}

	public function toCSS() {
		$value = $this->key;

		if ( $this->op ) {
			$value .= $this->op;
			$value .= ( is_object( $this->value ) ? $this->value->toCSS() : $this->value );
		}

		return '[' . $value . ']';
	}
}
