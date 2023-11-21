<?php
/**
 * @private
 * @see less.tree.Call in less.js 3.0.0 https://github.com/less/less.js/blob/v3.0.0/dist/less.js#L6336
 */
class Less_Tree_Call extends Less_Tree {
	public $value;

	public $name;
	public $args;
	/** @var bool */
	public $mathOn;
	public $index;
	public $currentFileInfo;
	public $type = 'Call';

	public function __construct( $name, $args, $index, $currentFileInfo = null ) {
		$this->name = $name;
		$this->args = $args;
		$this->mathOn = ( $name !== 'calc' );
		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
	}

	public function accept( $visitor ) {
		$this->args = $visitor->visitArray( $this->args );
	}

	//
	// When evaluating a function call,
	// we either find the function in Less_Functions,
	// in which case we call it, passing the evaluated arguments,
	// or we simply print it out as it literal CSS.
	//
	// The reason why we compile the arguments, is in the case one
	// of them is a LESS variable that only PHP knows the value of,
	// like: `saturate(@mycolor)`.
	// The function should receive the value, not the variable.
	//
	public function compile( $env = null ) {
		// Turn off math for calc(). https://phabricator.wikimedia.org/T331688
		$currentMathContext = Less_Environment::$mathOn;
		Less_Environment::$mathOn = $this->mathOn;

		$args = [];
		foreach ( $this->args as $a ) {
			$args[] = $a->compile( $env );
		}

		Less_Environment::$mathOn = $currentMathContext;

		$nameLC = strtolower( $this->name );
		switch ( $nameLC ) {
			case '%':
			$nameLC = '_percent';
				break;

			case 'get-unit':
			$nameLC = 'getunit';
				break;

			case 'data-uri':
			$nameLC = 'datauri';
				break;

			case 'svg-gradient':
			$nameLC = 'svggradient';
				break;
		}

		$result = null;
		if ( $nameLC === 'default' ) {
			$result = Less_Tree_DefaultFunc::compile();
		} else {
			$func = null;
			if ( method_exists( 'Less_Functions', $nameLC ) ) {
				$functions = new Less_Functions( $env, $this->currentFileInfo );
				$func = [ $functions, $nameLC ];
			} elseif ( isset( $env->functions[$nameLC] ) && is_callable( $env->functions[$nameLC] ) ) {
				$func = $env->functions[$nameLC];
			}
			// If the function name isn't known to LESS, output it unchanged as CSS.
			if ( $func ) {
				try {
					$result = call_user_func_array( $func, $args );
				} catch ( Exception $e ) {
					// Preserve original trace, especially from custom functions.
					// https://github.com/wikimedia/less.php/issues/38
					throw new Less_Exception_Compiler(
						'error evaluating function `' . $this->name . '` ' . $e->getMessage()
							. ' index: ' . $this->index,
						$e
					);
				}
			}
		}

		if ( $result !== null ) {
			return $result;
		}

		return new Less_Tree_Call( $this->name, $args, $this->index, $this->currentFileInfo );
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ) {
		$output->add( $this->name . '(', $this->currentFileInfo, $this->index );
		$args_len = count( $this->args );
		for ( $i = 0; $i < $args_len; $i++ ) {
			$this->args[$i]->genCSS( $output );
			if ( $i + 1 < $args_len ) {
				$output->add( ', ' );
			}
		}

		$output->add( ')' );
	}

	// public function toCSS(){
	//    return $this->compile()->toCSS();
	//}

}
