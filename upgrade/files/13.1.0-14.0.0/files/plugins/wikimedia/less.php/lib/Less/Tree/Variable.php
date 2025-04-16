<?php
/**
 * @private
 */
class Less_Tree_Variable extends Less_Tree {

	public $name;
	public $index;
	public $currentFileInfo;
	public $evaluating = false;

	/**
	 * @param string $name
	 */
	public function __construct( $name, $index = null, $currentFileInfo = null ) {
		$this->name = $name;
		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
	}

	/**
	 * @param Less_Environment $env
	 * @return Less_Tree|Less_Tree_Keyword|Less_Tree_Quoted
	 * @see less-3.13.1.js#Variable.prototype.eval
	 */
	public function compile( $env ) {
		// Optimization: Less.js checks if string starts with @@, we only check if second char is @
		if ( $this->name[1] === '@' ) {
			$v = new self( substr( $this->name, 1 ), $this->index + 1, $this->currentFileInfo );
			// While some Less_Tree nodes have no 'value', we know these can't occur after a
			// variable assignment (would have been a ParseError).
			$name = '@' . $v->compile( $env )->value;
		} else {
			$name = $this->name;
		}

		if ( $this->evaluating ) {
			throw new Less_Exception_Compiler( "Recursive variable definition for " . $name, null, $this->index, $this->currentFileInfo );
		}

		$this->evaluating = true;
		$variable = null;
		foreach ( $env->frames as $frame ) {
			/** @var Less_Tree_Ruleset $frame */
			$v = $frame->variable( $name );
			if ( $v ) {
				if ( isset( $v->important ) && $v->important ) {
					$importantScopeLength = count( $env->importantScope );
					$env->importantScope[ $importantScopeLength - 1 ]['important'] = $v->important;
				}
				// If in calc, wrap vars in a function call to cascade evaluate args first
				if ( $env->inCalc ) {
					$call = new Less_Tree_Call( '_SELF', [ $v->value ], $this->index, $this->currentFileInfo );
					$variable = $call->compile( $env );
					break;
				} else {
					$variable = $v->value->compile( $env );
					break;
				}
			}
		}
		if ( $variable ) {
			$this->evaluating = false;
			return $variable;
		}

		throw new Less_Exception_Compiler( "variable " . $name . " is undefined in file " . $this->currentFileInfo["filename"], null, $this->index, $this->currentFileInfo );
	}

}
