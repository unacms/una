<?php
/**
 * @private
 */
class Less_Tree_Negative extends Less_Tree implements Less_Tree_HasValueProperty {

	public $value;

	public function __construct( $node ) {
		$this->value = $node;
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ) {
		$output->add( '-' );
		$this->value->genCSS( $output );
	}

	public function compile( $env ) {
		if ( $env->isMathOn() ) {
			$ret = new Less_Tree_Operation( '*', [ new Less_Tree_Dimension( -1 ), $this->value ] );
			return $ret->compile( $env );
		}
		return new self( $this->value->compile( $env ) );
	}
}
