<?php
/**
 * @private
 */
class Less_Tree_Mixin_Definition extends Less_Tree_Ruleset {
	public $name;
	public $selectors;
	public $params;
	public $arity = 0;
	public $rules;
	public $lookups		= [];
	public $required	= 0;
	public $frames		= [];
	public $condition;
	public $variadic;
	public $optionalParameters = [];

	public function __construct( $name, $params, $rules, $condition, $variadic = false, $frames = [] ) {
		$this->name = $name;
		$this->selectors = [ new Less_Tree_Selector( [ new Less_Tree_Element( null, $name ) ] ) ];

		$this->params = $params;
		$this->condition = $condition;
		$this->variadic = $variadic;
		$this->rules = $rules;

		if ( $params ) {
			$this->arity = count( $params );
			foreach ( $params as $p ) {
				if ( !isset( $p['name'] ) || ( $p['name'] && !isset( $p['value'] ) ) ) {
					$this->required++;
				} else {
					$this->optionalParameters[ (string)$p['name'] ] = true;
				}
			}
		}

		$this->frames = $frames;
		$this->SetRulesetIndex();
	}

	/**
	 * @param Less_Environment $env
	 * @see less-2.5.3.js#Definition.prototype.evalParams
	 */
	public function compileParams( $env, $mixinFrames, $args = [], &$evaldArguments = [] ) {
		$frame = new Less_Tree_Ruleset( null, [] );
		$params = $this->params;
		$mixinEnv = null;
		$argsLength = 0;

		if ( $args ) {
			$argsLength = count( $args );
			for ( $i = 0; $i < $argsLength; $i++ ) {
				$arg = $args[$i];

				if ( $arg && $arg['name'] ) {
					$isNamedFound = false;

					foreach ( $params as $j => $param ) {
						if ( !isset( $evaldArguments[$j] ) && $arg['name'] === $param['name'] ) {
							$evaldArguments[$j] = $arg['value']->compile( $env );
							array_unshift( $frame->rules, new Less_Tree_Declaration( $arg['name'], $arg['value']->compile( $env ) ) );
							$isNamedFound = true;
							break;
						}
					}
					if ( $isNamedFound ) {
						array_splice( $args, $i, 1 );
						$i--;
						$argsLength--;
					} else {
						throw new Less_Exception_Compiler( "Named argument for " . $this->name . ' ' . $args[$i]['name'] . ' not found' );
					}
				}
			}
		}

		$argIndex = 0;
		foreach ( $params as $i => $param ) {
			if ( isset( $evaldArguments[$i] ) ) {
				continue;
			}

			$arg = $args[$argIndex] ?? null;

			$name = $param['name'] ?? null;
			if ( $name ) {
				if ( isset( $param['variadic'] ) ) {
					$varargs = [];
					for ( $j = $argIndex; $j < $argsLength; $j++ ) {
						$varargs[] = $args[$j]['value']->compile( $env );
					}
					$expression = new Less_Tree_Expression( $varargs );
					array_unshift( $frame->rules, new Less_Tree_Declaration( $name, $expression->compile( $env ) ) );
				} else {
					$val = ( $arg && $arg['value'] ) ? $arg['value'] : false;

					if ( $val ) {
						// This was a mixin call, pass in a detached ruleset of it's eval'd rules
						if ( is_array( $val ) ) {
							$val = new Less_Tree_DetachedRuleset( new Less_Tree_Ruleset( null, $val ) );
						} else {
							$val = $val->compile( $env );
						}
					} elseif ( isset( $param['value'] ) ) {

						if ( !$mixinEnv ) {
							$mixinEnv = $env->copyEvalEnv( array_merge( [ $frame ], $mixinFrames ) );
						}

						$val = $param['value']->compile( $mixinEnv );
						$frame->resetCache();
					} else {
						throw new Less_Exception_Compiler( "Wrong number of arguments for " . $this->name . " (" . $argsLength . ' for ' . $this->arity . ")" );
					}

					array_unshift( $frame->rules, new Less_Tree_Declaration( $name, $val ) );
					$evaldArguments[$i] = $val;
				}
			}

			if ( isset( $param['variadic'] ) && $args ) {
				for ( $j = $argIndex; $j < $argsLength; $j++ ) {
					$evaldArguments[$j] = $args[$j]['value']->compile( $env );
				}
			}
			$argIndex++;
		}

		ksort( $evaldArguments );
		$evaldArguments = array_values( $evaldArguments );

		return $frame;
	}

