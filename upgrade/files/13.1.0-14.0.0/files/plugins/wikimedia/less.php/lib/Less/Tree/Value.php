<?php
/**
 * @private
 */
class Less_Tree_Value extends Less_Tree implements Less_Tree_HasValueProperty {

	/** @var Less_Tree[] */
	public $value;

	public $index;
	public $currentFileInfo;

	/**
	 * @param array<Less_Tree> $value
	 */
	public function __construct( $value, $index = null ) {
		$this->value = $value;
		$this->index = $index;
	}

	public function accept( $visitor ) {
		$this->value = $visitor->visitArray( $this->value );
	}

	public function compile( $env ) {
		$ret = [];
		$i = 0;
		foreach ( $this->value as $i => $v ) {
			$ret[] = $v->compile( $env );
		}
		if ( $i > 0 ) {
			return new self( $ret );
		}
		return $ret[0];
	}

	/**
	 * @see less-2.5.3.js#Value.prototype.genCSS
	 */
	public function genCSS( $output ) {
		$len = count( $this->value );
		for ( $i = 0; $i < $len; $i++ ) {
			$this->value[$i]->genCSS( $output );
			if ( $i + 1 < $len ) {
				$output->add( Less_Parser::$options['compress'] ? ',' : ', ' );
			}
		}
	}

}
