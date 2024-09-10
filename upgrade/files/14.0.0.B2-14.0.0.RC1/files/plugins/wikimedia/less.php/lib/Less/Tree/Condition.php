<?php
/**
 * @private
 */
class Less_Tree_Condition extends Less_Tree {

	public $op;
	public $lvalue;
	public $rvalue;
	public $index;
	public $negate;

	public function __construct( $op, $l, $r, $i = 0, $negate = false ) {
		$this->op = trim( $op );
		$this->lvalue = $l;
		$this->rvalue = $r;
		$this->index = $i;
		$this->negate = $negate;
	}

	public function accept( $visitor ) {
		$this->lvalue = $visitor->visitObj( $this->lvalue );
		$this->rvalue = $visitor->visitObj( $this->rvalue );
	}

	/**
	 * @param Less_Environment $env
	 * @return bool
	 * @see less-2.5.3.js#Condition.prototype.eval
	 */
	public function compile( $env ) {
		$a = $this->lvalue->compile( $env );
		$b = $this->rvalue->compile( $env );

		switch ( $this->op ) {
			case 'and':
				$result = $a && $b;
				break;

			case 'or':
				$result = $a || $b;
				break;

			default:
				$res = Less_Tree::nodeCompare( $a, $b );
				// In JS, switch(undefined) with -1,0,-1,defaults goes to `default`.
				// In PHP, switch(null) would go to case 0. Use if/else instead.
				if ( $res === -1 ) {
					$result = $this->op === '<' || $this->op === '=<' || $this->op === '<=';
				} elseif ( $res === 0 ) {
					$result = $this->op === '=' || $this->op === '>=' || $this->op === '=<' || $this->op === '<=';
				} elseif ( $res === 1 ) {
					$result = $this->op === '>' || $this->op === '>=';
				} else {
					// null, NAN
					$result = false;
				}
		}

		return $this->negate ? !$result : $result;
	}

}
