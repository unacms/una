<?php
/**
 * @private
 */
class Less_Tree_Quoted extends Less_Tree implements Less_Tree_HasValueProperty {
	public $escaped;
	/** @var string */
	public $value;
	public $quote;
	public $index;
	public $currentFileInfo;

	public $variableRegex = '/@\{([\w-]+)\}/';
	public $propRegex = '/\$\{([\w-]+)\}/';

	/**
	 * @param string $str
	 */
	public function __construct( $str, $content = '', $escaped = true, $index = false, $currentFileInfo = null ) {
		$this->escaped = $escaped;
		$this->value = $content;
		if ( $str ) {
			$this->quote = $str[0];
		}
		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ) {
		if ( !$this->escaped ) {
			$output->add( $this->quote, $this->currentFileInfo, $this->index );
		}
		$output->add( $this->value );
		if ( !$this->escaped ) {
			$output->add( $this->quote );
		}
	}

	/**
	 * @see less-3.13.1.js#Quoted.prototype.containsVariables
	 */
	public function containsVariables() {
		return preg_match( $this->variableRegex, $this->value );
	}

	private function variableReplacement( $r, $env ) {
		do {
			$value = $r;
			if ( preg_match_all( $this->variableRegex, $value, $matches ) ) {
				foreach ( $matches[1] as $i => $match ) {
					$v = new Less_Tree_Variable( '@' . $match, $this->index, $this->currentFileInfo );
					$v = $v->compile( $env );
					$v = ( $v instanceof self ) ? $v->value : $v->toCSS();
					$r = str_replace( $matches[0][$i], $v, $r );
				}
			}
		} while ( $r != $value );
		return $r;
	}

	private function propertyReplacement( $r, $env ) {
		do {
			$value = $r;
			if ( preg_match_all( $this->propRegex, $value, $matches ) ) {
				foreach ( $matches[1] as $i => $match ) {
					$v = new Less_Tree_Property( '$' . $match, $this->index, $this->currentFileInfo );
					$v = $v->compile( $env );
					$v = ( $v instanceof self ) ? $v->value : $v->toCSS();
					$r = str_replace( $matches[0][$i], $v, $r );
				}
			}
		} while ( $r != $value );
		return $r;
	}

	public function compile( $env ) {
		$value = $this->value;
		$value = $this->variableReplacement( $value, $env );
		$value = $this->propertyReplacement( $value, $env );
		return new self( $this->quote . $value . $this->quote, $value, $this->escaped, $this->index, $this->currentFileInfo );
	}

	/**
	 * @param mixed $other
	 * @return int|null
	 * @see less-2.5.3.js#Quoted.prototype.compare
	 */
	public function compare( $other ) {
		if ( $other instanceof self && !$this->escaped && !$other->escaped ) {
			return Less_Tree::numericCompare( $this->value, $other->value );
		} else {
			return (
				Less_Parser::is_method( $other, 'toCSS' )
				&& $this->toCSS() === $other->toCSS()
			) ? 0 : null;
		}
	}
}
