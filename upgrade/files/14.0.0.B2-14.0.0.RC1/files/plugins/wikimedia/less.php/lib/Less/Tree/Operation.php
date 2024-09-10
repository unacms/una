<?php
/**
 * @private
 * @see less-3.13.1.js#Operation.prototype
 */
class Less_Tree_Operation extends Less_Tree {

	public $op;
	public $operands;
	public $isSpaced;

	/**
	 * @param string $op
	 */
	public function __construct( $op, $operands, $isSpaced = false ) {
		$this->op = trim( $op );
		$this->operands = $operands;
		$this->isSpaced = $isSpaced;
	}

	public function accept( $visitor ) {
		$this->operands = $visitor->visitArray( $this->operands );
	}

	public function compile( $env ) {
		$a = $this->operands[0]->compile( $env );
		$b = $this->operands[1]->compile( $env );

		// Skip operation if argument was not compiled down to a non-operable value.
		// For example, if one argument is a Less_Tree_Call like 'var(--foo)' then we
		// preserve it as literal for native CSS.
		// https://phabricator.wikimedia.org/T331688
		if ( $env->isMathOn( $this->op ) ) {
			$op = $this->op === './' ? '/' : $this->op;

			if ( $a instanceof Less_Tree_Dimension && $b instanceof Less_Tree_Color ) {
				$a = $a->toColor();
			} elseif ( $b instanceof Less_Tree_Dimension && $a instanceof Less_Tree_Color ) {
				$b = $b->toColor();
			}

			if ( !( $a instanceof Less_Tree_Dimension || $a instanceof Less_Tree_Color ) ) {
				if ( $a instanceof Less_Tree_Operation && $a->op === '/' && $env->math === Less_Environment::MATH_PARENS_DIVISION
				) {
					return new self( $this->op, [ $a, $b ], $this->isSpaced );
				}
				throw new Less_Exception_Compiler( "Operation on an invalid type" );
			}

			return $a->operate( $op, $b );
		} else {
			return new self( $this->op, [ $a, $b ], $this->isSpaced );
		}
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ) {
		$this->operands[0]->genCSS( $output );
		if ( $this->isSpaced ) {
			$output->add( " " );
		}
		$output->add( $this->op );
		if ( $this->isSpaced ) {
			$output->add( ' ' );
		}
		$this->operands[1]->genCSS( $output );
	}

}
