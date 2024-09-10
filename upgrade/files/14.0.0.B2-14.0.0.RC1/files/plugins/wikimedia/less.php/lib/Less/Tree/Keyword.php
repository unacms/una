<?php
/**
 * @private
 */
class Less_Tree_Keyword extends Less_Tree implements Less_Tree_HasValueProperty {

	/** @var string */
	public $value;

	/**
	 * @param string $value
	 */
	public function __construct( $value ) {
		$this->value = $value;
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ) {
		if ( $this->value === '%' ) {
			throw new Less_Exception_Compiler( "Invalid % without number" );
		}

		$output->add( $this->value );
	}
}