	public function compile( $env ) {
		if ( $this->frames ) {
			return new self( $this->name, $this->params, $this->rules, $this->condition, $this->variadic, $this->frames );
		}
		return new self( $this->name, $this->params, $this->rules, $this->condition, $this->variadic, $env->frames );
	}

	/**
	 * @param Less_Environment $env
	 * @param array|null $args
	 * @param bool|null $important
	 * @return Less_Tree_Ruleset
	 */
	public function evalCall( $env, $args = null, $important = null ) {
		Less_Environment::$mixin_stack++;

		$_arguments = [];

		if ( $this->frames ) {
			$mixinFrames = array_merge( $this->frames, $env->frames );
		} else {
			$mixinFrames = $env->frames;
		}

		$frame = $this->compileParams( $env, $mixinFrames, $args, $_arguments );

		$ex = new Less_Tree_Expression( $_arguments );
		array_unshift( $frame->rules, new Less_Tree_Declaration( '@arguments', $ex->compile( $env ) ) );

		$ruleset = new Less_Tree_Ruleset( null, $this->rules );
		$ruleset->originalRuleset = $this->ruleset_id;

		$ruleSetEnv = $env->copyEvalEnv( array_merge( [ $this, $frame ], $mixinFrames ) );
		$ruleset = $ruleset->compile( $ruleSetEnv );

		if ( $important ) {
			$ruleset = $ruleset->makeImportant();
		}

		Less_Environment::$mixin_stack--;

		return $ruleset;
	}

	/**
	 * @param array $args
	 * @param Less_Environment $env
	 * @return bool
	 */
	public function matchCondition( $args, $env ) {
		if ( !$this->condition ) {
			return true;
		}

		// set array to prevent error on array_merge
		if ( !is_array( $this->frames ) ) {
			 $this->frames = [];
		}

		$frame = $this->compileParams( $env, array_merge( $this->frames, $env->frames ), $args );

		$compile_env = $env->copyEvalEnv(
			array_merge(
				[ $frame ], // the parameter variables
				$this->frames, // the parent namespace/mixin frames
				$env->frames // the current environment frames
			)
		);
		$compile_env->functions = $env->functions;

		return (bool)$this->condition->compile( $compile_env );
	}

	public function makeImportant() {
		$important_rules = [];
		foreach ( $this->rules as $rule ) {
			if ( $rule instanceof Less_Tree_Declaration || $rule instanceof self || $rule instanceof Less_Tree_NameValue ) {
				$important_rules[] = $rule->makeImportant();
			} else {
				$important_rules[] = $rule;
			}
		}
		return new self( $this->name, $this->params, $important_rules, $this->condition, $this->variadic, $this->frames );
	}

	/**
	 * @param array[] $args
	 * @param Less_Environment|null $env
	 * @see less-2.5.3.js#Definition.prototype.matchArgs
	 */
	public function matchArgs( $args, $env = null ) {
		$allArgsCnt = count( $args );
		$requiredArgsCnt = 0;
		foreach ( $args as $arg ) {
			if ( !array_key_exists( $arg['name'], $this->optionalParameters ) ) {
				$requiredArgsCnt++;
			}
		}
		if ( !$this->variadic ) {
			if ( $requiredArgsCnt < $this->required ) {
				return false;
			}
			if ( $allArgsCnt > count( $this->params ) ) {
				return false;
			}
		} else {
			if ( $requiredArgsCnt < ( $this->required - 1 ) ) {
				return false;
			}
		}

		$len = min( $requiredArgsCnt, $this->arity );

		for ( $i = 0; $i < $len; $i++ ) {
			if ( !isset( $this->params[$i]['name'] ) && !isset( $this->params[$i]['variadic'] ) ) {
				if ( $args[$i]['value']->compile( $env )->toCSS() != $this->params[$i]['value']->compile( $env )->toCSS() ) {
					return false;
				}
			}
		}

		return true;
	}

}
