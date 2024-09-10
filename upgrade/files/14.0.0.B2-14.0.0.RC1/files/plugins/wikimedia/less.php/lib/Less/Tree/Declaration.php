<?php
/**
 * @private
 * @see less-3.13.1.js#Declaration.prototype
 * @todo check for feature parity with 3.13.1
 */
class Less_Tree_Declaration extends Less_Tree implements Less_Tree_HasValueProperty {

	public $name;
	/** @var Less_Tree[]|Less_Tree_Anonymous */
	public $value;
	/** @var string */
	public $important;
	public $merge;
	public $index;
	public $inline;
	public $variable;
	public $currentFileInfo;

	/**
	 * In the upstream `parsed` is stored in `Node`, but Less_Tree_Declaration is the only place
	 * that make use of it.
	 * @see less-3.13.1.js#Node.parsed
	 * @var bool
	 */
	public $parsed = false;

	/**
	 * @param string|array<Less_Tree_Keyword|Less_Tree_Variable> $name
	 * @param Less_Tree|string|null $value
	 * @param null|false|string $important
	 * @param null|false|string $merge
	 * @param int|null $index
	 * @param array|null $currentFileInfo
	 * @param bool $inline
	 * @param bool|null $variable
	 */
	public function __construct( $name, $value = null, $important = null, $merge = null,
		$index = null, $currentFileInfo = null, $inline = false, $variable = null ) {
		$this->name = $name;
		$this->value = ( $value instanceof Less_Tree )
			? $value
			: new Less_Tree_Value( [ $value ? new Less_Tree_Anonymous( $value ) : null ] );
		$this->important = $important ? ' ' . trim( $important ) : '';
		$this->merge = $merge;
		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
		$this->inline = $inline;
		$this->variable = $variable ?? ( is_string( $name ) && $name[0] === '@' );
	}

	public function accept( $visitor ) {
		$this->value = $visitor->visitObj( $this->value );
	}

	/**
	 * @see less-2.5.3.js#Rule.prototype.genCSS
	 */
	public function genCSS( $output ) {
		$output->add( $this->name . ( Less_Parser::$options['compress'] ? ':' : ': ' ), $this->currentFileInfo, $this->index );
		try {
			$this->value->genCSS( $output );

		} catch ( Less_Exception_Parser $e ) {
			$e->index = $this->index;
			$e->currentFile = $this->currentFileInfo;
			throw $e;
		}
		$output->add( $this->important . ( ( $this->inline || ( Less_Environment::$lastRule && Less_Parser::$options['compress'] ) ) ? "" : ";" ), $this->currentFileInfo, $this->index );
	}

	/**
	 * @see less-2.5.3.js#Rule.prototype.eval
	 * @param Less_Environment $env
	 * @return self
	 */
	public function compile( $env ) {
		$variable = $this->variable;
		$name = $this->name;
		if ( is_array( $name ) ) {
			// expand 'primitive' name directly to get
			// things faster (~10% for benchmark.less):
			if ( count( $name ) === 1 && $name[0] instanceof Less_Tree_Keyword ) {
				$name = $name[0]->value;
			} else {
				$name = $this->CompileName( $env, $name );
			}
			$variable = false; // never treat expanded interpolation as new variable name
		}

		$mathBypass = false;
		$prevMath = $env->math;
		if ( $name === "font" && $env->math === Less_Environment::MATH_ALWAYS ) {
			$mathBypass = true;
			$env->math = Less_Environment::MATH_PARENS_DIVISION;
		}

		try {
			$env->importantScope[] = [];
			$evaldValue = $this->value->compile( $env );

			if ( !$this->variable && $evaldValue instanceof Less_Tree_DetachedRuleset ) {
				throw new Less_Exception_Compiler( "Rulesets cannot be evaluated on a property.", null, $this->index, $this->currentFileInfo );
			}

			$important = $this->important;
			$importantResult = array_pop( $env->importantScope );

			if ( !$important && isset( $importantResult['important'] ) && $importantResult['important'] ) {
				$important = $importantResult['important'];
			}

			$return = new Less_Tree_Declaration( $name,
				$evaldValue,
				$important,
				$this->merge,
				$this->index,
				$this->currentFileInfo,
				$this->inline,
				$variable,
			);

		} catch ( Less_Exception_Parser $e ) {
			if ( !is_numeric( $e->index ) ) {
				$e->index = $this->index;
				$e->currentFile = $this->currentFileInfo;
				$e->genMessage();
			}
			throw $e;
		}

		if ( $mathBypass ) {
			$env->math = $prevMath;
		}

		return $return;
	}

	public function CompileName( $env, $name ) {
		$output = new Less_Output();
		foreach ( $name as $n ) {
			$n->compile( $env )->genCSS( $output );
		}
		return $output->toString();
	}

	public function makeImportant() {
		return new self( $this->name, $this->value, '!important', $this->merge, $this->index, $this->currentFileInfo, $this->inline );
	}

	public function mark( $value ) {
		if ( !is_array( $this->value ) ) {

			if ( method_exists( $value, 'markReferenced' ) ) {
				$value->markReferenced();
			}
		} else {
			foreach ( $this->value as $v ) {
				$this->mark( $v );
			}
		}
	}

	public function markReferenced() {
		if ( $this->value ) {
			$this->mark( $this->value );
		}
	}

}
