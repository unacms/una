<?php

/**
 * Tree
 */
class Less_Tree {

	public $parensInOp = false;
	public $extendOnEveryPath;
	public $allExtends;

	/**
	 * @var Less_Parser
	 * @see less-3.13.1.js#Node.prototype.parse
	 */
	public static $parse;

	/**
	 * @see less-2.5.3.js#Node.prototype.toCSS
	 */
	public function toCSS() {
		$output = new Less_Output();
		$this->genCSS( $output );
		return $output->toString();
	}

	/**
	 * Generate CSS by adding it to the output object
	 *
	 * @param Less_Output $output The output
	 * @return void
	 */
	public function genCSS( $output ) {
	}

	public function compile( $env ) {
		return $this;
	}

	/**
	 * @param string $op
	 * @param float $a
	 * @param float $b
	 * @see less-2.5.3.js#Node.prototype._operate
	 */
	protected function _operate( $op, $a, $b ) {
		switch ( $op ) {
			case '+':
				return $a + $b;
			case '-':
				return $a - $b;
			case '*':
				return $a * $b;
			case '/':
				return $a / $b;
		}
	}

	/**
	 * @see less-2.5.3.js#Node.prototype.fround
	 */
	protected function fround( $value ) {
		if ( $value === 0 ) {
			return $value;
		}

		// TODO: Migrate to passing $env.
		if ( Less_Parser::$options['numPrecision'] ) {
			$p = pow( 10, Less_Parser::$options['numPrecision'] );
			return round( $value * $p ) / $p;
		}
		return $value;
	}

	/**
	 * @param Less_Output $output
	 * @param Less_Tree_Ruleset[] $rules
	 */
	public static function outputRuleset( $output, $rules ) {
		$ruleCnt = count( $rules );
		Less_Environment::$tabLevel++;

		// Compressed
		if ( Less_Parser::$options['compress'] ) {
			$output->add( '{' );
			for ( $i = 0; $i < $ruleCnt; $i++ ) {
				$rules[$i]->genCSS( $output );
			}

			$output->add( '}' );
			Less_Environment::$tabLevel--;
			return;
		}

		// Non-compressed
		$tabSetStr = "\n" . str_repeat( Less_Parser::$options['indentation'], Less_Environment::$tabLevel - 1 );
		$tabRuleStr = $tabSetStr . Less_Parser::$options['indentation'];

		$output->add( " {" );
		for ( $i = 0; $i < $ruleCnt; $i++ ) {
			$output->add( $tabRuleStr );
			$rules[$i]->genCSS( $output );
		}
		Less_Environment::$tabLevel--;
		$output->add( $tabSetStr . '}' );
	}

	public function accept( $visitor ) {
	}

	/**
	 * @param Less_Tree $a
	 * @param Less_Tree $b
	 * @return int|null
	 * @see less-2.5.3.js#Node.compare
	 */
	public static function nodeCompare( $a, $b ) {
		// Less_Tree subclasses that implement compare() are:
		// Anonymous, Color, Dimension, Quoted, Unit
		$aHasCompare = ( $a instanceof Less_Tree_Anonymous || $a instanceof Less_Tree_Color
			|| $a instanceof Less_Tree_Dimension || $a instanceof Less_Tree_Quoted || $a instanceof Less_Tree_Unit
		);
		$bHasCompare = ( $b instanceof Less_Tree_Anonymous || $b instanceof Less_Tree_Color
			|| $b instanceof Less_Tree_Dimension || $b instanceof Less_Tree_Quoted || $b instanceof Less_Tree_Unit
		);

		if ( $aHasCompare &&
			!( $b instanceof Less_Tree_Quoted || $b instanceof Less_Tree_Anonymous )
		) {
			// for "symmetric results" force toCSS-based comparison via b.compare()
			// of Quoted or Anonymous if either value is one of those
			// @phan-suppress-next-line PhanUndeclaredMethod
			return $a->compare( $b );
		} elseif ( $bHasCompare ) {
			$res = $b->compare( $a );
			// In JS, `-undefined` produces NAN, which, just like undefined
			// will enter the the default/false branch of Less_Tree_Condition#compile.
			// In PHP, `-null` is 0. To ensure parity, preserve the null.
			return $res !== null ? -$res : null;
		} elseif ( get_class( $a ) !== get_class( $b ) ) {
			return null;
		}

		// Less_Tree subclasses that have an array value: Less_Tree_Expression, Less_Tree_Value
		// @phan-suppress-next-line PhanUndeclaredProperty
		$aval = $a->value ?? [];
		$bval = $b->value ?? [];
		if ( !( $a instanceof Less_Tree_Expression || $a instanceof Less_Tree_Value ) ) {
			return $aval === $bval ? 0 : null;
		}
		'@phan-var Less_Tree[] $aval';
		'@phan-var Less_Tree[] $bval';
		if ( count( $aval ) !== count( $bval ) ) {
			return null;
		}
		foreach ( $aval as $i => $item ) {
			if ( self::nodeCompare( $item, $bval[$i] ) !== 0 ) {
				return null;
			}
		}
		return 0;
	}

	/**
	 * @param string|float|int $a
	 * @param string|float|int $b
	 * @return int|null
	 * @see less-2.5.3.js#Node.numericCompare
	 */
	public static function numericCompare( $a, $b ) {
		return $a < $b ? -1
			: ( $a === $b ? 0
				: ( $a > $b ? 1
					// NAN is not greater, less, or equal
					: null
				)
			);
	}

	public static function ReferencedArray( $rules ) {
		foreach ( $rules as $rule ) {
			if ( method_exists( $rule, 'markReferenced' ) ) {
				$rule->markReferenced();
			}
		}
	}

	/**
	 * Requires php 5.3+
	 */
	public static function __set_state( $args ) {
		$class = get_called_class();
		$obj = new $class( null, null, null, null );
		foreach ( $args as $key => $val ) {
			$obj->$key = $val;
		}
		return $obj;
	}

}
